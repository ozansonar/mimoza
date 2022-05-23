<?php
//sistem sign.up.php üzerinen çalışıyor buna gerek kalmadı eğer ayırmak isterseniz bunu kullanabilirsiniz.
$functions->redirect($system->url());
exit;

use Includes\System\Form;
use Mrt\MimozaCore\View;

if(!$session->isThereUserSession()){
    $functions->redirect($system->url());
}

$customCss = [];
$customCss[] = "plugins/fancybox/jquery.fancybox.min.css";
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";

$customJs = [];
$customJs[] = "plugins/fancybox/jquery.fancybox.min.js";
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-".$_SESSION["lang"].".js";


$metaTag->title = "Bilgilerim";

$pageData = [];
foreach ($loggedUser as $key_user=>$value_user){
    $pageData[$key_user] = $value_user;
}

if(isset($_POST["submit"]) && $_POST["submit"]){
    $pageData["email"] = $functions->cleanPost("email");
    $pageData["name"] = $functions->cleanPost("name");
    $pageData["surname"] = $functions->cleanPost("surname");
    $pageData["telefon"] = $functions->cleanPost("telefon");
    $pageData["password"] = $functions->cleanPost("password");
    $pageData["password_again"] = $functions->cleanPost("password_again");

    $message = [];
    if(empty($pageData["email"])){
        $message["reply"][] = "E-posta boş olamaz.";
    }
    if(!empty($pageData["email"])){
        if(!$functions->is_email($pageData["email"])){
            $message["reply"][] = "E-postan email formatında olmalıdır.";
        }
    }

    if($functions->is_email($pageData["email"])){
        if($siteManager->uniqDataWithoutThis("users","email",$pageData["email"],$pageData["id"]) > 0){
            $message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
        }
    }
    if(empty($pageData["name"])){
        $message["reply"][] = "İsim boş olamaz.";
    }
    if(!empty($pageData["name"])){
        if(strlen($pageData["name"]) < 2){
            $message["reply"][] = "İsim 2 karakterden az olamaz.";
        }
        if(strlen($pageData["name"]) > 20){
            $message["reply"][] = "İsim 20 karakteri geçemez.";
        }
    }
    if(empty($pageData["surname"])){
        $message["reply"][] = "Soyisim boş olamaz.";
    }
    if(!empty($pageData["surname"])){
        if(strlen($pageData["surname"]) < 2){
            $message["reply"][] = "Soyisim 2 karekterden az olamaz.";
        }
        if(strlen($pageData["surname"]) > 20){
            $message["reply"][] = "Soyisim 20 karekteri geçemez.";
        }
    }
    if(empty($pageData["telefon"])){
        $message["reply"][] = "Cep telefonu boş olamaz.";
    }
    if(!empty($pageData["telefon"])){
        if(!is_numeric($pageData["telefon"])){
            $message["reply"][] = "Cep telefonu sadece rakam olmaldır.";
        }
        if(strlen($pageData["telefon"]) != 11){
            $message["reply"][] = "Cep telefonu 11 karakter olmalıdır.";
        }
    }

    if(!empty($pageData["password"]) && !empty($pageData["password_again"])){
        $sifre_control = $functions->passwordControl($pageData["password"],"Şifreniz");
        if(!empty($sifre_control)){
            foreach ($sifre_control as $s_cntr){
                $message["reply"][] = $s_cntr;
            }
        }
        $sifre_tekrar_control = $functions->passwordControl($pageData["password_again"],"Şifre tekrarınız");
        if(!empty($sifre_tekrar_control)){
            foreach ($sifre_tekrar_control as $st_cntr){
                $message["reply"][] = $st_cntr;
            }
        }
        if(!empty($pageData["password"]) && !empty($pageData["password_again"])){
            if($pageData["password"] != $pageData["password_again"]){
                $message["reply"][] = "Şifre ve şifre tekrarı aynı olmalıdır.";
            }
        }
    }

    if(empty($message)) {
        //resim yükleme işlemi en son
        include_once($system->path("includes/System/FileUploader.php"));
        $file = new \Includes\System\FileUploader($constants::fileTypePath);
        $file->globalFileName = "img";
        $file->uploadFolder = "user_image";
        $file->maxFileSize = 5;
        $file->compressor = true;
        $uploaded = $file->fileUpload();
        if($uploaded["result"] == 1){
            $pageData["img"] = $uploaded["img_name"];
        }
        if($uploaded["result"] == 2){
            $message["reply"][] = $uploaded["result_message"];
        }
    }

    if(empty($message)){
        $dbData = [];
        $dbData["email"] = $pageData["email"];
        $dbData["name"] = $pageData["name"];
        $dbData["surname"] = $pageData["surname"];
        $dbData["telefon"] = $pageData["telefon"];
        if($pageData["img"]){
            $dbData["img"] = $pageData["img"];
        }
        if(isset($pageData["password"])){
            $dbData["password"] = password_hash($pageData["password"],PASSWORD_BCRYPT);
        }
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;

        //güncelleme
        $update = $db::update("users",$dbData,["id"=>$pageData["id"]]);
        if($update){
            //log atalı
            $log->logThis($log->logTypes['PROFILE_EDIT_SUCC']);
            $message["success"][] = $lang["content-update"];
            $functions->refresh($system->url($settings->{'profile_prefix_' . $_SESSION["lang"]}),$refresh_time);
        }else{
            //log atalım
            $log->logThis($log->logTypes['PROFILE_EDIT_ERR']);
            $message["reply"][] = $lang["content-update-error"];
        }
    }
}
//form dahil ediliyor
include($system->path("includes/System/Form.php"));
$pageForm = new Form();

View::layout('profile',[
    'form' => $pageForm,
    'pageData' => $pageData,
    'customCss' => $customCss,
    'customJs' => $customJs,
]);