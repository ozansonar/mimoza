<?php

use Includes\System\AdminForm;
use Includes\System\FileUploader;

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
$pageData = array();


$default_lang = $siteManager->defaultLanguage();
if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $editPermissionKey);
		$session->permissionDenied();
	}

	//log atalım
	$log->logThis($log->logTypes['USER_DETAIL']);

	$id = $functions->clean_get_int("id");
	$data = $db::selectQuery("users", array(
		"id" => $id,
		"deleted" => 0,
	), true);
	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	$pageData[$default_lang->short_lang] = (array)$data;
	unset($pageData[$default_lang->short_lang]["password"]);//datanın içinde şifre olmasın
} else if ($session->sessionRoleControl($pageAddRoleKey, $addPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $editPermissionKey);
	$session->permissionDenied();
}

/**
 * @param \Includes\System\Functions $functions
 * @param string $password
 * @param array $message
 * @param string $password_again
 * @return array
 */
function getMessage(\Includes\System\Functions $functions, string $password, array $message, string $password_again): array
{
    $password_control = $functions->passwordControl($password, "Şifre");
    if (!empty($password_control)) {
        foreach ($password_control as $s_cntr) {
            $message["reply"][] = $s_cntr;
        }
    }
    $password_again_control = $functions->passwordControl($password_again, "Şifre tekrarı");
    if (!empty($password_again_control)) {
        foreach ($password_again_control as $st_cntr) {
            $message["reply"][] = $st_cntr;
        }
    }
    return $message;
}

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $editPermissionKey);
		$session->permissionDenied();
	}

	$pageData[$default_lang->short_lang]["email"] = $functions->clean_post("email");
	$pageData[$default_lang->short_lang]["name"] = $functions->clean_post("name");
	$pageData[$default_lang->short_lang]["surname"] = $functions->clean_post("surname");
	$pageData[$default_lang->short_lang]["telefon"] = $functions->clean_post("telefon");
	$pageData[$default_lang->short_lang]["status"] = $functions->clean_post("status");
	$pageData[$default_lang->short_lang]["password"] = $functions->clean_post("password");
	$pageData[$default_lang->short_lang]["password_again"] = $functions->clean_post("password_again");

	$message = array();
	if (empty($pageData[$default_lang->short_lang]["email"])) {
		$message["reply"][] = "E-posta boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["email"]) && !$functions->is_email($pageData[$default_lang->short_lang]["email"])) {
		$message["reply"][] = "E-postanız email formatında olmalıdır.";
	}
	if ($id === 0) {
		//ekleme kısmı
		if ($functions->is_email($pageData[$default_lang->short_lang]["email"]) && $siteManager->uniqData("users", "email", $pageData[$default_lang->short_lang]["email"]) > 0) {
			$message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
		}
	} else if ($functions->is_email($pageData[$default_lang->short_lang]["email"]) && $siteManager->uniqDataWithoutThis("users", "email", $pageData[$default_lang->short_lang]["email"], $id) > 0) {
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
    if($pageData[$default_lang->short_lang]["status"]){
        if(!in_array($pageData[$default_lang->short_lang]["status"],array_keys($systemStatus))){
            $message["reply"][] = "Geçersiz onay durumu.";
        }
    }

    if(!empty($pageData[$default_lang->short_lang]["password"]) && !empty($pageData[$default_lang->short_lang]["password_again"])){
        $message = getMessage($functions, $pageData[$default_lang->short_lang]["password"], $message, $pageData[$default_lang->short_lang]["password_again"]);
        if($pageData[$default_lang->short_lang]["password"] !== $pageData[$default_lang->short_lang]["password_again"]){
            $message["reply"][] = "Şifre ve şifre tekrarı aynı olmalıdır.";
        }
    }


	if (empty($message)) {
		//resim yükleme işlemi en son
		$file = new FileUploader($fileTypePath);
		$file->global_file_name = "img";
		$file->upload_folder = "user_image";
		$file->max_file_size = 5;
		$file->resize = true;
		$file->width = 280;
		$file->height = 500;
		$file->compressor = true;
		$uploaded = $file->file_upload();
		if ((int)$uploaded["result"] === 1) {
			$pageData[$default_lang->short_lang]["img"] = $uploaded["img_name"];
		}
		if ((int)$uploaded["result"] === 2) {
			$message["reply"][] = $uploaded["result_message"];
		}
	}
	if (empty($message)) {
		$dat = array();

		$dat["status"] = 1;
		$dat["email"] = $pageData[$default_lang->short_lang]["email"];
		$dat["name"] = $pageData[$default_lang->short_lang]["name"];
		$dat["telefon"] = $pageData[$default_lang->short_lang]["telefon"];
		$dat["status"] = $pageData[$default_lang->short_lang]["status"];
		$dat["surname"] = $pageData[$default_lang->short_lang]["surname"];
        if(isset($pageData[$default_lang->short_lang]["password"]) && !empty($pageData[$default_lang->short_lang]["password"])){
            $dat["password"] = password_hash($pageData[$default_lang->short_lang]["password"],PASSWORD_BCRYPT);
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
				//log atalım
				$log->logThis($log->logTypes['USER_EDIT_SUCC']);

				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl("user-settings?id=" . $id), $refresh_time);
			} else {
				//log atalım
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


$form = new AdminForm();

//sayfa başlıkları
$page_title = "Kullanıcı " . (isset($data) ? "Düzenle" : "Ekle");
$sub_title = null;

//butonun gideceği link ve yazısı
$page_button_redirect_link = "user";
$page_button_redirect_text = "Kullanıcılar";
$page_button_icon = "icon-list";
require $system->adminView('user-settings');

