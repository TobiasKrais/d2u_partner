<?php

$imageType = '280x200';
$title = 'REX_VALUE[1]';
$categoryId = (int) 'REX_VALUE[2]';

$partners = [];
if ($categoryId > 0) {
    $category = new D2U_Partner\Category($categoryId);
    $partners = $category->getPartners(true);
} else {
    $partners = D2U_Partner\Partner::getAll(true);
}

if (count($partners) > 0) {
    if ('' !== $title) {
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<h2>'. rex_escape($title) .'</h2>';
        echo '</div>';
        echo '</div>';
    }

    echo '<div class="row row-cols-2 row-cols-sm-3 row-cols-lg-6 g-4 partnerlogos">';
    foreach ($partners as $partner) {
        $linkStart = '';
        $linkEnd = '';
        if ($partner->article_id > 0) {
            $linkStart = '<a href="'. rex_escape(rex_getUrl((int) $partner->article_id)) .'" target="_blank" rel="noopener">';
            $linkEnd = '</a>';
        } elseif (strlen($partner->url) > 7) {
            $linkStart = '<a href="'. rex_escape($partner->url) .'" target="_blank" rel="noopener">';
            $linkEnd = '</a>';
        }

        echo '<div class="col text-center">';
        echo $linkStart;
        echo '<img src="index.php?rex_media_type='. rex_escape($imageType) .'&rex_media_file='. rex_escape((string) $partner->picture) .'" class="img-fluid" alt="'. rex_escape($partner->name) .'">';
        echo $linkEnd;
        echo '</div>';
    }
    echo '</div>';
}
