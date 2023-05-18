<?php

use Includes\System\Form;
use OS\MimozaCore\FileUploader;
use OS\MimozaCore\Mail;
use OS\MimozaCore\View;

$log->this('KAYIT_PAGE');

$customCss = [];
$customCss[] = "plugins/fancybox/jquery.fancybox.min.css";
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";

$customJs = [];
$customJs[] = "plugins/fancybox/jquery.fancybox.min.js";
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-".$_SESSION["lang"].".js";

$pageData = [];

$breadcrumb = [
    [
        'title' => $session->isThereUserSession() ? $functions->textManager('breadcrumb_profil'):$functions->textManager('breadcrumb_uyeol'),
        'active' => true,
    ]
];

if ($session->isThereUserSession()){
    $log->this('USER_INFO');
    $userInfo = $loggedUser;
    $pageData = (array)$loggedUser;
}

if(isset($_POST["submit"]) && (int)$_POST["submit"] === 1){
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
        if(!$functions->isEmail($pageData["email"])){
            $message["reply"][] = "E-postan email formatında olmalıdır.";
        }
    }

    if($functions->isEmail($pageData["email"]) && isset($userInfo)){
        if($siteManager->uniqDataWithoutThis("users","email",$pageData["email"],$pageData["id"]) > 0){
            $message["reply"][] = "Bu e-posta adresi kayıtlarımızda mevcut lütfen başka bir tane deyin";
        }
    }else{
        if($siteManager->uniqData("users","email",$pageData["email"]) > 0){
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

    if(isset($userInfo) && !empty($pageData["password"]) && !empty($pageData["password_again"])){
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
    }elseif(!isset($userInfo)){
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
        $file = new FileUploader($constants::fileTypePath);
        $file->globalFileName = "img";
        $file->uploadFolder = "user_image";
        $file->maxFileSize = 1;
        $file->compressor = true;
        $uploaded = $file->fileUpload();
        if ((int)$uploaded["result"] === 1) {
            $pageData["img"] = $uploaded["img_name"];
        }
        if ((int)$uploaded["result"] === 2) {
            $message["reply"][] = $uploaded["result_message"];
        }
    }

    if(empty($message)){
        $dbData = [];
        $dbData["email"] = $pageData["email"];
        $dbData["name"] = $pageData["name"];
        $dbData["surname"] = $pageData["surname"];
        $dbData["telefon"] = $pageData["telefon"];
        if(isset($pageData["img"])){
            $dbData["img"] = $pageData["img"];
        }
        if(isset($pageData["password"])){
            $dbData["password"] = password_hash($pageData["password"],PASSWORD_BCRYPT);
        }
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;

        if(isset($userInfo)){
            //güncelleme
            $update = $db::update("users",$dbData,["id"=>$pageData["id"]]);
            if($update){
                //güncel verileri çekelim
                $loggedUser = $session->getUserInfo();
                //log atalı
                $log->this('PROFILE_EDIT_SUCC');
                $message["success"][] = 'Bilgileriniz başarıyla güncellendi.';
                $functions->refresh($system->url($siteManager->getPrefix('profile')),$refresh_time);
            }else{
                //log atalım
                $log->this('PROFILE_EDIT_ERR');
                $message["reply"][] = 'Bilgileriniz güncellenemedi. Lütfen tekrar deneyiniz.';
            }
        }else{
            //sadece ekleme yapılırken yazılsın
            $dbData["email_verify"] = 0;
            $verifyCode = password_hash($pageData["email"].$pageData["name"].$pageData["surname"],PASSWORD_DEFAULT);
            $dbData["verify_code"] = $verifyCode;
            $dbData["send_mail"] = 1;
            //ekleme
            $db_query = $db::insert("users",$dbData);
            if($db_query > 0){
                $log->this('USER_INSERT_SUCC'," add user id:".$db_query);

                //kullanıcıya heabını doğrulaması için mail atalım
                $mailTemplate = $siteManager->getMailTemplate(16);
                if(!empty($mailTemplate)){
                    $mailTemplateText = $mailTemplate->text;
                    $mailTemplateText = str_replace("#name#",$pageData["name"],$mailTemplateText);
                    $mailTemplateText = str_replace("#surname#",$pageData["surname"],$mailTemplateText);
                    $mailTemplateText = str_replace("#project_name#",$settings->project_name,$mailTemplateText);
                    $mailTemplateText = str_replace("#link#",$system->url($siteManager->getPrefix('hesap_dogrulama')."?hash=".$verifyCode),$mailTemplateText);

                    $mail = new Mail($db);
                    $mail->message = $mailTemplateText;
                    $mail->address = $pageData['email'];
                    $mail->sender_name = $pageData['name']." ".$pageData['surname'];
                    $mail->subject = $mailTemplate->subject;
                    $mail->send();
                    $userVerifyMail = true;
                }

                $getMailSendAdminList = $siteManager->getMailSendAdminList();
                $mailTemplate = $siteManager->getMailTemplate(19);
                if(!empty($getMailSendAdminList) && !empty($mailTemplate)){
                    $mailTemplateText = $mailTemplate->text;
                    $mailTemplateText = str_replace("#name#",$pageData["name"],$mailTemplateText);
                    $mailTemplateText = str_replace("#surname#",$pageData["surname"],$mailTemplateText);
                    foreach ($getMailSendAdminList as $adminRow){
                        $mail = new Mail($db);
                        $mail->message = $mailTemplateText;
                        $mail->address = $adminRow->email;
                        $mail->subject = $mailTemplate->subject;
                        $mail->send();
                    }
                }

                if(isset($userVerifyMail) && $userVerifyMail){
                    $message["success"][] = "Kaydınız başarıyla yapıldı. Size gönderdiğiniz e-postadan hesabınızı doğrulamanız gerekmektedir.";
                }else{
                    $message["success"][] = "Kadınız başarıyla yapıldı.";
                }
                //veriler sıfırlansın
                $pageData = [];
                $functions->refresh($system->url());
            }else{
                $log->this('USER_INSERT_ERR');
                $message["reply"][] = 'kaydınız oluşturulamadı. Lütfen tekrar deneyiniz.';
            }
        }
    }
}


View::layout('sign-up',[
    'pageData' => $pageData,
    'customCss' => $customCss,
    'customJs' => $customJs,
    'breadcrumb' => $breadcrumb,
]);