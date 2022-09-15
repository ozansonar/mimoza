<?php

use Mrt\MimozaCore\View;
if(!$session->checkUserSession()){
    $functions->redirect($system->url());
}

if (!isset($_GET["hash"]) || empty($_GET["hash"])) {
    $functions->redirect($system->url());
}
$log->logThis($log->logTypes["HESAP_DOGRULAMA"]);
$hash = $functions->cleanGet("hash");
if (empty($hash)) {
    $functions->redirect($system->url());
}
if (strlen($hash) != 60) {
    $functions->redirect($system->url());
}
$select_query = $db::selectQuery("users",array(
    "verify_code" => $hash,
    "deleted" => 0,
),true);

$message = [];
if (!empty($select_query)) {
    if($select_query->email_verify == 0){
        $verify = [];
        $verify["email_verify"] = 1;
        //doğrulama kodunu silmeyeceğiz eğer tekrardan doğrulamak isterlerse doğrulandı diye mesaj çıkacak
        //$verify["verify_code"] = null;
        $update = $db::update("users",$verify,["id"=>$select_query->id]);
        if($update){
            $log->logThis($log->logTypes["HESAP_DOGRULAMA_SUCC"]);
            $message["success"][] = "Hesabınız başarılı bir şekilde  doğrulanmıştır. Kayıt sırasında belirlediğiniz bilgiler ile giriş yapabilirsiniz. Üye girişi sayfasına yönlendiriliyorsunuz.";
            $refresh_time = 6;
            $message["refresh_time"] = $refresh_time;
            $functions->refresh($system->url($settings->{"giris_prefix_".$_SESSION["lang"]}),$refresh_time);
        }else{
            $log->logThis($log->logTypes["HESAP_DOGRULAMA_ERR"]);
            $message["reply"][] = "Hesabınız doğrulanamadı lütfen tekrar deneyin.";
        }
    }else{
        $log->logThis($log->logTypes["HESAP_DOGRULAMA_ONCE_DOGRULANMIS"]);
        $message["reply"][] = "Daha önce aktivasyon işleminizi gerçekleştirdiniz. Bilgileriniz ile giriş yapabilirsiniz.";
        $message["reply_custom_title"] = "Uyarı";
    }
}else{
    $log->logThis($log->logTypes["HESAP_DOGRULAMA_HATALI_KOD"]);
    $message["reply"][] = "Geçersiz doğrulama kodu.";
}

View::layout('account-activate');
