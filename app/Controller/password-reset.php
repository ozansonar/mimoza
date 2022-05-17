<?php

use Mrt\MimozaCore\View;

$log->logThis($log->logTypes["SIFRE_YENILE_PAGE"]);

if (empty($_GET["hash"])) {
    $functions->redirect($functions->site_url_lang());
}
$hash = $functions->clean_get("hash");
if (empty($hash)) {
    $functions->redirect($functions->site_url_lang());
}
if (strlen($hash) !== 60) {
    $functions->redirect($functions->site_url_lang());
}

$selectQuery = $db::selectQuery("forgot_password",array(
    "hash" => $hash,
    "deleted" => 0,
),true);
if (empty($selectQuery)) {
    $functions->redirect($functions->site_url_lang());
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";

//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-".$_SESSION["lang"].".js";

$metaTag->title = $functions->textManager("sifre_yenileme_baslik");

if(isset($_POST["save"]) && (int)$_POST["save"] === 1){
    $message = array();
    $sifre = $functions->clean_post("password");
    $sifre_tekrar = $functions->clean_post("password_again");
    $hash = $functions->clean_post("hash");

    if(!empty($selectQuery)){
        $created_at = date($selectQuery->created_at);
        $new_date = strtotime('1 day',strtotime($created_at));
        $date = date('Y-m-d H:i:s' ,$new_date);
        //$current_date = date("Y/m/d H:i:s");
        $current_date = strtotime(date("Y-m-d H:i:s"));
        $datestrto = strtotime($date);
        if($current_date > $datestrto){
            //geçeriz olduguna göre bu hashi silelim
            $hash_del = array();
            $hash_del["deleted"] = 1;
            $db::update("forgot_password",$hash_del,array("hash"=>$hash));
            $message["reply"][] = $functions->textManager("sifre_yenileme_linkin_kullanim_suresi_dolmus");
        }
    }else{
        $functions->redirect($functions->site_url_lang());
    }

    $sifre_control = $functions->passwordControl($sifre,"sifreniz");
    if(!empty($sifre_control)){
        foreach ($sifre_control as $s_cntr){
            $message["reply"][] = $s_cntr;
        }
    }
    $sifre_tekrar_control = $functions->passwordControl($sifre_tekrar,"sifre_tekrari");
    if(!empty($sifre_tekrar_control)){
        foreach ($sifre_tekrar_control as $st_cntr){
            $message["reply"][] = $st_cntr;
        }
    }

    if(!empty($sifre) && !empty($sifre_tekrar) && $sifre !== $sifre_tekrar) {
		$message["reply"][] = $functions->textManager("sifre_yenileme_sifre_ve_tekrari_ayni_olmalidir");
	}

    if(empty($message)){
        $ups = array();
        $ups["password"] = password_hash($sifre,PASSWORD_DEFAULT);
        $up = $db::update("users",$ups,array("id"=>$selectQuery->user_id));
        if($up){
            $log->logThis($log->logTypes["SIFRE_YENILE_SUCC"]);
            //kullanıcı şifresini güncelledi şimdi hash'i silelim
            //geçeriz olduguna göre bu hashi silelim
            $hash_del = array();
            $hash_del["deleted"] = 1;
            $db::update("forgot_password",$hash_del,array("hash"=>$hash));

            $message["success"][] = $functions->textManager("sifre_yenileme_basarili");
            $refresh_time = 5;
            $message["refresh_time"] = $refresh_time;
            $functions->refresh($functions->site_url_lang("giris"),$refresh_time);
        }else{
            $log->logThis($log->logTypes["SIFRE_YENILE_ERR"]);
            $message["reply"][] = $functions->textManager("sifre_yenileme_hatali");
        }
    }
}

include($system->path("includes/System/Form.php"));
$form = new Includes\System\Form();


View::layout('password-reset',[
    'customCss' => $customCss,
    'customJs' => $customJs,
]);