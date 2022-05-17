<?php

use Mrt\MimozaCore\Form;
use Mrt\MimozaCore\View;

if($session->isThereUserSession()){
    $functions->redirect($system->url());
}

//bu sayfadakullanılan özel css'ler
$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css"
];

//bu sayfadakullanılan özel js'ler
$customJs = [
	"dist/js/sweetalert2.all.min.js",
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-".$_SESSION["lang"].".js",
];

$form = new Form();
$pageData = [];
$metaTag->title = "Üye Girişi";
$log->logThis($log->logTypes["GIRIS"]);
if(isset($_POST["save"]) && $_POST["save"]){
    $pageData["email"] = $functions->cleanPost("email");
    $pageData["password"] = $functions->cleanPost("password");

    $message = $session->login($pageData["email"],$pageData["password"]);
    if(isset($message["success"])){
        //modal çıkmasın diye bunu temizleyelim
        unset($message["success"]);

        //login oldgunda indexdeki kontrolü geçtiği için burda da çalışıtalım ki login olduğunda hata vermesin
        $loggedUser = $session->getUserInfo();
        $log->logThis($log->logTypes["GIRIS_BASARILI"]);
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if(isset($_GET["url"]) && !empty($_GET["url"])){
            $redirect_link = $functions->cleaner($_GET["url"]);
            $functions->redirect($system->url($redirect_link));
        }else{
            $functions->redirect($system->url());
        }
    }
}

View::layout('login',[
    'form' => $form,
    'customCss' => $customCss,
    'customJs' => $customJs,
    'pageData' => $pageData,
]);