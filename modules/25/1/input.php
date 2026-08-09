<div class="row">
	<div class="col-xs-4">
		Titel
	</div>
	<div class="col-xs-8">
		<input  class="form-control" type="text" size="40" name="VALUE[1]" value="REX_VALUE[1]" />
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Welche Kategorie soll angezeigt werden?
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
        $categories = D2U_Partner\Category::getAll(true);
        $select = new rex_select();
        $select->setName('VALUE[2]');
        $select->setAttribute('class', 'form-control');
        $select->setSize(1);
        // Daten
        foreach ($categories as $category) {
            $select->addOption($category->name, $category->category_id);
        }
        // Vorselektierung
        $select->setSelected('REX_VALUE[2]');
        $select->show();
        ?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12"><p>Partner werden im <a href="index.php?page=d2u_partner">D2U Business Partner Addon</a> verwaltet.</p></div>
</div>