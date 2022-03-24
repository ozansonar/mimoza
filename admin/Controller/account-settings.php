<?php
use Includes\System\FileUploader;

//sayfanın izin keyi
$page_role_key = "account-settings";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if($session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$listPermissionKey);
    $session->permissionDenied();
}
//log atalım
$log->logThis($log->logTypes['ACCOUNT_DETAIL']);

$default_lang = $siteManager->defaultLanguage();

$page_data = array();
$page_data[$default_lang->short_lang] = (array)$loggedUser;
unset($page_data[$default_lang->short_lang]["password"]);//datanın içinde şifre olmasın

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";
$customCss[] = "plugins/icheck-bootstrap/icheck-bootstrap.min.css";
$customCss[] = "plugins/select2/css/select2.min.css";
$customCss[] = "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
$customJs[] = "plugins/select2/js/select2.full.min.js";
$customJs[] = "plugins/bs-custom-file-input/bs-custom-file-input.min.js";

/**
 * @param \Includes\System\Functions $functions
 * @param string $password
 * @param array $message
 * @param string $password_again
 * @return array
 */
function getMessage(\Includes\System\Functions $functions, string $password, array $message, string $password_again): array
{
    $password_control = $functions->passwordControl($password, "Şifreniz");
    if (!empty($password_control)) {
        foreach ($password_control as $s_cntr) {
            $message["reply"][] = $s_cntr;
        }
    }
    $password_again_control = $functions->passwordControl($password_again, "Şifre tekrarınız");
    if (!empty($password_again_control)) {
        foreach ($password_again_control as $st_cntr) {
            $message["reply"][] = $st_cntr;
        }
    }
    return $message;
}

if(isset($_POST["submit"]) && (int)$_POST["submit"] === 1){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($page_role_key,$editPermissionKey) == false || $session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }
    $page_data[$default_lang->short_lang]["email"] = $functions->clean_post("email");
    $page_data[$default_lang->short_lang]["name"] = $functions->clean_post("name");
    $page_data[$default_lang->short_lang]["surname"] = $functions->clean_post("surname");
    $page_data[$default_lang->short_lang]["theme"] = $functions->clean_post("theme");

    //formda gözükmemesi için bunları arrayda tutmuyorum
    $password = $functions->clean_post("password");
    $password_again = $functions->clean_post("password_again");

    $message = array();
    if(empty($page_data[$default_lang->short_lang]["email"])){
        $message["reply"][] = "E-posta boş olamaz.";
    }
    if(!empty($page_data[$default_lang->short_lang]["email"])){
        if(!$functions->is_email($page_data[$default_lang->short_lang]["email"])){
            $message["reply"][] = "E-postanız email formatında olmalıdır.";
        }
        if($siteManager->uniqDataWithoutThis("users","email",$page_data[$default_lang->short_lang]["email"],$_SESSION["user_id"]) > 0){
            $message["reply"][] = "Bu mail adresi kayıtlarımızda mevcut. Lütfen başka bir tane deneyin.";
        }
    }
    if(empty($page_data[$default_lang->short_lang]["name"])){
        $message["reply"][] = "İsim boş olamaz.";
    }
    if(!empty($page_data[$default_lang->short_lang]["name"])){
        if(strlen($page_data[$default_lang->short_lang]["name"]) < 2){
            $message["reply"][] = "İsim 2 karakterden az olamaz.";
        }
        if(strlen($page_data[$default_lang->short_lang]["name"]) > 20){
            $message["reply"][] = "İsim 20 karakteri geçemez.";
        }
    }
    if(empty($page_data[$default_lang->short_lang]["surname"])){
        $message["reply"][] = "Soyisim boş olamaz.";
    }
    if(!empty($page_data[$default_lang->short_lang]["surname"])){
        if(strlen($page_data[$default_lang->short_lang]["surname"]) < 2){
            $message["reply"][] = "Soyisim 2 karekterden az olamaz.";
        }
        if(strlen($page_data[$default_lang->short_lang]["surname"]) > 20){
            $message["reply"][] = "Soyisim 20 karekteri geçemez.";
        }
    }

    if(!empty($password) && !empty($password_again)){
        $message = getMessage($functions, $password, $message, $password_again);
        if($password !== $password_again){
            $message["reply"][] = "Şifre ve şifre tekrarı aynı olmalıdır.";
        }
    }
    if(!array_key_exists($page_data[$default_lang->short_lang]["theme"], $adminPanelTheme)){
        $message["reply"][] = "Geçersiz tema seçimi.";
    }

    if(empty($message)){
        //resim yükleme işlemi en son
        include_once($functions->root_url("includes/System/FileUploader.php"));
        $file = new FileUploader($fileTypePath);
        $file->global_file_name = "img";
        $file->upload_folder = "user_image";
        $file->max_file_size = 5;
        $file->compressor = true;
        $uploaded = $file->file_upload();
        if((int)$uploaded["result"] === 1){
            $page_data[$default_lang->short_lang]["img"] = $uploaded["img_name"];
        }
        if((int)$uploaded["result"] === 2){
            $message["reply"][] = $uploaded["result_message"];
        }
    }
    if(empty($message)){
        $dat = array();
        $dat["email"] = $page_data[$default_lang->short_lang]["email"];
        $dat["name"] = $page_data[$default_lang->short_lang]["name"];
        $dat["surname"] = $page_data[$default_lang->short_lang]["surname"];
        $dat["theme"] = $page_data[$default_lang->short_lang]["theme"];
        if(isset($page_data[$default_lang->short_lang]["img"])){
            $dat["img"] = $page_data[$default_lang->short_lang]["img"];
        }
        if(!empty($password) && !empty($password_again)){
            $new_password = password_hash($password,PASSWORD_DEFAULT);
            $dat["password"] = $new_password;
        }
        $update = $db::update("users",$dat,array("id"=>$_SESSION["user_id"]));
        if($update){
            //log atalım
            $log->logThis($log->logTypes['ACCOUNT_EDIT_SUCC']);

            $refresh_time = 5;
            $message["refresh_time"] = $refresh_time;
            $message["success"][] = "Hesap ayarları başarılı bir şekilde güncellendi.";
            $functions->refresh($adminSystem->adminUrl("account-settings"),$refresh_time);
        }else{
            //log atalım
            $log->logThis($log->logTypes['ACCOUNT_EDIT_ERR']);

            $message["reply"][] = "Hesap ayarları güncellenemedi tekrar deneyin.";
        }
    }
}

include($functions->root_url("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Hesap Ayarlarım";
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "account-settings";
$page_button_redirect_text = $page_title;
$page_button_icon = null;


require $adminSystem->adminView('account-settings');
?>