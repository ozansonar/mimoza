<?php
//sayfanın izin keyi
$page_role_key = "lang";
$page_add_role_key = "lang-settings";

$id = 0;
$pageData = array();

$default_lang = $siteManager->defaultLanguage();

if(isset($_GET["id"])){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($page_role_key,$editPermissionKey) == false || $session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }

    //log atalım
    $log->logThis($log->logTypes['LANG_DETAIL']);

    //id ye ait içeriği çekiyoruz
    $id = $functions->clean_get_int("id");
    $data = $db::selectQuery("lang",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($system->adminUrl());
    }
    $pageData[$default_lang->short_lang] = (array) $data;
}else{
    //add yetki kontrolü
    if($session->sessionRoleControl($page_add_role_key,$addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }
    //log atalım
    $log->logThis($log->logTypes['LANG_ADD_PAGE']);
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";
$customCss[] = "plugins/icheck-bootstrap/icheck-bootstrap.min.css";
$customCss[] = "plugins/select2/css/select2.min.css";
$customCss[] = "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css";
$customCss[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css";
$customCss[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
$customJs[] = "plugins/select2/js/select2.full.min.js";
$customJs[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js";
$customJs[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js";
$customJs[] = "plugins/ckeditor/ckeditor.js";


if(isset($_POST["submit"]) && $_POST["submit"] == 1){

    $functions->form_lang = $default_lang->short_lang;
    $pageData[$default_lang->short_lang]["lang"] = $functions->clean_post("lang");
    $pageData[$default_lang->short_lang]["short_lang"] = $functions->clean_post("short_lang");
    $pageData[$default_lang->short_lang]["default_lang"] = $functions->clean_post_int("default_lang");
    $pageData[$default_lang->short_lang]["form_validate"] = $functions->clean_post_int("form_validate");
    $pageData[$default_lang->short_lang]["status"] = $functions->clean_post_int("status");


    if(empty($pageData[$default_lang->short_lang]["lang"])){
        $message["reply"][] = "Dil boş olamaz.";
    }

    if(!empty($pageData[$default_lang->short_lang]["name"])){
        if(strlen($pageData[$default_lang->short_lang]["name"]) < 2){
            $message["reply"][] = "Dil 2 karakterden az olamaz.";
        }
        if(strlen($pageData[$default_lang->short_lang]["name"]) > 20){
            $message["success"][] = "Dil 20 karakterden fazla olamaz.";
        }
    }

    if(empty($pageData[$default_lang->short_lang]["short_lang"])){
        $message["reply"][] = "Dil kısaltma boş olamaz.";
    }

    if(!empty($pageData[$default_lang->short_lang]["short_lang"])){
        if(strlen($pageData[$default_lang->short_lang]["short_lang"]) < 2){
            $message["reply"][] = "Dil kısaltma 2 karakterden az olamaz.";
        }
        if(strlen($pageData[$default_lang->short_lang]["short_lang"]) > 5){
            $message["success"][] = "Dil kısaltma 5 karakterden fazla olamaz.";
        }
    }

    if(!in_array($pageData[$default_lang->short_lang]["default_lang"],array_keys($systemSiteModVersion2))){
        $message["reply"][] = "Geçersiz varsayılan dil durumu.";
    }

    if(!in_array($pageData[$default_lang->short_lang]["form_validate"],array_keys($systemSiteModVersion2))){
        $message["reply"][] = "Geçersiz form doğrulama durumu.";
    }

    if(!in_array($pageData[$default_lang->short_lang]["status"],array_keys($systemStatus))){
        $message["reply"][] = "Geçersiz onay durumu.";
    }

    if($pageData[$default_lang->short_lang]["default_lang"] == 1 && $pageData[$default_lang->short_lang]["form_validate"] != 1){
        $message["reply"][] = "Bu dili varsayılan dil olarak seçtiniz bu durumda <b>Form Doğrulama</b> seçeneğinide evet olarak seçmeniz gerekmektedir.";
    }
    //varsayılan dil başka var mı ?
    if($pageData[$default_lang->short_lang]["default_lang"] != 1 && $siteManager->getDefaultLangNotId($id) == false){
        $message["reply"][] = "Varsayılan başka bir dil yok bu dili varsayılan dil yapmak zorundasınız. Bu dili varsayılandan çıkarmak istiyorsanız başka bir dili varsayılan yapmanız yeterli olacaktır.";
    }


    if(empty($message)){
        $db_data = array();
        $db_data["lang"] = $pageData[$default_lang->short_lang]["lang"];
        $db_data["short_lang"] = $pageData[$default_lang->short_lang]["short_lang"];
        $db_data["default_lang"] = $pageData[$default_lang->short_lang]["default_lang"];
        $db_data["form_validate"] = $pageData[$default_lang->short_lang]["form_validate"];
        $db_data["status"] = $pageData[$default_lang->short_lang]["status"];
        $db_data["user_id"] = $session->get("user_id");

        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($id > 0){
            $update = $db::update("lang",$db_data,array("id"=>$id));
            if($update){
                //log atalım
                $log->logThis($log->logTypes['LANG_EDIT_SUCC']);
                $message["success"][] = $lang["content-update"];

                if($pageData[$default_lang->short_lang]["default_lang"] == 1){
                    //işlem tamamlandı eğer varsayılan dil yapıldıysa diğer varsayılan dili kaldıralım
                    $siteManager->defaultLanguageReset($id);
                }

                $functions->refresh($system->adminUrl("lang-settings?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['LANG_EDIT_ERR']);
                $message["reply"][] = $lang["content-update-error"];
            }
        }else{
            //ekleme
            $add = $db::insert("lang",$db_data);

            if($add){
                //log atalım
                $log->logThis($log->logTypes['LANG_ADD_SUCC']);
                $message["success"][] = $lang["content-insert"];

                if($pageData[$default_lang->short_lang]["default_lang"] == 1){
                    //işlem tamamlandı eğer varsayılan dil yapıldıysa diğer varsayılan dili kaldıralım
                    $siteManager->defaultLanguageReset($db->getLastInsertedId());
                }

                $functions->refresh($system->adminUrl("lang-settings"),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['LANG_ADD_ERR']);
                $message["reply"][] = $lang["content-insert-error"];
            }
        }
    }
}

include($system->path("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Dil ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "lang";
$page_button_redirect_text = "Diller";
$page_button_icon = "icon-list";
require $system->adminView('lang-settings');