<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan
 * Date: 29.07.2021
 * Time: 13:54
 */
$message = array();
if(isset($_POST["mailing_id"])){
    $id = $functions->clean_post_int("mailing_id");

    include_once $system->path("includes/System/Mail.php");
    $mail_class = new \Includes\System\Mail($db);
    $send = $mail_class->maililing_send($id);
    $message = $send;
}else{
    $message["reply"][] = "Birşeyler yanış gitti tekrar deneyin.";
}
