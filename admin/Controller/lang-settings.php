<?php
//sayfanın izin keyi
use OS\MimozaCore\View;

$pageRoleKey = "lang";
$pageAddRoleKey = "lang-settings";

$id = 0;
$pageData = [];

$defaultLanguage = $siteManager->defaultLanguage();

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['LANG_DETAIL']);
	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("lang", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	$pageData = (array)$data;
} else {
	if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	$log->logThis($log->logTypes['LANG_ADD_PAGE']);
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
 
	$pageData["lang"] = $functions->cleanPost("lang");
	$pageData["short_lang"] = $functions->cleanPost("short_lang");
	$pageData["default_lang"] = $functions->cleanPostInt("default_lang");
	$pageData["form_validate"] = $functions->cleanPostInt("form_validate");
	$pageData["status"] = $functions->cleanPostInt("status");


	if (empty($pageData["lang"])) {
		$message["reply"][] = "Dil boş olamaz.";
	}

	if (!empty($pageData["name"])) {
		if (strlen($pageData["name"]) < 2) {
			$message["reply"][] = "Dil 2 karakterden az olamaz.";
		}
		if (strlen($pageData["name"]) > 20) {
			$message["success"][] = "Dil 20 karakterden fazla olamaz.";
		}
	}

	if (empty($pageData["short_lang"])) {
		$message["reply"][] = "Dil kısaltma boş olamaz.";
	}

	if (!empty($pageData["short_lang"])) {
		if (strlen($pageData["short_lang"]) < 2) {
			$message["reply"][] = "Dil kısaltma 2 karakterden az olamaz.";
		}
		if (strlen($pageData["short_lang"]) > 5) {
			$message["success"][] = "Dil kısaltma 5 karakterden fazla olamaz.";
		}
	}

	if (!array_key_exists($pageData["default_lang"], $constants::systemSiteModVersion2)) {
		$message["reply"][] = "Geçersiz varsayılan dil durumu.";
	}

	if (!array_key_exists($pageData["form_validate"], $constants::systemSiteModVersion2)) {
		$message["reply"][] = "Geçersiz form doğrulama durumu.";
	}

	if (!array_key_exists($pageData["status"], $constants::systemStatus)) {
		$message["reply"][] = "Geçersiz onay durumu.";
	}

	if ((int)$pageData["default_lang"] === 1 && (int)$pageData["form_validate"] !== 1) {
		$message["reply"][] = "Bu dili varsayılan dil olarak seçtiniz bu durumda <b>Form Doğrulama</b> seçeneğinide evet olarak seçmeniz gerekmektedir.";
	}

	if ((int)$pageData["default_lang"] !== 1 && $siteManager->getDefaultLangNotId($id) === false) {
		$message["reply"][] = "Varsayılan başka bir dil yok bu dili varsayılan dil yapmak zorundasınız. Bu dili varsayılandan çıkarmak istiyorsanız başka bir dili varsayılan yapmanız yeterli olacaktır.";
	}


	if (empty($message)) {
		$db_data = [];
		$db_data["lang"] = $pageData["lang"];
		$db_data["short_lang"] = $pageData["short_lang"];
		$db_data["default_lang"] = $pageData["default_lang"];
		$db_data["form_validate"] = $pageData["form_validate"];
		$db_data["status"] = $pageData["status"];
		$db_data["user_id"] = $session->get("user_id");

		$refresh_time = 3;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			$update = $db::update("lang", $db_data, array("id" => $id));
			if ($update) {
				$log->logThis($log->logTypes['LANG_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];
				if ((int)$pageData["default_lang"] === 1) {
					$siteManager->defaultLanguageReset($id);
				}
				$functions->refresh($system->adminUrl("lang-settings?id=" . $id), $refresh_time);
			} else {
				$log->logThis($log->logTypes['LANG_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else {
			$add = $db::insert("lang", $db_data);
			if ($add > 0) {
				$log->logThis($log->logTypes['LANG_ADD_SUCC']);
				$message["success"][] = $lang["content-insert"];

				if ((int)$pageData["default_lang"] === 1) {
					//işlem tamamlandı eğer varsayılan dil yapıldıysa diğer varsayılan dili kaldıralım
					$siteManager->defaultLanguageReset($add);
				}
				$functions->refresh($system->adminUrl("lang-settings"), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['LANG_ADD_ERR']);
				$message["reply"][] = $lang["content-insert-error"];
			}
		}
	}
}

View::backend('lang-settings', [
	'title' => "Dil " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "lang",
	'pageButtonRedirectText' => "Diller",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageData' => $pageData,
	'css' =>$customCss,
	'js' =>$customJs,
]);