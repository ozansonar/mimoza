<?php
use \Includes\System\AdminForm;

if ($session->isThereUserSession()) {
	if ($session->get("user_rank") < 60) {
		$functions->redirect($functions->site_url());
	}
}

//log atalım
$log->logThis($log->logTypes['ADMIN_LOGIN_PAGE']);

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";

//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";

$pageData = [];
//form class
include($functions->root_url("includes/System/AdminForm.php"));

$page_form = new Includes\System\AdminForm();
if ($functions->post('submit')) {
    $pageData["name"] = $functions->clean_post("name");
    $pageData["password"] = $functions->clean_post("password");
	$message = $session->login($pageData["name"], $pageData["password"]);
	if (isset($message["success"])) {
		//log atalım
		$log->logThis($log->logTypes['ADMIN_LOGIN'], "user id: " . $_SESSION["user_id"]);
		$functions->redirect($adminSystem->adminUrl());
	}
}
require $adminSystem->adminView('login');