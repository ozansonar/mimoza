<?php

use Mrt\MimozaCore\FileUploader;
use Mrt\MimozaCore\Functions;
use Mrt\MimozaCore\View;

$pageRoleKey = "account-settings";

if ($session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::listPermissionKey);
	$session->permissionDenied();
}

function getMessage(Functions $functions, string $password, array $message, string $password_again): array
{
	$password_control = $functions->passwordControl($password, "Şifreniz");
	if (!empty($password_control)) {
		foreach ($password_control as $s_cntr) {
			$message["reply"][] = $s_cntr;
		}
	}
	$password_again_control = $functions->passwordControl($password_again, "Şifre tekrarınız");
	if (!empty($password_again_control)) {
		foreach ($password_again_control as $st_cntr) {
			$message["reply"][] = $st_cntr;
		}
	}
	return $message;
}

$log->logThis($log->logTypes['ACCOUNT_DETAIL']);
$default_lang = $siteManager->defaultLanguage();

$pageData = [];
$pageData[$default_lang->short_lang] = (array)$loggedUser;

//datanın içinde şifre olmasın
unset($pageData[$default_lang->short_lang]["password"]);

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
	"plugins/bs-custom-file-input/bs-custom-file-input.min.js",
];


if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	$pageData[$default_lang->short_lang]["email"] = $functions->cleanPost("email");
	$pageData[$default_lang->short_lang]["name"] = $functions->cleanPost("name");
	$pageData[$default_lang->short_lang]["surname"] = $functions->cleanPost("surname");
	$pageData[$default_lang->short_lang]["theme"] = $functions->cleanPost("theme");

	//formda gözükmemesi için bunları arrayda tutmuyorum
	$password = $functions->cleanPost("password");
	$password_again = $functions->cleanPost("password_again");

	$message = [];
	if (empty($pageData[$default_lang->short_lang]["email"])) {
		$message["reply"][] = "E-posta boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["email"])) {
		if (!$functions->isEmail($pageData[$default_lang->short_lang]["email"])) {
			$message["reply"][] = "E-postanız email formatında olmalıdır.";
		}
		if ($siteManager->uniqDataWithoutThis("users", "email", $pageData[$default_lang->short_lang]["email"], $_SESSION["user_id"]) > 0) {
			$message["reply"][] = "Bu mail adresi kayıtlarımızda mevcut. Lütfen başka bir tane deneyin.";
		}
	}
	if (empty($pageData[$default_lang->short_lang]["name"])) {
		$message["reply"][] = "İsim boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["name"])) {
		if (strlen($pageData[$default_lang->short_lang]["name"]) < 2) {
			$message["reply"][] = "İsim 2 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["name"]) > 20) {
			$message["reply"][] = "İsim 20 karakteri geçemez.";
		}
	}
	if (empty($pageData[$default_lang->short_lang]["surname"])) {
		$message["reply"][] = "Soyisim boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["surname"])) {
		if (strlen($pageData[$default_lang->short_lang]["surname"]) < 2) {
			$message["reply"][] = "Soyisim 2 karekterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["surname"]) > 20) {
			$message["reply"][] = "Soyisim 20 karekteri geçemez.";
		}
	}

	if (!empty($password) && !empty($password_again)) {
		$message = getMessage($functions, $password, $message, $password_again);
		if ($password !== $password_again) {
			$message["reply"][] = "Şifre ve şifre tekrarı aynı olmalıdır.";
		}
	}
	if (!array_key_exists($pageData[$default_lang->short_lang]["theme"], $constants::adminPanelTheme)) {
		$message["reply"][] = "Geçersiz tema seçimi.";
	}

	if (empty($message)) {
		//resim yükleme işlemi en son
		$file = new FileUploader($constants::fileTypePath);
		$file->globalFileName = "img";
		$file->uploadFolder = "user_image";
		$file->maxFileSize = 5;
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
		$dat["email"] = $pageData[$default_lang->short_lang]["email"];
		$dat["name"] = $pageData[$default_lang->short_lang]["name"];
		$dat["surname"] = $pageData[$default_lang->short_lang]["surname"];
		$dat["theme"] = $pageData[$default_lang->short_lang]["theme"];
		if (isset($pageData[$default_lang->short_lang]["img"])) {
			$dat["img"] = $pageData[$default_lang->short_lang]["img"];
		}
		if (!empty($password) && !empty($password_again)) {
			$new_password = password_hash($password, PASSWORD_DEFAULT);
			$dat["password"] = $new_password;
		}
		$update = $db::update("users", $dat, array("id" => $_SESSION["user_id"]));
		if ($update) {
			//log atalım
			$log->logThis($log->logTypes['ACCOUNT_EDIT_SUCC']);

			$refresh_time = 5;
			$message["refresh_time"] = $refresh_time;
			$message["success"][] = "Hesap ayarları başarılı bir şekilde güncellendi.";
			$functions->refresh($system->adminUrl("account-settings"), $refresh_time);
		} else {
			//log atalım
			$log->logThis($log->logTypes['ACCOUNT_EDIT_ERR']);

			$message["reply"][] = "Hesap ayarları güncellenemedi tekrar deneyin.";
		}
	}
}

View::backend('account-settings',[
	'title' => 'Hesap Ayarlarım',
	'pageButtonRedirectLink' => "account-settings",
	'pageButtonRedirectText' => 'Hesap Ayarlarım',
	'pageButtonIcon' => null,
	'pageRoleKey' =>$pageRoleKey,
	'pageData' =>$pageData,
	'defaultLanguage' =>$default_lang,
	'css' =>$customCss,
	'js' =>$customJs,
]);