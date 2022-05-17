<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: ozansonar
 * Date: 7.11.2019
 * Time: 09:49
 */

$log->logThis($log->logTypes["CONTACT_API_SEND"]);
if(isset($_POST)){
    $log->logThis(8);
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Type: text/json; charset=utf-8');
    //$functions->pre_arr($_POST);
    $name = $functions->cleanPost("name");
    $surname = $functions->cleanPost("surname");
    $email = $functions->cleanPost("email");
    $subject = $functions->cleanPost("subject");
    $msg = $functions->cleanPost("message");
    $phone = $functions->cleanPost("phone");
    $message = array();
    if(!$name){
        $message["reply"][] = $functions->textManager("contact_validate_ad");
    }
    if(!empty($name)){
        if(strlen($name) < 2){
            $message["reply"][] = $functions->textManager("contact_validate_min_2_ad");
        }
        if(strlen($name) > 60){
            $message["reply"][] = $functions->textManager("contact_validate_max_60_ad");
        }
        if(!$functions->safString_bosluklu($name)){
            $message["reply"][] = $functions->textManager("contact_validate_sadece_harf_ad");
        }
    }
    if(!$surname){
        $message["reply"][] = $functions->textManager("contact_validate_soyad");
    }
    if(!empty($surname)){
        if(strlen($surname) < 2){
            $message["reply"][] = $functions->textManager("contact_validate_min_2_soyad");
        }
        if(strlen($surname) > 60){
            $message["reply"][] = $functions->textManager("contact_validate_max_60_soyad");
        }
        if(!$functions->safString_bosluklu($surname)){
            $message["reply"][] = $functions->textManager("contact_validate_sadece_harf_soyad");
        }
    }
    /*
    if(!$phone){
        $message["reply"][] = "Telefon numarası boş olamaz.";
    }
    */
    if(!$email){
        $message["reply"][] = $functions->textManager("contact_validate_email");
    }
    if(!empty($email) && !$functions->is_email($email)) {
        $message["reply"][] = $functions->textManager("contact_validate_gecersiz_email");
    }

    if(!$msg){
        $message["reply"][] = $functions->textManager("contact_validate_mesaj");
    }
    if(!empty($msg)){
        if(strlen($msg) < 10){
            $message["reply"][] = $functions->textManager("contact_validate_min_10_mesaj");
        }
        if(strlen($msg) > 2000){
            $message["reply"][] = $functions->textManager("contact_validate_max_2000_mesaj");
        }
    }


    /*
    if(!empty($phone)){
        if(!$functions->is_int_2("phone")){
            $message["reply"][] = "Telefon numarası sadece sadece sayı olabilir.";
            $message["title"][] = "HATA";
        }
        if(strlen($phone) != 11){
            $message["reply"][] = "Telefon numarası 11 karekter olabilir.";
            $message["title"][] = "HATA";
        }
    }
    */

    //hata yoksa doğrulama yap
    if(empty($message) && defined("LIVE_MODE")) {
        $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
        $recaptcha_secret = CAPTCHA_SECRET_KEY;
        $recaptcha_response = $_POST['recaptcha_response'];

        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        if ($recaptcha->score < 0.5) {
            $message["reply"][] = $functions->textManager("contact_validate_bot_onay");
        }
    }

    if(empty($message)){
        $mail_template = $siteManager->getMailTemplate(1);
        if(empty($mail_template)){
            exit("mail teması hatasi");
        }
        $s_msg = "";
        $s_msg .="<p><b>Ad Soyad:</b> ".$name." ".$surname."</p>";
        $s_msg .="<p><b>E-posta:</b> ".$email."</p>";
        if(!empty($subject)){
            $s_msg .="<p><b>Konu:</b> ".$subject."</p>";
        }
        $s_msg .="<p><b>Mesaj:</b> ".$msg."</p>";

        $mail_temp = $mail_template->text;
        $mail_temp = str_replace(array("#project_name#", "#message#"), array($settings->project_name, $s_msg), $mail_temp);

        $db_data = array();
        $db_data["name"] = $name;
        $db_data["surname"] = $surname;
        $db_data["subject"] = $subject;
        $db_data["email"] = $email;
        $db_data["phone"] = $phone;
        $db_data["message"] = $msg;
        $db::insert("contact_form",$db_data);

        include_once $system->path("includes/System/Mail.php");

        $mail_class = new \Includes\System\Mail($db);
        $mail_class->message = $mail_temp;
        $mail_class->adress = $settings->site_mail;
        $mail_class->sender_name = $name." ".$surname;
        $mail_class->subject = " - ".$mail_template->subject;

        $send = $mail_class->mail_send();
        if($send){
            $message["success"][] = $functions->textManager("contact_form_success");
            $log->logThis($log->logTypes["CONTACT_API_SEND_SUCCESS"]);
        }else{
            $message["reply"][] = $functions->textManager("contact_form_error");
            $log->logThis($log->logTypes["CONTACT_API_SEND_ERR"]);
        }
    }
}