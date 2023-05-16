<?php

use OS\MimozaCore\View;

$pageRoleKey = "mailler";
$pageAddRoleKey = "mail-settings";
$pageTable = "mailing";
$default_lang = $siteManager->defaultLanguage();
$id = 0;
$pageData = [];
$pageData[$default_lang->short_lang]["image"] = "";

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['EMAIL_TEMALARI_DETAIL']);
	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery($pageTable, array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}

	if (!empty($data->image)) {
		$mailing_image_unserialize = unserialize($data->image);
		foreach ($mailing_image_unserialize as $m_key => $m_value) {
			$data->text = str_replace("cid:image_" . $m_key, $constants::fileTypePath["mailing"]["url"] . $m_value, $data->text);
		}
		$data->image = implode(",", $mailing_image_unserialize);
	}

	if (!empty($data->attachment)) {
		$attachment_array = [];
		$attecament = unserialize($data->attachment);
		foreach ($attecament as $attecament_row) {
			$at_name = $functions->cleaner($attecament_row);
			if (file_exists($constants::fileTypePath["mailing_attachment"]["full_path"] . $at_name)) {
				$attachment_array[] = $at_name;
			}
		}
	}

	$pageData = (array)$data;
} else {
	if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	$log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_PAGE_DETAIL']);
}

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css",
	"plugins/icheck-bootstrap/icheck-bootstrap.min.css",
	"plugins/select2/css/select2.min.css",
	"plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
	"plugins/toastr/toastr.min.css",
];
$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
	"plugins/select2/js/select2.full.min.js",
	"plugins/ckeditor/ckeditor.js",
	"plugins/toastr/toastr.min.js",
];


View::backend($pageAddRoleKey,[
	'title' =>"Mailing " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => $pageRoleKey,
	'pageButtonRedirectText' => "Gönderilmiş Mailler",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' =>$pageRoleKey,
	'pageAddRoleKey' =>$pageAddRoleKey,
	'pageData' =>$pageData,
	'css' =>$customCss,
	'js' =>$customJs,
]);