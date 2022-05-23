<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: ozansonar
 * Date: 28.10.2019
 * Time: 11:40
 */

$log->logThis($log->logTypes["SIFREMI_UNUTTUM_PAGE"]);
if (isset($_POST) && isset($_POST["ajax_request"]) && $_POST["ajax_request"] == 99){
    $message = [];
    $email = $functions->cleanPost("forgot_email");
    $result = $db::selectQuery("users",array(
        "email" => $email,
        "deleted" => 0,
    ),true);

    if (empty($email)) {
        $message["reply"][] = $functions->textManager("giris_sifremi_unuttum_bos_email");
    }
    if (!empty($email)) {
        if (!$functions->is_email($email)) {
            $message["reply"][] = $functions->textManager("giris_sifremi_unuttum_gecersiz_email");
        }
        if (empty($result->email)) {
            $message["reply"][] = $functions->textManager("giris_sifremi_unuttum_kayitsiz_email");
        }
    }

    if (empty($message)) {
        $datas = $result->id.$result->rank.$result->email;
        $hash = password_hash($datas, PASSWORD_DEFAULT);
        $link = $system->url($settings->{"sifre_yenile_prefix_".$_SESSION["lang"]}."?hash=".$hash);

        $mail_template = $siteManager->getMailTemplate(4);
        if(empty($mail_template)){
            exit("mail teması hatasi");
        }
        $m_temp = $mail_template->text;
        $m_temp = str_replace("#link#",$link,$m_temp);

        $data = [];
        $data["user_id"] = $result->id;
        $data["type"] = $result->rank;
        $data["email"] = $result->email;
        $data["hash"] = $hash;
        $query = $db::insert("forgot_password",$data);
        if ($query) {
            $log->logThis($log->logTypes["SIFREMI_UNUTTUM_SUCC"]);

            include_once $system->path("includes/System/Mail.php");

            $mail_class = new \Includes\System\Mail($db);
            $mail_class->adress = $result->email;
            $mail_class->subject = " | ".$mail_template->subject;
            $mail_class->message = $m_temp;
            $mail_send = $mail_class->mail_send();
            if ($mail_send) {
                $message["success"][] = $functions->textManager("giris_sifremi_unuttum_mail_gonderildi");
                $message["url"][] = $system->url();
                $message["timer"][] = 2000;
            } else {
                $message["reply"][] = $functions->textManager("giris_sifremi_unuttum_mail_gonderilemedi");
            }
        } else {
            $log->logThis($log->logTypes["SIFREMI_UNUTTUM_ERR"]);
            $message["reply"][] = $functions->textManager("giris_sifremi_unuttum_mail_gonderilemedi");
        }
    }
}