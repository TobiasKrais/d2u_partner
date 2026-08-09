<?php

namespace D2U_Partner;

use rex;
use rex_sql;

use function is_array;

/**
 * Partner details.
 */
class Partner
{
    /** @var int Database partner ID */
    public $partner_id = 0;

    /** @var string Partner name */
    public $name = '';

    /** @var string Picture name */
    public $picture = '';

    /** @var string Partner website URL */
    public $url = '';

    /** @var string Redaxo article ID for link */
    public $article_id = 0;

    /** @var Category[] Array with categories, partner belongs to */
    public $categories = [];

    /** @var string online status "online" or "offline" */
    public $online_status = 'offline';

    /**
     * Constructor.
     * @param int $partner_id partner ID
     */
    public function __construct($partner_id)
    {
        if ($partner_id > 0) {
            $query = 'SELECT * FROM '. rex::getTablePrefix() .'d2u_partner '
                    .'WHERE partner_id = '. $partner_id;
            $result = rex_sql::factory();
            $result->setQuery($query);

            if ($result->getRows() > 0) {
                $this->partner_id = $result->getValue('partner_id');
                $this->name = stripslashes($result->getValue('name'));
                $this->picture = $result->getValue('picture');
                $this->url = $result->getValue('url');
                $this->article_id = $result->getValue('article_id');
                $category_ids = preg_grep('/^\s*$/s', explode('|', $result->getValue('category_ids')), PREG_GREP_INVERT);
                $category_ids = is_array($category_ids) ? array_map('intval', $category_ids) : [];
                foreach ($category_ids as $category_id) {
                    $this->categories[$category_id] = new Category($category_id);
                }
                $this->online_status = $result->getValue('online_status');
            }
        } else {
            return;
        }
    }

    /**
     * Changes the online status of this object.
     */
    public function changeStatus(): void
    {
        if ('online' === $this->online_status) {
            if ($this->partner_id > 0) {
                $query = 'UPDATE '. rex::getTablePrefix() .'d2u_partner '
                    ."SET online_status = 'offline' "
                    .'WHERE partner_id = '. $this->partner_id;
                $result = rex_sql::factory();
                $result->setQuery($query);
            }
            $this->online_status = 'offline';
        } else {
            if ($this->partner_id > 0) {
                $query = 'UPDATE '. rex::getTablePrefix() .'d2u_partner '
                    ."SET online_status = 'online' "
                    .'WHERE partner_id = '. $this->partner_id;
                $result = rex_sql::factory();
                $result->setQuery($query);
            }
            $this->online_status = 'online';
        }
    }

    /**
     * Deletes the object.
     */
    public function delete(): void
    {
        $query_lang = 'DELETE FROM '. rex::getTablePrefix() .'d2u_partner '
            .'WHERE partner_id = '. $this->partner_id;
        $result_lang = rex_sql::factory();
        $result_lang->setQuery($query_lang);
    }

     /**
      * Create an empty object instance.
      * @return Partner empty new object
      */
     public static function factory()
     {
         return new self(0);
     }

    /**
     * Get all partners.
     * @param bool $online_only If only online partner should be returned true, otherwise false
     * @return Partner[] Array with partner objects
     */
    public static function getAll($online_only = true)
    {
        $query = 'SELECT partner_id FROM '. rex::getTablePrefix() .'d2u_partner ';
        if ($online_only) {
            $query .= "WHERE online_status = 'online' ";
        }
        $query .= 'ORDER BY name';

        $result = rex_sql::factory();
        $result->setQuery($query);

        $partner = [];
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $partner[$result->getValue('partner_id')] = new self($result->getValue('partner_id'));
            $result->next();
        }

        return $partner;
    }

    /**
     * Updates or inserts the object into database.
     * @return bool true if successful
     */
    public function save()
    {
        $error = true;

        // Save the not language specific part
        $pre_save_partner = new self($this->partner_id);

        if (0 === $this->partner_id || $pre_save_partner !== $this) {
            $query = rex::getTablePrefix() .'d2u_partner SET '
                    .'name = :name, '
                    .'picture = :picture, '
                    .'url = :url, '
                    .'article_id = '. (int) $this->article_id .', '
                    .'category_ids = :category_ids, '
                    .'online_status = :online_status ';

            if (0 === $this->partner_id) {
                $query = 'INSERT INTO '. $query;
            } else {
                $query = 'UPDATE '. $query .' WHERE partner_id = '. (int) $this->partner_id;
            }

            $result = rex_sql::factory();
            $result->setQuery($query, [
                ':name' => $this->name,
                ':picture' => $this->picture,
                ':url' => $this->url,
                ':category_ids' => '|' . implode('|', array_keys($this->categories)) . '|',
                ':online_status' => $this->online_status,
            ]);
            if (0 === $this->partner_id) {
                $this->partner_id = (int) $result->getLastId();
                $error = !$result->hasError();
            }
        }

        return $error;
    }
}
