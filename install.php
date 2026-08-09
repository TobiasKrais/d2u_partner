<?php

$sql = rex_sql::factory();
// Install database
\rex_sql_table::get(\rex::getTable('d2u_partner'))
    ->ensureColumn(new rex_sql_column('partner_id', 'INT(10) unsigned', false, null, 'auto_increment'))
    ->ensureColumn(new rex_sql_column('name', 'varchar(255)', true))
    ->ensureColumn(new rex_sql_column('picture', 'varchar(255)', true))
    ->ensureColumn(new rex_sql_column('url', 'varchar(255)', true))
    ->ensureColumn(new rex_sql_column('article_id', 'int(10)', true))
    ->ensureColumn(new rex_sql_column('category_ids', 'varchar(255)', true))
    ->ensureColumn(new rex_sql_column('online_status', 'varchar(10)', true, 'online'))
    ->setPrimaryKey('partner_id')
    ->ensure();

\rex_sql_table::get(\rex::getTable('d2u_partner_categories'))
    ->ensureColumn(new rex_sql_column('category_id', 'INT(10) unsigned', false, null, 'auto_increment'))
    ->ensureColumn(new rex_sql_column('name', 'varchar(255)', true))
    ->setPrimaryKey('category_id')
    ->ensure();

// Update modules
include __DIR__ . DIRECTORY_SEPARATOR .'lib'. DIRECTORY_SEPARATOR .'Module.php';
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(\TobiasKrais\D2UPartner\Module::getModules(), '', 'd2u_partner');
$d2u_module_manager->autoupdate();