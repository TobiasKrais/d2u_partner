<?php

namespace D2U_Partner;

use rex;
use rex_sql;

/**
 * Category class.
 */
class Category
{
    /** @var int Database ID */
    public $category_id = 0;

    /** @var string Name */
    public $name = '';

    /**
     * Constructor.
     * @param int $category_id category ID
     */
    public function __construct($category_id)
    {
        $query = 'SELECT * FROM '. rex::getTablePrefix() .'d2u_partner_categories '
                .'WHERE category_id = '. $category_id;
        $result = rex_sql::factory();
        $result->setQuery($query);

        if ($result->getRows() > 0) {
            $this->category_id = $result->getValue('category_id');
            $this->name = stripslashes($result->getValue('name'));
        }
    }

    /**
     * Deletes the object in all languages.
     */
    public function delete(): void
    {
        $query_lang = 'DELETE FROM '. rex::getTablePrefix() .'d2u_partner_categories '
            .'WHERE category_id = '. $this->category_id;
        $result_lang = rex_sql::factory();
        $result_lang->setQuery($query_lang);
    }

    /**
     * Get all categories.
     * @param bool $ignoreOfflines Ignore offline categories
     * @return Category[] array with Category objects
     */
    public static function getAll($ignoreOfflines = true)
    {
        $query = 'SELECT category_id FROM '. rex::getTablePrefix() .'d2u_partner_categories '
            .'ORDER BY name';
        $result = rex_sql::factory();
        $result->setQuery($query);

        $categories = [];
        for ($i = 0; $i < $result->getRows(); ++$i) {
            if ($ignoreOfflines) {
                $query_check_offline = 'SELECT partner_id FROM '. rex::getTablePrefix() .'d2u_partner '
                    ."WHERE category_ids LIKE '%|". $result->getValue('category_id') ."|%'";

                $result_check_offline = rex_sql::factory();
                $result_check_offline->setQuery($query_check_offline);
                if ($result_check_offline->getRows() > 0) {
                    $categories[$result->getValue('category_id')] = new self((int) $result->getValue('category_id'));
                }
            } else {
                $categories[$result->getValue('category_id')] = new self((int) $result->getValue('category_id'));
            }
            $result->next();
        }
        return $categories;
    }

    /**
     * Get the partneres of the category.
     * @param bool $only_online Show only online partner
     * @return Partner[] Partners in this category
     */
    public function getPartners($only_online = false)
    {
        $query = 'SELECT partner_id FROM '. rex::getTablePrefix() .'d2u_partner '
            ."WHERE category_ids LIKE '%|". $this->category_id ."|%' ";
        if ($only_online) {
            $query .= "AND online_status = 'online' ";
        }
        $query .= 'ORDER BY name ASC';
        $result = rex_sql::factory();
        $result->setQuery($query);

        $partner = [];
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $partner[] = new Partner($result->getValue('partner_id'));
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
        $pre_save_category = new self($this->category_id);

        if (0 === $this->category_id || $pre_save_category !== $this) {
            $query = rex::getTablePrefix() .'d2u_partner_categories SET '
                    .'name = :name ';

            if (0 === $this->category_id) {
                $query = 'INSERT INTO '. $query;
            } else {
                $query = 'UPDATE '. $query .' WHERE category_id = '. (int) $this->category_id;
            }
            $result = rex_sql::factory();
            $result->setQuery($query, [':name' => $this->name]);
            if (0 === $this->category_id) {
                $this->category_id = (int) $result->getLastId();
                $error = !$result->hasError();
            }
        }

        return $error;
    }
}
