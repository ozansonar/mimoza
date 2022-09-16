<?php

use Mrt\MimozaCore\Mail;
use Mrt\MimozaCore\View;

$pageRoleKey = "contact";
$pageAddRoleKey = "contact-settings";

$id = 0;
$pageData = [];

$defaultLang = $siteManager->defaultLanguage();

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['CONTACT_DETAIL']);
	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("contact_form", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}

	if ((int)$data->read_user === 0) {
		$message_read = $db::query("UPDATE contact_form SET read_user=1,read_user_id=:u_id,read_date=:r_date WHERE id=:id AND deleted=0");
		$message_read->bindParam("u_id", $_SESSION["user_id"], PDO::PARAM_INT);
		$now_date = date("Y-m-d H:i:s");
		$message_read->bindParam("r_date", $now_date, PDO::PARAM_STR);
		$message_read->bindParam(":id", $id, PDO::PARAM_INT);
		$message_read->execute();
	}

	//data yukarda güncellendi tekrar çekelim
	$data = $db::selectQuery("contact_form", array(
		"id" => $id,
		"deleted" => 0,
	), true);
	$pageData[$defaultLang->short_lang] = (array)$data;
}

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css"
];
$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
];

$readUser = $session->getUserInfo($pageData[$defaultLang->short_lang]["read_user_id"]);

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, "send") === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$pageData[$defaultLang->short_lang]["reply_subject"] = $functions->cleanPost("reply_subject");
	$pageData[$defaultLang->short_lang]["reply_text"] = $functions->cleanPost("reply_text");

	if (!empty($pageData[$defaultLang->short_lang]["reply_send_user_id"])) {
		$message["reply"][] = "Bu mesajı daha önce cevapladınız.";
	} else {
		if (empty($pageData[$defaultLang->short_lang]["reply_subject"])) {
			$message["reply"][] = "Konu boş olamaz.";
		}

		if (!empty($pageData[$defaultLang->short_lang]["reply_subject"])) {
			if (strlen(strip_tags($pageData[$defaultLang->short_lang]["reply_subject"])) < 5) {
				$message["reply"][] = "Konu 5 karekterden az olamaz.";
			}
			if (strlen(strip_tags($pageData[$defaultLang->short_lang]["reply_subject"])) > 200) {
				$message["reply"][] = "Konu 200 karekterden fazla olamaz.";
			}
		}

		if (empty($pageData[$defaultLang->short_lang]["reply_text"])) {
			$message["reply"][] = "Cevap boş olamaz.";
		}

		if (!empty($pageData[$defaultLang->short_lang]["reply_text"]) && strlen(strip_tags($pageData[$defaultLang->short_lang]["reply_text"])) < 10) {
			$message["reply"][] = "Cevap 10 karekterden az olamaz.";
		}

		if (empty($message)) {

			$db_data = [];
			$db_data["reply_subject"] = $pageData[$defaultLang->short_lang]["reply_subject"];
			$db_data["reply_text"] = $pageData[$defaultLang->short_lang]["reply_text"];
			$db_data["reply_send_date"] = date("Y-m-d H:i:s");
			$db_data["reply_send_user_id"] = $session->get("user_id");

			$refresh_time = 5;
			$message["refresh_time"] = $refresh_time;

			$mailClass = new Mail($db);
			$mailClass->address = $data->email;
			$mailClass->subject = " İletişim mesjınıza cevap verildi -" . $pageData[$defaultLang->short_lang]["reply_subject"];
			$on_ek = "<p>Sayın <b>" . $data->name . " " . $data->surname . ",</b></p>";
			$on_ek .= "<p><b>" . $functions->dateLong($data->created_at) . "</b> tarihinde gönderdiğiniz iletişim mesajına yöneticimiz tarafından cevap verildi. Yönetici mesajı aşağıdadır.</p><hr>";
			$mailClass->message = nl2br($on_ek . $pageData[$defaultLang->short_lang]["reply_text"]);
			$mailClass->send();
			$update = $db::update("contact_form", $db_data, array("id" => $id));

			if ($update) {
				$log->logThis($log->logTypes['CONTACT_CEVAP_SEND_SUCC']);
				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl("contact-settings?id=" . $id), $refresh_time);
			} else {
				$log->logThis($log->logTypes['CONTACT_CEVAP_SEND_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		}
	}
}

View::backend('contact-settings', [
	'title' => "Gelen Mesajı " . (isset($data) ? "Cevapla" : "Ekle"),
	'pageButtonRedirectLink' => "contact",
	'pageButtonRedirectText' => "İletişim Mesajları",
	'pageButtonIcon' => "fas fa-th-list",
	'pageData' => $pageData,
	'content' => $data,
	'defaultLanguage' => $defaultLanguage,
	'css' => $customCss,
	'js' => $customJs,

]);