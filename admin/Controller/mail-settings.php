<?php
//sayfanın izin keyi
$data->pageRoleKey = "mailler";
$pageAddRoleKey = "mail-settings";

//sayfada işlem yapılacak table
$table = "mailing";

$default_lang = $siteManager->defaultLanguage();

$id = 0;
$pageData = [];
$pageData[$default_lang->short_lang]["image"] = "";
if(isset($_GET["id"])){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($data->pageRoleKey,$constants::editPermissionKey) == false || $session->sessionRoleControl($data->pageRoleKey,$constants::listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$data->pageRoleKey." permissions => ".$constants::editPermissionKey);
        $session->permissionDenied();
    }

    //log atalım
    $log->logThis($log->logTypes['EMAIL_TEMALARI_DETAIL']);

    //id ye ait içeriği çekiyoruz
    $id = $functions->cleanGetInt("id");
    $data = $db::selectQuery($table,array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($system->adminUrl());
    }

    if (!empty($data->image)) {
        $mailing_image_unserialize = unserialize($data->image);
        foreach ($mailing_image_unserialize as $m_key => $m_value) {
            $data->text = str_replace("cid:image_" . $m_key, $constants::fileTypePath["mailing"]["url"].$m_value, $data->text);
        }
        $data->image = implode(",", $mailing_image_unserialize);
    }

    if (!empty($data->attachment)) {
        $attachment_array = [];
        $attecament = unserialize($data->attachment);
        foreach ($attecament as $attecament_row) {
            $at_name = $functions->cleaner($attecament_row);
            if (file_exists($constants::fileTypePath["mailing_attachment"]["full_path"].$at_name)) {
                $attachment_array[] = $at_name;
            }
        }
    }

    $pageData[$default_lang->short_lang] = (array)$data;
}else{
    //add yetki kontrolü
    if($session->sessionRoleControl($pageAddRoleKey,$constants::addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$data->pageRoleKey." permissions => ".$constants::editPermissionKey);
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
$customCss[] = "plugins/toastr/toastr.min.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
$customJs[] = "plugins/select2/js/select2.full.min.js";
$customJs[] = "plugins/ckeditor/ckeditor.js";
$customJs[] = "plugins/toastr/toastr.min.js";



if(isset($_POST["submit"]) && $_POST["submit"] == 1){

    foreach ($projectLanguages as $project_languages_row){
        $functions->form_lang = $project_languages_row->short_lang; // formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek

        $pageData[$project_languages_row->short_lang]["subject"] = $functions->cleanPost("subject");
        $pageData[$project_languages_row->short_lang]["text"] = $functions->cleanPostTextarea("text");
        $pageData[$project_languages_row->short_lang]["not_text"] = $functions->cleanPost("not_text");
        $pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");

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


            if(!in_array($pageData[$project_languages_row->short_lang]["status"],array_keys($constants::systemStatus))){
                $message["reply"][] = $project_languages_row->lang." - Geçersiz onay durumu.";
            }
        }
    }
    if(empty($message)){
        $lang_id = date("YmdHis");
        foreach ($projectLanguages as $project_languages_row){
            $db_data = [];
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

                $functions->refresh($system->adminUrl($pageAddRoleKey."?id=".$id),$refresh_time);
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

                $functions->refresh($system->adminUrl($pageAddRoleKey),$refresh_time);
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
$page_title = "Mailing ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$data->pageButtonRedirectLink = $data->pageRoleKey;
$data->pageButtonRedirectText = "Gönderilmiş Mailler";
$data->pageButtonIcon = "icon-list";
require $system->adminView($pageAddRoleKey);