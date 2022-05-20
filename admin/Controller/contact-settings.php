<?php
//sayfanın izin keyi
$page_role_key = "contact";
$page_add_role_key = "contact-settings";

$id = 0;
$pageData = array();

$default_lang = $siteManager->defaultLanguage();
if(isset($_GET["id"])){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($page_role_key,$constants::editPermissionKey) == false || $session->sessionRoleControl($page_role_key,$constants::listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$constants::editPermissionKey);
        $session->permissionDenied();
    }
    //log atalım
    $log->logThis($log->logTypes['CONTACT_DETAIL']);

    $id = $functions->cleanGetInt("id");
    $data = $db::selectQuery("contact_form",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($system->adminUrl());
    }
    if($data->read_user == 0){
        $message_read = $db::query("UPDATE contact_form SET read_user=1,read_user_id=:u_id,read_date=:r_date WHERE id=:id AND deleted=0");
        $message_read->bindParam("u_id",$_SESSION["user_id"],PDO::PARAM_INT);
        $now_date = date("Y-m-d H:i:s");
        $message_read->bindParam("r_date",$now_date,PDO::PARAM_STR);
        $message_read->bindParam(":id",$id,PDO::PARAM_INT);
        $message_read->execute();
    }
    //data yukarda güncellendi tekrar çekelim
    $data = $db::selectQuery("contact_form",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    $pageData[$default_lang->short_lang] = (array)$data;
}

$readUser = $session->getUserInfo($pageData[$default_lang->short_lang]["read_user_id"]);

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";
//bu sayfadda kullanılan özel js'ler
$customJs = array();
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
if(isset($_POST["submit"]) && $_POST["submit"] == 1){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($page_role_key,"send") == false || $session->sessionRoleControl($page_role_key,$constants::editPermissionKey) == false || $session->sessionRoleControl($page_role_key,$constants::listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$constants::editPermissionKey);
        $session->permissionDenied();
    }

    $pageData[$default_lang->short_lang]["reply_subject"] = $functions->cleanPost("reply_subject");
    $pageData[$default_lang->short_lang]["reply_text"] = $functions->cleanPost("reply_text");

    if(!empty($pageData[$default_lang->short_lang]["reply_send_user_id"])){
        $message["reply"][] = "Bu mesajı daha önce cevapladınız.";
    }else{
        if(empty($pageData[$default_lang->short_lang]["reply_subject"])){
            $message["reply"][] = "Konu boş olamaz.";
        }
        if(!empty($pageData[$default_lang->short_lang]["reply_subject"])){
            if(strlen(strip_tags($pageData[$default_lang->short_lang]["reply_subject"])) < 5){
                $message["reply"][] = "Konu 5 karekterden az olamaz.";
            }
            if(strlen(strip_tags($pageData[$default_lang->short_lang]["reply_subject"])) > 200){
                $message["reply"][] = "Konu 200 karekterden fazla olamaz.";
            }
        }
        if(empty($pageData[$default_lang->short_lang]["reply_text"])){
            $message["reply"][] = "Cevap boş olamaz.";
        }
        if(!empty($pageData[$default_lang->short_lang]["reply_text"])){
            if(strlen(strip_tags($pageData[$default_lang->short_lang]["reply_text"])) < 10){
                $message["reply"][] = "Cevap 10 karekterden az olamaz.";
            }
        }
        if (empty($message)) {

            $db_data = array();
            $db_data["reply_subject"] = $pageData[$default_lang->short_lang]["reply_subject"];
            $db_data["reply_text"] = $pageData[$default_lang->short_lang]["reply_text"];
            $db_data["reply_send_date"] = date("Y-m-d H:i:s");
            $db_data["reply_send_user_id"] = $session->get("user_id");

            $refresh_time = 5;
            $message["refresh_time"] = $refresh_time;
            //kişiye cevabı gönder
            include_once $system->path("includes/System/Mail.php");

            $mail_class = new \Includes\System\Mail($db);

            $mail_class->adress = $data->email;
            $mail_class->subject = " İletişim mesjınıza cevap verildi -".$pageData[$default_lang->short_lang]["reply_subject"];
            $on_ek = "<p>Sayın <b>".$data->name." ".$data->surname.",</b></p>";
            $on_ek .= "<p><b>".$functions->date_long($data->created_at)."</b> tarihinde gönderdiğiniz iletişim mesajına yöneticimiz tarafından cevap verildi. Yönetici mesajı aşağıdadır.</p><hr>";
            $mail_class->message = nl2br($on_ek.$pageData[$default_lang->short_lang]["reply_text"]);

            $mail_class->mail_send();
            //güncelleme
            $update = $db::update("contact_form", $db_data, array("id"=>$id));
            if ($update) {
                //log atalım
                $log->logThis($log->logTypes['CONTACT_CEVAP_SEND_SUCC']);

                $message["success"][] = $lang["content-update"];
                $functions->refresh($system->adminUrl("contact-settings?id=".$id), $refresh_time);
            } else {
                //log atalım
                $log->logThis($log->logTypes['CONTACT_CEVAP_SEND_ERR']);

                $message["reply"][] = $lang["content-update-error"];
            }
        }
    }
}

include($system->path("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Gelen Mesajı ".(isset($data) ? "Cevapla":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "contact";
$page_button_redirect_text = "İletişim Mesajları";
$page_button_icon = "icon-list";
require $system->adminView('contact-settings');