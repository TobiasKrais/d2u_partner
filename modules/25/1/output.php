<?php

$im_type = '280x200';
$titel = 'REX_VALUE[1]';
$category_id = (int) 'REX_VALUE[2]';

$partners = [];
if ($category_id > 0) {
    $category = new D2U_Partner\Category($category_id);
    $partners = $category->getPartners(true);
} else {
    $partners = D2U_Partner\Partner::getAll(true);
}

if (count($partners) > 0) {
    if ('' !== $titel) {
        echo '<div class="row">';
        echo '<div class="large-12 columns">';
        echo '<h1>'. rex_escape($titel) .'</h1>';
        echo '</div>';
        echo '</div>';
    }

    echo '<div class="row partnerlogos">';
    foreach ($partners as $partner) {
        echo '<div class="small-4 medium-2 columns end">';
        if ($partner->article_id > 0) {
            echo '<a href="'. rex_escape(rex_getUrl((int) $partner->article_id)) .'" target="_blank" rel="noopener noreferrer">';
        } elseif (strlen($partner->url) > 7) {
            echo '<a href="'. rex_escape($partner->url) .'" target="_blank" rel="noopener noreferrer">';
        }
        echo '<img src="index.php?rex_media_type='. rex_escape($im_type) .'&rex_media_file='. rex_escape((string) $partner->picture) .'" alt="'. rex_escape($partner->name) .'">';
        if ($partner->article_id > 0 || strlen($partner->url) > 7) {
            echo '</a>';
        }
        echo '</div>';
    }
    echo '</div>';
}
