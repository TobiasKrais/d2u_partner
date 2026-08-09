<?php

use TobiasKrais\D2UHelper\BackendHelper;

$func = rex_request('func', 'string');
$entry_id = rex_request('entry_id', 'int');
$message = rex_get('message', 'string');

$csrfToken = BackendHelper::getPageCsrfToken();
$invalidCsrf = false;
if ((
	1 === (int) filter_input(INPUT_POST, 'btn_save')
	|| 1 === (int) filter_input(INPUT_POST, 'btn_apply')
	|| 1 === (int) filter_input(INPUT_POST, 'btn_delete', FILTER_VALIDATE_INT)
	|| in_array($func, ['delete', 'changestatus'], true)
) && !$csrfToken->isValid()) {
	echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
	$invalidCsrf = true;
	if (in_array($func, ['delete', 'changestatus'], true)) {
		$func = '';
	}
}

if ('' !== $message) {
	echo rex_view::success(rex_i18n::msg($message));
}

if (1 === (int) filter_input(INPUT_POST, 'btn_abort', FILTER_VALIDATE_INT)) {
	header('Location: '. BackendHelper::getCurrentBackendPage([], ['entry_id', 'func', 'message', 'message_type']));
	exit;
}

if (!$invalidCsrf && (1 === (int) filter_input(INPUT_POST, 'btn_save') || 1 === (int) filter_input(INPUT_POST, 'btn_apply'))) {
	$form = rex_post('form', 'array', []);
	$input_media = rex_post('REX_INPUT_MEDIA', 'array', []);
	$link_ids = filter_input_array(INPUT_POST, ['REX_INPUT_LINK' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_REQUIRE_ARRAY]]);

	$partner = new D2U_Partner\Partner((int) $form['partner_id']);
	$partner->name = (string) $form['name'];
	$partner->picture = (string) ($input_media[1] ?? '');
	$partner->url = (string) $form['url'];
	$partner->article_id = is_array($link_ids['REX_INPUT_LINK'] ?? null) ? (int) $link_ids['REX_INPUT_LINK'][1] : 0;
	$partner->online_status = array_key_exists('online_status', $form) ? 'online' : 'offline';
	$partner->categories = [];
	foreach ($form['category_ids'] ?? [] as $category_id) {
		$partner->categories[(int) $category_id] = new D2U_Partner\Category((int) $category_id);
	}

	$message = $partner->save() ? 'form_saved' : 'form_save_error';

	if (1 === (int) filter_input(INPUT_POST, 'btn_apply', FILTER_VALIDATE_INT) && $partner->partner_id > 0) {
		header('Location: '. rex_url::currentBackendPage(['entry_id' => $partner->partner_id, 'func' => 'edit', 'message' => $message], false));
	} else {
		header('Location: '. rex_url::currentBackendPage(['message' => $message], false));
	}
	exit;
}

if ((!$invalidCsrf && 1 === (int) filter_input(INPUT_POST, 'btn_delete', FILTER_VALIDATE_INT)) || 'delete' === $func) {
	$partner_id = $entry_id;
	if (0 === $partner_id) {
		$form = rex_post('form', 'array', []);
		$partner_id = (int) $form['partner_id'];
	}

	$partner = new D2U_Partner\Partner($partner_id);
	$partner->delete();
	$func = '';
} elseif ('changestatus' === $func) {
	$partner = new D2U_Partner\Partner($entry_id);
	$partner->changeStatus();

	header('Location: '. rex_url::currentBackendPage());
	exit;
}

if ('edit' === $func || 'add' === $func) {
?>
	<form action="<?= BackendHelper::getCurrentBackendPage([], ['message', 'message_type']) ?>" method="post">
		<?= $csrfToken->getHiddenField() ?>
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_partner_partner') ?></div></header>
			<div class="panel-body">
				<input type="hidden" name="form[partner_id]" value="<?= $entry_id ?>">
				<fieldset>
					<legend><?= rex_i18n::msg('d2u_helper_data_all_lang') ?></legend>
					<div class="panel-body-wrapper slide">
						<?php
							$partner = new D2U_Partner\Partner($entry_id);
							$readonly = true;
							if (rex::getUser() instanceof rex_user && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_partner[edit_data]'))) {
								$readonly = false;
							}

							BackendHelper::form_input('d2u_helper_name', 'form[name]', $partner->name, true, $readonly);
							BackendHelper::form_mediafield('d2u_helper_picture', '1', $partner->picture, $readonly);
							BackendHelper::form_input('d2u_partner_url', 'form[url]', $partner->url, false, $readonly);
							BackendHelper::form_linkfield('d2u_helper_article_id', '1', (int) $partner->article_id, (int) rex_config::get('d2u_helper', 'default_lang'), $readonly);

							$options_categories = [];
							foreach (D2U_Partner\Category::getAll(false) as $category) {
								$options_categories[$category->category_id] = $category->name;
							}
							BackendHelper::form_select('d2u_helper_categories', 'form[category_ids][]', $options_categories, array_keys($partner->categories), 5, true, $readonly);
							BackendHelper::form_checkbox('d2u_helper_online_status', 'form[online_status]', 'online', 'online' === $partner->online_status, $readonly);
						?>
					</div>
				</fieldset>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="1"><?= rex_i18n::msg('form_save') ?></button>
						<button class="btn btn-apply" type="submit" name="btn_apply" value="1"><?= rex_i18n::msg('form_apply') ?></button>
						<button class="btn btn-abort" type="submit" name="btn_abort" formnovalidate="formnovalidate" value="1"><?= rex_i18n::msg('form_abort') ?></button>
						<?php
							if (rex::getUser() instanceof rex_user && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_partner[edit_data]'))) {
								echo '<button class="btn btn-delete" type="submit" name="btn_delete" formnovalidate="formnovalidate" data-confirm="'. rex_i18n::msg('form_delete') .'?" value="1">'. rex_i18n::msg('form_delete') .'</button>';
							}
						?>
					</div>
				</div>
			</footer>
		</div>
	</form>
	<br>
	<?php
		echo BackendHelper::getCSS();
		echo BackendHelper::getJS();
}

if ('' === $func) {
	$query = 'SELECT partner_id, name, url, category_ids, online_status '
		. 'FROM '. rex::getTablePrefix() .'d2u_partner ';
	$list = rex_list::factory(query: $query, rowsPerPage: 1000, defaultSort: ['name' => 'ASC']);

	$list->addTableAttribute('class', 'table-striped table-hover');

	$tdIcon = '<i class="rex-icon fa-handshake-o"></i>';
	$thIcon = '';
	if (rex::getUser() instanceof rex_user && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_partner[edit_data]'))) {
		$thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-module"></i></a>';
	}
	$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
	$list->setColumnParams($thIcon, ['func' => 'edit', 'entry_id' => '###partner_id###']);

	$list->setColumnLabel('partner_id', rex_i18n::msg('id'));
	$list->setColumnLayout('partner_id', ['<th class="rex-table-id">###VALUE###</th>', '<td class="rex-table-id">###VALUE###</td>']);
	$list->setColumnSortable('partner_id');

	$list->setColumnLabel('name', rex_i18n::msg('d2u_helper_name'));
	$list->setColumnParams('name', ['func' => 'edit', 'entry_id' => '###partner_id###']);
	$list->setColumnSortable('name');

	$list->setColumnLabel('url', rex_i18n::msg('d2u_partner_url'));
	$list->setColumnSortable('url');

	$list->setColumnLabel('category_ids', rex_i18n::msg('d2u_helper_categories'));
	$list->setColumnFormat('category_ids', 'custom', static function ($params) {
		$list_params = $params['list'];
		$category_names = [];
		$category_ids = preg_grep('/^\s*$/s', explode('|', (string) $list_params->getValue('category_ids')), PREG_GREP_INVERT);
		if (is_array($category_ids)) {
			foreach ($category_ids as $category_id) {
				$category = new D2U_Partner\Category((int) $category_id);
				if ($category->category_id > 0) {
					$category_names[] = $category->name;
				}
			}
		}
		return implode(', ', $category_names);
	});

	$list->addColumn(rex_i18n::msg('module_functions'), '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('edit'));
	$list->setColumnLayout(rex_i18n::msg('module_functions'), ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
	$list->setColumnParams(rex_i18n::msg('module_functions'), ['func' => 'edit', 'entry_id' => '###partner_id###']);

	$list->removeColumn('online_status');
	if (rex::getUser() instanceof rex_user && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_partner[edit_data]'))) {
		$list->addColumn(rex_i18n::msg('status_online'), '<a class="rex-###online_status###" href="' . BackendHelper::getCurrentBackendPage(['func' => 'changestatus'], [], true) . '&entry_id=###partner_id###"><i class="rex-icon rex-icon-###online_status###"></i> ###online_status###</a>');
		$list->setColumnLayout(rex_i18n::msg('status_online'), ['', '<td class="rex-table-action">###VALUE###</td>']);

		$list->addColumn(rex_i18n::msg('delete_module'), '<i class="rex-icon rex-icon-delete"></i> ' . rex_i18n::msg('delete'));
		$list->setColumnLayout(rex_i18n::msg('delete_module'), ['', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams(rex_i18n::msg('delete_module'), ['func' => 'delete', 'entry_id' => '###partner_id###'] + $csrfToken->getUrlParams());
		$list->addLinkAttribute(rex_i18n::msg('delete_module'), 'data-confirm', rex_i18n::msg('d2u_helper_confirm_delete'));
	}

	$list->setNoRowsMessage(rex_i18n::msg('d2u_partner_no_partners_found'));

	$fragment = new rex_fragment();
	$fragment->setVar('title', rex_i18n::msg('d2u_partner_partner'), false);
	$fragment->setVar('content', $list->get(), false);
	echo $fragment->parse('core/page/section.php');
}
