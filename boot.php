<?php

if (rex::isBackend() && is_object(rex::getUser())) {
    rex_perm::register('d2u_partner[]', rex_i18n::msg('d2u_partner_rights_all'));
    rex_perm::register('d2u_partner[edit_data]', rex_i18n::msg('d2u_partner_rights_edit_data'), rex_perm::OPTIONS);
    rex_perm::register('d2u_partner[settings]', rex_i18n::msg('d2u_partner_rights_settings'), rex_perm::OPTIONS);
}

if (rex::isBackend()) {
    rex_extension::register('ART_PRE_DELETED', rex_d2u_partner_article_is_in_use(...));
    rex_extension::register('MEDIA_IS_IN_USE', rex_d2u_partner_media_is_in_use(...));
}

/**
 * Checks if article is used by this addon.
 * @param rex_extension_point<string> $ep Redaxo extension point
 * @throws rex_api_exception If article is used
 * @return string Empty string if article is not used
 */
function rex_d2u_partner_article_is_in_use(rex_extension_point $ep)
{
    $warning = [];
    $params = $ep->getParams();
    $article_id = $params['id'];

    // Partner
    $sql = rex_sql::factory();
    $sql->setQuery('SELECT partner_id, name FROM `' . rex::getTablePrefix() . 'd2u_partner` '
        .'WHERE article_id = '. $article_id);

    // Partner Warnings
    for ($i = 0; $i < $sql->getRows(); ++$i) {
        $message = '<a href="javascript:openPage(\'index.php?page=d2u_partner/partner&func=edit&entry_id='.
            $sql->getValue('partner_id') .'\')">'. rex_i18n::msg('d2u_partner') .': '. $sql->getValue('name') .'</a>';
        $warning[] = $message;
        $sql->next();
    }

    if (count($warning) > 0) {
        throw new rex_api_exception(rex_i18n::msg('d2u_helper_rex_article_cannot_delete') .'<ul><li>'. implode('</li><li>', $warning) .'</li></ul>');
    }

    return '';

}

/**
 * Checks if media is used by this addon.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_partner_media_is_in_use(rex_extension_point $ep)
{
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $filename = (string) $params['filename'];

    // Partner
    $sql = rex_sql::factory();
    $sql->setQuery('SELECT partner_id, name FROM `' . rex::getTablePrefix() . 'd2u_partner` '
        .'WHERE picture = :filename', [':filename' => $filename]);

    // Partner Warnings
    for ($i = 0; $i < $sql->getRows(); ++$i) {
        $message = '<a href="javascript:openPage(\'index.php?page=d2u_partner/partner&func=edit&entry_id='.
            $sql->getValue('partner_id') .'\')">'. rex_i18n::msg('d2u_partner') .': '. $sql->getValue('name') .'</a>';
        $warning[] = $message;
        $sql->next();
    }

    return $warning;
}
