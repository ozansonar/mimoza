<?php

use OS\MimozaCore\Mail;
use OS\MimozaCore\View;

$pageRoleKey = "comment";
$pageAddRoleKey = "comment-settings";
$pageTable = 'comment';

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

	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery($pageTable, array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}

    $log->this('CONTACT_DETAIL');

	$pageData = (array)$data;
}


$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css",
    "plugins/select2/css/select2.min.css",
    "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
];
$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
    "plugins/select2/js/select2.full.min.js",
];


if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {
	$pageData["name"] = $functions->cleanPost("name");
	$pageData["surname"] = $functions->cleanPost("surname");
	$pageData["email"] = $functions->cleanPost("email");
	$pageData["comment"] = $functions->cleanPost("comment");
	$pageData["status"] = $functions->cleanPost("status");

    if(empty($pageData['name'])){
        $message['reply'][] = $functions->textManager('comment_ad_bos');
    }
    if(!empty($pageData['name'])){
        if(strlen($pageData['name']) < 2){
            $message['reply'][] = $functions->textManager('comment_ad_min');
        }
        if(strlen($pageData['name']) > 30){
            $message['reply'][] = $functions->textManager('comment_ad_max');
        }
    }
    if(empty($pageData['surname'])){
        $message['reply'][] = $functions->textManager('comment_soyad_bos');
    }
    if(!empty($pageData['surname'])){
        if(strlen($pageData['surname']) < 2){
            $message['reply'][] = $functions->textManager('comment_soyad_min');
        }
        if(strlen($pageData['surname']) > 30){
            $message['reply'][] = $functions->textManager('comment_soyad_max');
        }
    }
    if(empty($pageData['email'])){
        $message['reply'][] = $functions->textManager('comment_email_bos');
    }
    if(!empty($pageData['email'])){
        if($functions->isEmail($pageData['email']) === false){
            $message['reply'][] = $functions->textManager('comment_email_gecersiz');
        }
        if(strlen($pageData['email']) > 100){
            $message['reply'][] = $functions->textManager('comment_email_max');
        }

    }
    if(empty($pageData['comment'])){
        $message['reply'][] = $functions->textManager('comment_yorum_bos');
    }
    if(!empty($pageData['comment'])){
        if(strlen($pageData['comment']) < 10){
            $message['reply'][] = $functions->textManager('comment_yorum_min');
        }
        if(strlen($pageData['comment']) > 2000){
            $message['reply'][] = $functions->textManager('comment_yorum_max');
        }
    }


    if(empty($message)){
        $dbData = [];
        $dbData["name"] = $pageData['name'];
        $dbData["surname"] = $pageData['surname'];
        $dbData["email"] = $pageData['email'];
        $dbData["comment"] = $pageData['comment'];
        $dbData["status"] = $pageData['status'];
        $update = $db::update("comment",$dbData,['id'=>$id]);

        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($update){
            $message["success"][] = $lang["content-update"];
            $log->this('COMMENT_EDIT_SUCC');
            $functions->refresh($system->adminUrl($pageAddRoleKey."?id=" . $id), $refresh_time);
        }else{
            $message["reply"][] = $lang["content-update-error"];
            $log->this('COMMENT_EDIT_ERR');
        }
    }
}

View::backend($pageAddRoleKey, [
	'title' => "Yorum Düzenle",
	'pageButtonRedirectLink' => $pageRoleKey,
	'pageButtonRedirectText' => "Yorumlar",
	'pageButtonIcon' => "fas fa-th-list",
	'pageData' => $pageData,
	'css' => $customCss,
	'js' => $customJs
]);