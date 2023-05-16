<?php

use OS\MimozaCore\FileUploader;
use OS\MimozaCore\View;

$pageRoleKey = "user";
$pageAddRoleKey = "user-settings";
$pageTable = 'users';
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
	$data = $db::selectQuery($pageTable, array(
		"id" => $id,
		"deleted" => 0,
	), true);
	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	$pageData = (array)$data;
	unset($pageData["password"]);
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

	$pageData["email"] = $functions->cleanPost("email");
	$pageData["name"] = $functions->cleanPost("name");
	$pageData["surname"] = $functions->cleanPost("surname");
	$pageData["telefon"] = $functions->cleanPost("telefon");
	$pageData["status"] = $functions->cleanPost("status");
	$pageData["password"] = $functions->cleanPost("password");
	$pageData["password_again"] = $functions->cleanPost("password_again");

	$message = [];
	if (empty($pageData["email"])) {
		$message["reply"][] = "E-posta boş olamaz.";
	}
	if (!empty($pageData["email"]) && !$functions->isEmail($pageData["email"])) {
		$message["reply"][] = "E-postanız email formatında olmalıdır.";
	}
	if ($id === 0) {
		//ekleme kısmı
		if ($functions->isEmail($pageData["email"]) && $siteManager->uniqData("users", "email", $pageData["email"]) > 0) {
			$message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
		}
	} else if ($functions->isEmail($pageData["email"]) && $siteManager->uniqDataWithoutThis("users", "email", $pageData["email"], $id) > 0) {
		$message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
	}

	if (empty($pageData["name"])) {
		$message["reply"][] = "İsim boş olamaz.";
	}
	if (!empty($pageData["name"])) {
		if (strlen($pageData["name"]) < 2) {
			$message["reply"][] = "İsim 2 karakterden az olamaz.";
		}
		if (strlen($pageData["name"]) > 50) {
			$message["reply"][] = "İsim 50 karakteri geçemez.";
		}
	}
	if (empty($pageData["surname"])) {
		$message["reply"][] = "Soyisim boş olamaz.";
	}
	if (!empty($pageData["surname"])) {
		if (strlen($pageData["surname"]) < 2) {
			$message["reply"][] = "Soyisim 2 karekterden az olamaz.";
		}
		if (strlen($pageData["surname"]) > 50) {
			$message["reply"][] = "Soyisim 50 karekteri geçemez.";
		}
	}
	if ($pageData["status"]
		&& !array_key_exists($pageData["status"], $constants::systemStatus)) {
		$message["reply"][] = "Geçersiz onay durumu.";
	}

	if (!empty($pageData["password"]) && !empty($pageData["password_again"])) {
		$message = getMessage($functions, $pageData["password"], $message, $pageData["password_again"]);
		if ($pageData["password"] !== $pageData["password_again"]) {
			$message["reply"][] = "Şifre ve şifre tekrarı aynı olmalıdır.";
		}
	}


	if (empty($message)) {
		//resim yükleme işlemi en son
		$file = new FileUploader($constants::fileTypePath);
		$file->globalFileName = "img";
		$file->uploadFolder = "user_image";
		$file->maxFileSize = 1;
		$file->resize = true;
		$file->width = 280;
		$file->height = 500;
		$file->compressor = true;
		$uploaded = $file->fileUpload();
		if ((int)$uploaded["result"] === 1) {
			$pageData["img"] = $uploaded["img_name"];
		}
		if ((int)$uploaded["result"] === 2) {
			$message["reply"][] = $uploaded["result_message"];
		}
	}
	if (empty($message)) {
		$dat = [];

		$dat["status"] = 1;
		$dat["email"] = $pageData["email"];
		$dat["name"] = $pageData["name"];
		$dat["telefon"] = $pageData["telefon"];
		$dat["status"] = $pageData["status"];
		$dat["surname"] = $pageData["surname"];
		if (isset($pageData["password"]) && !empty($pageData["password"])) {
			$dat["password"] = password_hash($pageData["password"], PASSWORD_BCRYPT);
		}
		if (isset($pageData["img"])) {
			$dat["img"] = $pageData["img"];
		}
		$refresh_time = 3;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			//güncelleme
			$update = $db::update($pageTable, $dat, array("id" => $id));
			if ($update) {
				$log->logThis($log->logTypes['USER_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl($pageAddRoleKey."?id=" . $id), $refresh_time);
			} else {
				$log->logThis($log->logTypes['USER_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else {
			//ilk eklenirken şifreyi ata ve ekrana yazdır
			$dat["email_verify"] = 1;
			//ekleme
			$add = $db::insert($pageTable, $dat);
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

View::backend($pageAddRoleKey, [
	'id' => $id,
	'title' => "Kullanıcı " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => $pageRoleKey,
	'pageButtonRedirectText' => "Kullanıcılar",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'defaultLanguage' => $default_lang,
	'pageData' => $pageData,
	'css' =>$customCss,
	'js' =>$customJs,
]);

