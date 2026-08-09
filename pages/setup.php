<?php
/*
 * Modules
 */
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(\TobiasKrais\D2UPartner\Module::getModules(), 'modules/', 'd2u_partner');

// \TobiasKrais\D2UHelper\ModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if ('' !== $d2u_module_id) {
    if (!\TobiasKrais\D2UHelper\BackendHelper::getPageCsrfToken()->isValid()) {
        echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
    } else {
        $d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
    }
}

// \TobiasKrais\D2UHelper\ModuleManager show list
$d2u_module_manager->showManagerList();

/*
 * Templates
 */
?>
<h2>Beispielseiten</h2>
<ul>
	<li>D2U Business Partner Addon: <a href="https://www.kaltenbach.com/de/" target="_blank">
		www.kaltenbach.com</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte über das Kontaktformular unter
	<a href="https://www.design-to-use.de" target="_blank">www.design-to-use.de</a> melden.</p>