<?php

use OS\MimozaCore\View;

$pageRoleKey = "mailler";
$pageAddRoleKey = "mail-settings";
$table = "mailing";
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
	$data = $db::selectQuery($table, array(
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

	$pageData[$default_lang->short_lang] = (array)$data;
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

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	foreach ($projectLanguages as $project_languages_row) {
		// formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek
		$functions->formLang = $project_languages_row->short_lang;
		$pageData[$project_languages_row->short_lang]["subject"] = $functions->cleanPost("subject");
		$pageData[$project_languages_row->short_lang]["text"] = $functions->cleanPostTextarea("text");
		$pageData[$project_languages_row->short_lang]["not_text"] = $functions->cleanPost("not_text");
		$pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");

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
					$update = $db::update($table, $db_data, ["id" => $pageData[$project_languages_row->short_lang]["id"]]);
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

View::backend('mail-settings',[
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