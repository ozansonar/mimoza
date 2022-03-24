<?php

use Includes\System\View;

if($session->isThereUserSession()){
    $functions->redirect($functions->site_url_lang());
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";

//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "dist/js/sweetalert2.all.min.js";
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-".$_SESSION["lang"].".js";

include($functions->root_url("includes/System/Form.php"));
$form = new Includes\System\Form();
$pageData = [];
$metaTag->title = "Üye Girişi";
$log->logThis($log->logTypes["GIRIS"]);
if(isset($_POST["save"]) && $_POST["save"]){
    $pageData["email"] = $functions->clean_post("email");
    $pageData["password"] = $functions->clean_post("password");

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
            $functions->redirect($functions->site_url_lang($redirect_link));
        }else{
            $functions->redirect($functions->site_url_lang());
        }
    }
}

View::layout('login',[
    'form' => $form,
    'customCss' => $customCss,
    'customJs' => $customJs,
    'pageData' => $pageData,
]);