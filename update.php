<?php

$this->includeFile(__DIR__.'/install.php'); /** @phpstan-ignore-line */

// Update database to 1.0.1
$sql = rex_sql::factory();
$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() .'d2u_partner` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() .'d2u_partner_categories` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
