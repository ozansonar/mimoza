<?php

use Mrt\MimozaCore\FileUploader;
use Mrt\MimozaCore\View;

$pageRoleKey = "user";
$pageAddRoleKey = "user-settings";

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css",
	"plugins/icheck-bootstrap/icheck-bootstrap.min.css",
	"plugins/select2/css/select2.min.css",
	"plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
];
$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
	"plugins/select2/js/select2.full.min.js",
	"plugins/ckeditor/ckeditor.js",
	"plugins/bs-custom-file-input/bs-custom-file-input.min.js",
];

$id = 0;
$pageData = [];

$default_lang = $siteManager->defaultLanguage();

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	//log atalım
	$log->logThis($log->logTypes['USER_DETAIL']);

	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("users", array(
		"id" => $id,
		"deleted" => 0,
	), true);
	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	$pageData[$default_lang->short_lang] = (array)$data;
	unset($pageData[$default_lang->short_lang]["password"]);
} else if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
	$session->permissionDenied();
}


if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$pageData[$default_lang->short_lang]["email"] = $functions->cleanPost("email");
	$pageData[$default_lang->short_lang]["name"] = $functions->cleanPost("name");
	$pageData[$default_lang->short_lang]["surname"] = $functions->cleanPost("surname");
	$pageData[$default_lang->short_lang]["telefon"] = $functions->cleanPost("telefon");
	$pageData[$default_lang->short_lang]["status"] = $functions->cleanPost("status");
	$pageData[$default_lang->short_lang]["password"] = $functions->cleanPost("password");
	$pageData[$default_lang->short_lang]["password_again"] = $functions->cleanPost("password_again");

	$message = [];
	if (empty($pageData[$default_lang->short_lang]["email"])) {
		$message["reply"][] = "E-posta boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["email"]) && !$functions->isEmail($pageData[$default_lang->short_lang]["email"])) {
		$message["reply"][] = "E-postanız email formatında olmalıdır.";
	}
	if ($id === 0) {
		//ekleme kısmı
		if ($functions->isEmail($pageData[$default_lang->short_lang]["email"]) && $siteManager->uniqData("users", "email", $pageData[$default_lang->short_lang]["email"]) > 0) {
			$message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
		}
	} else if ($functions->isEmail($pageData[$default_lang->short_lang]["email"]) && $siteManager->uniqDataWithoutThis("users", "email", $pageData[$default_lang->short_lang]["email"], $id) > 0) {
		$message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
	}

	if (empty($pageData[$default_lang->short_lang]["name"])) {
		$message["reply"][] = "İsim boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["name"])) {
		if (strlen($pageData[$default_lang->short_lang]["name"]) < 2) {
			$message["reply"][] = "İsim 2 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["name"]) > 50) {
			$message["reply"][] = "İsim 50 karakteri geçemez.";
		}
	}
	if (empty($pageData[$default_lang->short_lang]["surname"])) {
		$message["reply"][] = "Soyisim boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["surname"])) {
		if (strlen($pageData[$default_lang->short_lang]["surname"]) < 2) {
			$message["reply"][] = "Soyisim 2 karekterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["surname"]) > 50) {
			$message["reply"][] = "Soyisim 50 karekteri geçemez.";
		}
	}
	if ($pageData[$default_lang->short_lang]["status"]
		&& !array_key_exists($pageData[$default_lang->short_lang]["status"], $constants::systemStatus)) {
		$message["reply"][] = "Geçersiz onay durumu.";
	}

	if (!empty($pageData[$default_lang->short_lang]["password"]) && !empty($pageData[$default_lang->short_lang]["password_again"])) {
		$message = getMessage($functions, $pageData[$default_lang->short_lang]["password"], $message, $pageData[$default_lang->short_lang]["password_again"]);
		if ($pageData[$default_lang->short_lang]["password"] !== $pageData[$default_lang->short_lang]["password_again"]) {
			$message["reply"][] = "Şifre ve şifre tekrarı aynı olmalıdır.";
		}
	}


	if (empty($message)) {
		//resim yükleme işlemi en son
		$file = new FileUploader($constants::fileTypePath);
		$file->globalFileName = "img";
		$file->uploadFolder = "user_image";
		$file->maxFileSize = 5;
		$file->resize = true;
		$file->width = 280;
		$file->height = 500;
		$file->compressor = true;
		$uploaded = $file->fileUpload();
		if ((int)$uploaded["result"] === 1) {
			$pageData[$default_lang->short_lang]["img"] = $uploaded["img_name"];
		}
		if ((int)$uploaded["result"] === 2) {
			$message["reply"][] = $uploaded["result_message"];
		}
	}
	if (empty($message)) {
		$dat = [];

		$dat["status"] = 1;
		$dat["email"] = $pageData[$default_lang->short_lang]["email"];
		$dat["name"] = $pageData[$default_lang->short_lang]["name"];
		$dat["telefon"] = $pageData[$default_lang->short_lang]["telefon"];
		$dat["status"] = $pageData[$default_lang->short_lang]["status"];
		$dat["surname"] = $pageData[$default_lang->short_lang]["surname"];
		if (isset($pageData[$default_lang->short_lang]["password"]) && !empty($pageData[$default_lang->short_lang]["password"])) {
			$dat["password"] = password_hash($pageData[$default_lang->short_lang]["password"], PASSWORD_BCRYPT);
		}
		if (isset($pageData[$default_lang->short_lang]["img"])) {
			$dat["img"] = $pageData[$default_lang->short_lang]["img"];
		}
		$refresh_time = 3;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			//güncelleme
			$update = $db::update("users", $dat, array("id" => $id));
			if ($update) {
				$log->logThis($log->logTypes['USER_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl("user-settings?id=" . $id), $refresh_time);
			} else {
				$log->logThis($log->logTypes['USER_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else {
			//ilk eklenirken şifreyi ata ve ekrana yazdır
			$dat["email_verify"] = 1;
			//ekleme
			$add = $db::insert("users", $dat);
			if ($add) {
				//log atalım
				$log->logThis($log->logTypes['USER_ADD_SUCC']);
				$message["success"][] = $lang["content-insert"];
			} else {
				//log atalım
				$log->logThis($log->logTypes['USER_ADD_ERR']);
				$message["reply"][] = $lang["content-insert-error"];
			}
		}
	}
}

View::backend('user-settings', [
	'id' => $id,
	'title' => "Kullanıcı " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "user",
	'pageButtonRedirectText' => "Kullanıcılar",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'defaultLanguage' => $default_lang,
	'pageData' => $pageData,
	'css' =>$customCss,
	'js' =>$customJs,
]);

