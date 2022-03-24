<?php
//sayfanın izin keyi
$page_role_key = "content-categories";
$page_add_role_key = "content-categories-settings";

$id = 0;
$page_data = array();
if(isset($_GET["id"])){
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($page_role_key,$editPermissionKey) == false || $session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }

    //log atalım
    $log->logThis($log->logTypes['CONTENT_CATEGORIES_DETAIL']);

    //id ye ait içeriği çekiyoruz
    $id = $functions->clean_get_int("id");
    $data = $db::selectQuery("content_categories",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($adminSystem->adminUrl());
    }
    //id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
    $data_multi_lang = $db::selectQuery("content_categories",array(
        "lang_id" => $data->lang_id,
        "deleted" => 0,
    ));
    foreach($data_multi_lang as $data_row){
        $page_data[$data_row->lang] = (array) $data_row;
        $db_data_lang[$data_row->lang] = $data_row->lang;
    }
}else{
    //add yetki kontrolü
    if($session->sessionRoleControl($page_add_role_key,$addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
        $session->permissionDenied();
    }
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

        $page_data[$project_languages_row->short_lang]["title"] = $functions->clean_post("title");
        $page_data[$project_languages_row->short_lang]["show_order_control"] = is_numeric($functions->post("show_order"));
        $page_data[$project_languages_row->short_lang]["show_order"] = $functions->clean_post_int("show_order");
        $page_data[$project_languages_row->short_lang]["show_type_control"] = is_numeric($functions->post("show_type"));
        $page_data[$project_languages_row->short_lang]["show_type"] = $functions->clean_post_int("show_type");
        $page_data[$project_languages_row->short_lang]["status_control"] = is_numeric($functions->post("status"));
        $page_data[$project_languages_row->short_lang]["status"] = $functions->clean_post_int("status");

        //istenilen kontroller
        if($project_languages_row->form_validate == 1){
            if(empty($page_data[$project_languages_row->short_lang]["title"])){
                $message["reply"][] = $project_languages_row->lang." - Katetori ismi boş olamaz.";
            }

            if(!empty($page_data[$project_languages_row->short_lang]["title"])){
                if(strlen($page_data[$project_languages_row->short_lang]["title"]) < 2){
                    $message["reply"][] = $project_languages_row->lang." - Kategori ismi 2 karakterden az olamaz.";
                }
                if(strlen($page_data[$project_languages_row->short_lang]["title"]) > 200){
                    $message["reply"][] = $project_languages_row->lang." - Kategori ismi 200 karakterden fazla olamaz.";
                }
            }

            if($page_data[$project_languages_row->short_lang]["show_order_control"] == false){
                $message["reply"][] = $project_languages_row->lang." - Gösterim sırası sadece rakam olmalıdır.";
            }

            if($page_data[$project_languages_row->short_lang]["show_order"] > 5000){
                $message["reply"][] = $project_languages_row->lang." - Gösterim sırası 5000 den büyük olamaz.";
            }elseif ($page_data[$project_languages_row->short_lang]["show_order"] == 0){
                $message["reply"][] = $project_languages_row->lang." - Lütfen gösterim sırasını yazınız.";
            }

            if($page_data[$project_languages_row->short_lang]["show_type_control"] == false){
                $message["reply"][] = $project_languages_row->lang." - Lütfen gösterim seklini seçiniz.";
            }
            if($page_data[$project_languages_row->short_lang]["show_type_control"]){
                if(!in_array($page_data[$project_languages_row->short_lang]["show_type"],array_keys($systemContentCategoriesShowTypes2))){
                    $message["reply"][] = $project_languages_row->lang." - Geçersiz gösterim şekli seçimi.";
                }
            }

            if($page_data[$project_languages_row->short_lang]["status_control"] == false){
                $message["reply"][] = $project_languages_row->lang." - Lütfen onay durumunu seçiniz.";
            }
            if($page_data[$project_languages_row->short_lang]["status_control"]){
                if(!in_array($page_data[$project_languages_row->short_lang]["status"],array_keys($systemStatus))){
                    $message["reply"][] = $project_languages_row->lang." - Geçersiz onay durumu.";
                }
            }
        }
    }
    if(empty($message)){
        //üsteki şartlar sağlandığında resim yükleme işlemlerine geç her seferde resim yüklenmesin
        foreach ($projectLanguages as $project_languages_row){
            include_once($functions->root_url("includes/System/FileUploader.php"));
            $file = new \Includes\System\FileUploader($fileTypePath);
            $file->global_file_name = "img_".$project_languages_row->short_lang;
            $file->upload_folder = "content_categories";
            $file->max_file_size = 5;
            $file->compressor = true;
            $uploaded = $file->file_upload();
            if(isset($uploaded["result"]) && $uploaded["result"] == 1){
                $page_data[$project_languages_row->short_lang]["img"] = $uploaded["img_name"];
            }
            if(isset($uploaded["result"]) && $uploaded["result"] == 2){
                $message["reply"][] = $uploaded["result_message"];
            }
        }
    }
    if(empty($message)){
        $lang_id = date("YmdHis");
        foreach ($projectLanguages as $project_languages_row){
            $db_data = array();
            $db_data["title"] = $page_data[$project_languages_row->short_lang]["title"];
            $db_data["link"] = $functions->permalink($page_data[$project_languages_row->short_lang]["title"]);
            if(isset($page_data[$project_languages_row->short_lang]["img"])){
                $db_data["img"] = $page_data[$project_languages_row->short_lang]["img"];
            }
            $db_data["show_type"] = $page_data[$project_languages_row->short_lang]["show_type"];
            $db_data["show_order"] = $page_data[$project_languages_row->short_lang]["show_order"];
            $db_data["status"] = $page_data[$project_languages_row->short_lang]["status"];
            $db_data["user_id"] = $session->get("user_id");
            if($id > 0){
                if(array_key_exists($project_languages_row->short_lang,$db_data_lang)){
                    //şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
                    //çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
                    //güncelleme
                    $update = $db::update("content_categories",$db_data,array("id"=>$page_data[$project_languages_row->short_lang]["id"]));
                }else{
                    //yeni dil insert ediliyor
                    //lang işlemleri sadece eklemede gönderilsin
                    $db_data["lang"] = $project_languages_row->short_lang;
                    $db_data["lang_id"] = $data->lang_id;
                    $add = $db::insert("content_categories",$db_data);
                }
            }else{
                //ekleme
                //lang işlemleri sadece eklemede gönderilsin
                $db_data["lang"] = $project_languages_row->short_lang;
                $db_data["lang_id"] = $lang_id;

                $add = $db::insert("content_categories",$db_data);
            }
        }
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($id > 0){
            if($update){
                //log atalım
                $log->logThis($log->logTypes['CONTENT_CATEGORIES_EDIT_SUCC']);
                $message["success"][] = $lang["content-update"];

                $functions->refresh($adminSystem->adminUrl("content-categories-settings?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['CONTENT_CATEGORIES_EDIT_ERR']);
                $message["reply"][] = $lang["content-update-error"];
            }
        }else{
            if($add){
                //log atalım
                $log->logThis($log->logTypes['CONTENT_CATEGORIES_ADD_SUCC']);
                $message["success"][] = $lang["content-insert"];

                $functions->refresh($adminSystem->adminUrl("content-categories-settings"),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['CONTENT_CATEGORIES_ADD_ERR']);
                $message["reply"][] = $lang["content-insert-error"];
            }
        }
    }
}

include($functions->root_url("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "İçerik Kategorisi ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "content-categories";
$page_button_redirect_text = "Kategoriler";
$page_button_icon = "icon-list";
require $adminSystem->adminView('content-categories-settings');