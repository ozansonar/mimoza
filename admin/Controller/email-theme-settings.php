<?php
//sayfanın izin keyi
use Mrt\MimozaCore\View;

$pageRoleKey = "email-themes";
$pageAddRoleKey = "email-theme-settings";

//sayfada işlem yapılacak table
$table = "email_template";

$id = 0;
$pageData = [];
if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['EMAIL_TEMALARI_DETAIL']);

	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery($table, array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}

	//id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
	$data_multi_lang = $db::selectQuery($table, array(
		"lang_id" => $data->lang_id,
		"deleted" => 0,
	));

	foreach ($data_multi_lang as $data_row) {
		$pageData[$data_row->lang] = (array)$data_row;
		$db_data_lang[$data_row->lang] = $data_row->lang;
	}
} else {
	//add yetki kontrolü
	if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	//log atalım
	$log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_PAGE_DETAIL']);
}

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css",
	"plugins/icheck-bootstrap/icheck-bootstrap.min.css",
	"plugins/select2/css/select2.min.css",
	"plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
	"plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css",
	"plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
];
$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
	"plugins/select2/js/select2.full.min.js",
	"plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js",
	"plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
	"plugins/ckeditor/ckeditor.js",
];


if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	foreach ($projectLanguages as $project_languages_row) {
		// formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek
		$functions->formLang = $project_languages_row->short_lang;
		$pageData[$project_languages_row->short_lang]["subject"] = $functions->cleanPost("subject");
		$pageData[$project_languages_row->short_lang]["text"] = $functions->cleanPostTextarea("text");
		$pageData[$project_languages_row->short_lang]["not_text"] = $functions->cleanPost("not_text");
		$pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");

		//istenilen kontroller
		if ((int)$project_languages_row->form_validate === 1) {
			if (empty($pageData[$project_languages_row->short_lang]["subject"])) {
				$message["reply"][] = $project_languages_row->lang . " - Konu boş olamaz.";
			}

			if (!empty($pageData[$project_languages_row->short_lang]["subject"])) {
				if (strlen($pageData[$project_languages_row->short_lang]["subject"]) < 2) {
					$message["reply"][] = $project_languages_row->lang . " - Konu 2 karakterden az olamaz.";
				}
				if (strlen($pageData[$project_languages_row->short_lang]["subject"]) > 200) {
					$message["reply"][] = $project_languages_row->lang . " - Konu 200 karakterden fazla olamaz.";
				}
			}

			if (empty($pageData[$project_languages_row->short_lang]["text"])) {
				$message["reply"][] = $project_languages_row->lang . " - E-posta içeriği boş olamaz.";
			}


			if (!array_key_exists($pageData[$project_languages_row->short_lang]["status"], $constants::systemStatus)) {
				$message["reply"][] = $project_languages_row->lang . " - Geçersiz onay durumu.";
			}
		}
	}
	if (empty($message)) {
		$lang_id = date("YmdHis");
		foreach ($projectLanguages as $project_languages_row) {
			$db_data = [];
			$db_data["subject"] = $pageData[$project_languages_row->short_lang]["subject"];
			$db_data["text"] = $pageData[$project_languages_row->short_lang]["text"];
			$db_data["not_text"] = $pageData[$project_languages_row->short_lang]["not_text"];
			$db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
			$db_data["user_id"] = $session->get("user_id");
			if ($id > 0) {
				if (array_key_exists($project_languages_row->short_lang, $db_data_lang)) {
					$update = $db::update($table, $db_data, array("id" => $pageData[$project_languages_row->short_lang]["id"]));
				} else {
					$db_data["lang"] = $project_languages_row->short_lang;
					$db_data["lang_id"] = $data->lang_id;
					$add = $db::insert($table, $db_data);
				}
			} else {
				$db_data["lang"] = $project_languages_row->short_lang;
				$db_data["lang_id"] = $lang_id;
				$add = $db::insert($table, $db_data);
			}
		}
		$refresh_time = 3;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			if ($update) {
				$log->logThis($log->logTypes['EMAIL_TEMALARI_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl($pageAddRoleKey . "?id=" . $id), $refresh_time);
			} else {
				$log->logThis($log->logTypes['EMAIL_TEMALARI_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else if ($add) {
			$log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_SUCC']);
			$message["success"][] = $lang["content-insert"];
			$functions->refresh($system->adminUrl($pageAddRoleKey), $refresh_time);
		} else {
			$log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_ERR']);
			$message["reply"][] = $lang["content-insert-error"];
		}
	}
}

View::backend('email-theme-settings', [
	'title' => "E-posta Teması " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => $pageRoleKey,
	'pageButtonRedirectText' => "E-posta Temaları",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageData' => $pageData,
	'id' => $id,
	'css' =>$customCss,
	'js' =>$customJs,
]);