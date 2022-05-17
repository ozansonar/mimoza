<?php
//sayfanın izin keyi
$page_role_key = "email-themes";
$page_add_role_key = "email-theme-settings";

//sayfada işlem yapılacak table
$table = "email_template";

$id = 0;
$pageData = array();
if(isset($_GET["id"])){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($page_role_key,$editPermissionKey) == false || $session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }

    //log atalım
    $log->logThis($log->logTypes['EMAIL_TEMALARI_DETAIL']);

    //id ye ait içeriği çekiyoruz
    $id = $functions->clean_get_int("id");
    $data = $db::selectQuery($table,array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($adminSystem->adminUrl());
    }
    //id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
    $data_multi_lang = $db::selectQuery($table,array(
        "lang_id" => $data->lang_id,
        "deleted" => 0,
    ));
    foreach($data_multi_lang as $data_row){
        $pageData[$data_row->lang] = (array) $data_row;
        $db_data_lang[$data_row->lang] = $data_row->lang;
    }
}else{
    //add yetki kontrolü
    if($session->sessionRoleControl($page_add_role_key,$addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }
    //log atalım
    $log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_PAGE_DETAIL']);
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

    foreach ($projectLanguages as $project_languages_row){
        $functions->form_lang = $project_languages_row->short_lang; // formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek

        $pageData[$project_languages_row->short_lang]["subject"] = $functions->clean_post("subject");
        $pageData[$project_languages_row->short_lang]["text"] = $functions->clean_post_textarea("text");
        $pageData[$project_languages_row->short_lang]["not_text"] = $functions->clean_post("not_text");
        $pageData[$project_languages_row->short_lang]["status"] = $functions->clean_post_int("status");

        //istenilen kontroller
        if($project_languages_row->form_validate == 1){
            if(empty($pageData[$project_languages_row->short_lang]["subject"])){
                $message["reply"][] = $project_languages_row->lang." - Konu boş olamaz.";
            }

            if(!empty($pageData[$project_languages_row->short_lang]["subject"])){
                if(strlen($pageData[$project_languages_row->short_lang]["subject"]) < 2){
                    $message["reply"][] = $project_languages_row->lang." - Konu 2 karakterden az olamaz.";
                }
                if(strlen($pageData[$project_languages_row->short_lang]["subject"]) > 200){
                    $message["reply"][] = $project_languages_row->lang." - Konu 200 karakterden fazla olamaz.";
                }
            }

            if(empty($pageData[$project_languages_row->short_lang]["text"])){
                $message["reply"][] = $project_languages_row->lang." - E-posta içeriği boş olamaz.";
            }


            if(!in_array($pageData[$project_languages_row->short_lang]["status"],array_keys($systemStatus))){
                $message["reply"][] = $project_languages_row->lang." - Geçersiz onay durumu.";
            }
        }
    }
    if(empty($message)){
        $lang_id = date("YmdHis");
        foreach ($projectLanguages as $project_languages_row){
            $db_data = array();
            $db_data["subject"] = $pageData[$project_languages_row->short_lang]["subject"];
            $db_data["text"] = $pageData[$project_languages_row->short_lang]["text"];
            $db_data["not_text"] = $pageData[$project_languages_row->short_lang]["not_text"];
            $db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
            $db_data["user_id"] = $session->get("user_id");
            if($id > 0){
                if(array_key_exists($project_languages_row->short_lang,$db_data_lang)){
                    //şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
                    //çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
                    //güncelleme
                    $update = $db::update($table,$db_data,array("id"=>$pageData[$project_languages_row->short_lang]["id"]));
                }else{
                    //yeni dil insert ediliyor
                    //lang işlemleri sadece eklemede gönderilsin
                    $db_data["lang"] = $project_languages_row->short_lang;
                    $db_data["lang_id"] = $data->lang_id;
                    $add = $db::insert($table,$db_data);
                }
            }else{
                //ekleme
                //lang işlemleri sadece eklemede gönderilsin
                $db_data["lang"] = $project_languages_row->short_lang;
                $db_data["lang_id"] = $lang_id;

                $add = $db::insert($table,$db_data);
            }
        }
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($id > 0){
            if($update){
                //log atalım
                $log->logThis($log->logTypes['EMAIL_TEMALARI_EDIT_SUCC']);
                $message["success"][] = $lang["content-update"];

                $functions->refresh($adminSystem->adminUrl($page_add_role_key."?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['EMAIL_TEMALARI_EDIT_ERR']);
                $message["reply"][] = $lang["content-update-error"];
            }
        }else{
            if($add){
                //log atalım
                $log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_SUCC']);
                $message["success"][] = $lang["content-insert"];

                $functions->refresh($adminSystem->adminUrl($page_add_role_key),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['EMAIL_TEMALARI_ADD_ERR']);
                $message["reply"][] = $lang["content-insert-error"];
            }
        }
    }
}

include($system->path("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "E-posta Teması ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = "E-posta içeriğinde <b>\"#\"</b> içinde yazılmış kelimleri lütfen silmeyin aksi halde e-posta içeriğinde yanlışlıklar olacaktır. Silmemeniz gereken örnek kelimeler <b>#ad_soyad#,#rezervasyon_kodu#</b> gibi kelimeleri silmeyiniz.";
//butonun gideceği link ve yazısı
$page_button_redirect_link = $page_role_key;
$page_button_redirect_text = "E-posta Temaları";
$page_button_icon = "icon-list";
require $adminSystem->adminView($page_add_role_key);