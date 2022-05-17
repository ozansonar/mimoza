<?php
//sayfanın izin keyi
$page_role_key = "page-link";
$page_add_role_key = "page-link-settings";

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
    $log->logThis($log->logTypes['PAGE_LINK_DETAIL']);

    $id = $functions->clean_get_int("id");
    $data = $db::selectQuery("file_url",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($adminSystem->adminUrl("page-link"));
    }
    $pageData[$default_lang->short_lang] = (array) $data;
}else{
    //add yetki kontrolü
    if($session->sessionRoleControl($page_add_role_key,$addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }

    //log atalım
    $log->logThis($log->logTypes['PAGE_LINK_ADD_PAGE']);
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

//mevcut urlleri çekip array yapalım
$file_url_array = array();
$data_file_url = $db::selectQuery("file_url",array(
    "deleted" => 0,
));
foreach ($data_file_url as $file_url_row){
    $file_url_array[$file_url_row->url] = $file_url_row->url;
}

$pageLang = [];
foreach ($projectLanguages as $projectLanguagesKey=>$projectLanguagesValue){
    $pageLang[$projectLanguagesKey] = $projectLanguagesKey;
}

$pl_controller = [];
foreach (glob(ROOT_PATH . '/app/Controller/*.php') as $folder){
    $folder = explode('/', rtrim($folder, '/'));
    $folder_end = end($folder);
    $folder_parse = explode(".",$folder_end);
    if(in_array($folder_parse[0],$pageLinkNoListController)){
        continue;
    }
    $pl_controller[$folder_parse[0]] = $folder_parse[0];
}

if(isset($_POST["submit"]) && $_POST["submit"] == 1){

    $pageData[$default_lang->short_lang]["url"] = $functions->clean_post("url");
    $pageData[$default_lang->short_lang]["controller"] = $functions->clean_post("controller");
    $pageData[$default_lang->short_lang]["lang"] = $functions->clean_post("lang");
    $pageData[$default_lang->short_lang]["status_control"] = is_numeric($functions->post("status"));
    $pageData[$default_lang->short_lang]["status"] = $functions->clean_post_int("status");


    if(empty($pageData[$default_lang->short_lang]["url"])){
        $message["reply"][] = "Link boş olamaz.";
    }
    if(!empty($pageData[$default_lang->short_lang]["url"])){
        if(strlen($pageData[$default_lang->short_lang]["url"]) < 2){
            $message["reply"][] = "Link 2 karakterden az olamaz.";
        }
        if(strlen($pageData[$default_lang->short_lang]["url"]) > 200){
            $message["success"][] = "Link 200 karakterden fazla olamaz.";
        }
    }

    if(empty($pageData[$default_lang->short_lang]["controller"])){
        $message["reply"][] = "Lütfen gideceği dosyayı seçin.";
    }
    if(!empty($pageData[$default_lang->short_lang]["controller"])){
        if(!in_array($pageData[$default_lang->short_lang]["controller"],$pl_controller)){
            $message["reply"][] = "Lütfen geçerli bir dosya seçin.";
        }
    }

    if($pageData[$default_lang->short_lang]["status_control"] == false){
        $message["reply"][] = "Lütfen onay durumunu seçiniz.";
    }else{
        if(!in_array($pageData[$default_lang->short_lang]["status"],array_keys($systemStatus))){
            $message["reply"][] = "Geçersiz onay durumu.";
        }
    }

    if($pageData[$default_lang->short_lang]["lang"]){
        if(!in_array($pageData[$default_lang->short_lang]["lang"],array_keys($pageLang))){
            $message["reply"][] = "Geçersiz dil seçimi";
        }
    }

    if(array_key_exists($pageData[$default_lang->short_lang]["url"],$file_url_array)){
        $message["reply"][] = "Bu link zaten mevcut.";
    }




    if(empty($message)){
        $db_data = array();
        $db_data["url"] = $functions->permalink($pageData[$default_lang->short_lang]["url"]);
        $db_data["controller"] = $pageData[$default_lang->short_lang]["controller"];
        $db_data["lang"] = $pageData[$default_lang->short_lang]["lang"];
        $db_data["status"] = $pageData[$default_lang->short_lang]["status"];
        $db_data["user_id"] = $session->get("user_id");

        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($id > 0){
            //güncelleme
            $update = $db::update("file_url",$db_data,array("id"=>$id));
            if($update){
                //log atalım
                $log->logThis($log->logTypes['PAGE_LINK_EDIT_SUCC']);

                $message["success"][] = $lang["content-update"];
                $functions->refresh($adminSystem->adminUrl("page-link-settings?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['PAGE_LINK_EDIT_ERR']);

                $message["reply"][] = $lang["content-update-error"];
            }
        }else{
            //ekleme
            $add = $db::insert("file_url",$db_data);
            if($add){
                $log->logThis($log->logTypes['PAGE_LINK_ADD_SUCC']);
                $message["success"][] = $lang["content-insert"];
                $functions->refresh($adminSystem->adminUrl("page-link"),$refresh_time);
            }else{
                $log->logThis($log->logTypes['PAGE_LINK_ADD_ERR']);
                $message["reply"][] = $lang["content-insert-error"];
            }
        }
    }
}

include($system->path("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Sayfa Linki ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "page-link";
$page_button_redirect_text = "Sayfa Linkleri";
$page_button_icon = "icon-list";
require $adminSystem->adminView('page-link-settings');