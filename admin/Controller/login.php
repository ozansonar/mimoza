<?php

use Mrt\MimozaCore\AdminForm;
use Mrt\MimozaCore\View;

if ($session->isThereUserSession() && $session->get("user_rank") < 60) {
	$functions->redirect($system->url());
}

//log atalım
$log->logThis($log->logTypes['ADMIN_LOGIN_PAGE']);

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css"
];

$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
];

$pageData = [];
$page_form = new AdminForm();
if ($functions->post('submit')) {
	$pageData["name"] = $functions->cleanPost("name");
	$pageData["password"] = $functions->cleanPost("password");
	$message = $session->login($pageData["name"], $pageData["password"]);
	if (isset($message["success"])) {
		//log atalım
		$log->logThis($log->logTypes['ADMIN_LOGIN'], "user id: " . $_SESSION["user_id"]);
		$functions->redirect($system->adminUrl());
	}
}

View::backend('login',[
	'title' => 'Giriş Yap',
	'pageData' => $pageData,
	'css' =>$customCss,
	'js' =>$customJs,
],'guest');