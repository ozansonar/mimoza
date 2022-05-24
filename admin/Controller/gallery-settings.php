<?php

//sayfanın izin keyi
$data->pageRoleKey = "gallery";
$pageAddRoleKey = "gallery-settings";

$id = 0;
$pageData = [];

$default_lang = $siteManager->defaultLanguage();

if (isset($_GET["id"])) {
    //update yetki kontrolü ve gösterme yetkisi de olması lazım
    if($session->sessionRoleControl($data->pageRoleKey,$constants::editPermissionKey) == false || $session->sessionRoleControl($data->pageRoleKey,$constants::listPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$data->pageRoleKey." permissions => ".$constants::editPermissionKey);
        $session->permissionDenied();
    }
    //log atalım
    $log->logThis($log->logTypes['GALLERY_DETAIL']);

    $id = $functions->cleanGetInt("id");
    $data = $db::selectQuery("gallery",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    //id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
    $data_multi_lang = $db::selectQuery("gallery",array(
        "lang_id" => $data->lang_id,
        "deleted" => 0,
    ));
    foreach($data_multi_lang as $data_row){
        $pageData[$data_row->lang] = (array) $data_row;
        $db_data_lang[$data_row->lang] = $data_row->lang;
    }
}else{
    //add yetki kontrolü
    if($session->sessionRoleControl($pageAddRoleKey,$constants::addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$data->pageRoleKey." permissions => ".$constants::editPermissionKey);
        $session->permissionDenied();
    }
}
$gallery_data = $db::selectQuery("gallery",array(
    "deleted" => 0,
));
$gallery_data_array = [];
foreach ($gallery_data as $gallery_data_row){
    if($id > 0 && $id == $gallery_data_row->id){
        continue;
    }
    $gallery_data_array[$gallery_data_row->id] = $gallery_data_row->name;
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";
$customCss[] = "plugins/select2/css/select2.min.css";
$customCss[] = "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css";
$customCss[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
$customJs[] = "plugins/select2/js/select2.full.min.js";
$customJs[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js";


if(isset($_POST["submit"]) && $_POST["submit"] == 1){

    foreach ($projectLanguages as $project_languages_row){
        $functions->form_lang = $project_languages_row->short_lang; // formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek

        $pageData[$project_languages_row->short_lang]["name"] = $functions->cleanPost("name");
        $pageData[$project_languages_row->short_lang]["link"] = $functions->cleanPost("link");
        $pageData[$project_languages_row->short_lang]["type"] = $functions->cleanPostInt("type");
        if($pageData[$project_languages_row->short_lang]["type"] == 2){
            $pageData[$project_languages_row->short_lang]["top_id"] = $functions->cleanPostInt("top_id");
        }

        $pageData[$project_languages_row->short_lang]["show_order"] = $functions->cleanPostInt("show_order");
        $pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");

        //istenilen kontroller
        if($project_languages_row->form_validate == 1){
            if(empty($pageData[$project_languages_row->short_lang]["name"])){
                $message["reply"][] = $project_languages_row->lang." - Galeri ismi boş olamaz.";
            }

            if(!empty($pageData[$project_languages_row->short_lang]["name"])){
                if(strlen($pageData[$project_languages_row->short_lang]["name"]) < 2){
                    $message["reply"][] = $project_languages_row->lang." - Galeri ismi 2 karakterden az olamaz.";
                }
                if(strlen($pageData[$project_languages_row->short_lang]["name"]) > 200){
                    $message["success"][] = $project_languages_row->lang." - Galeri ismi 200 karakterden fazla olamaz.";
                }
            }

            if(!in_array($pageData[$project_languages_row->short_lang]["type"],array_keys($constants::systemGalleryTypesVersion2))){
                $message["reply"][] = $project_languages_row->lang." - Geçersiz galeri tipi seçimi.";
            }

            if($pageData[$project_languages_row->short_lang]["type"] == 2){
                if(!in_array($pageData[$project_languages_row->short_lang]["top_id"],array_keys($gallery_data_array))){
                    $message["reply"][] = $project_languages_row->lang." - Geçersiz üst galeri seçimi.";
                }
            }

            if($pageData[$project_languages_row->short_lang]["show_order"] > 5000){
                $message["reply"][] = $project_languages_row->lang." - Galeri sırası 5000 den büyük olamaz.";
            }elseif ($pageData[$project_languages_row->short_lang]["show_order"] == 0){
                $message["reply"][] = $project_languages_row->lang." - Lütfen galeri sırasını yazınız.";
            }

            if(!in_array($pageData[$project_languages_row->short_lang]["status"],array_keys($constants::systemStatus))){
                $message["reply"][] = $project_languages_row->lang." - Geçersiz onay durumu seçimi.";
            }
        }
    }
    if(empty($message)){
        //üsteki şartlar sağlandığında resim yükleme işlemlerine geç her seferde resim yüklenmesin
        foreach ($projectLanguages as $project_languages_row){
            include_once($system->path("includes/System/FileUploader.php"));
            $file = new \Includes\System\FileUploader($constants::fileTypePath);
            $file->globalFileName = "img_".$project_languages_row->short_lang;
            $file->uploadFolder = "gallery";
            $file->maxFileSize = 5;
            $file->compressor = true;
            $uploaded = $file->fileUpload();
            if($uploaded["result"] == 1){
                $pageData[$project_languages_row->short_lang]["img"] = $uploaded["img_name"];
            }
            if($uploaded["result"] == 2){
                $message["reply"][] = $project_languages_row->lang." - ".$uploaded["result_message"];
            }
            if($uploaded["result"] == 3 && $project_languages_row->form_validate == 1 && $id == 0){
                $message["reply"][] = $project_languages_row->lang." - ".$uploaded["result_message"];
            }
        }
    }
    if(empty($message)){
        $lang_id = date("YmdHis");
        foreach ($projectLanguages as $project_languages_row){
            $db_data = [];
            $db_data["name"] = $pageData[$project_languages_row->short_lang]["name"];
            $db_data["link"] = !empty($pageData[$project_languages_row->short_lang]["link"]) ? $pageData[$project_languages_row->short_lang]["link"]:$functions->permalink($pageData[$project_languages_row->short_lang]["name"]);
            $db_data["type"] = $pageData[$project_languages_row->short_lang]["type"];
            if($db_data["type"] == 2){
                $db_data["top_id"] = $pageData[$project_languages_row->short_lang]["top_id"];
            }
            $db_data["show_order"] = $pageData[$project_languages_row->short_lang]["show_order"];
            $db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
            if(isset($pageData[$project_languages_row->short_lang]["img"])){
                $db_data["img"] = $pageData[$project_languages_row->short_lang]["img"];
            }
            $db_data["user_id"] = $session->get("user_id");
            if($id > 0){
                if(array_key_exists($project_languages_row->short_lang,$db_data_lang)){
                    //şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
                    //çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
                    //güncelleme
                    $update = $db::update("gallery",$db_data,array("id"=>$pageData[$project_languages_row->short_lang]["id"]));
                }else{
                    //yeni dil insert ediliyor
                    //lang işlemleri sadece eklemede gönderilsin
                    $db_data["lang"] = $project_languages_row->short_lang;
                    $db_data["lang_id"] = $data->lang_id;
                    $add = $db::insert("gallery",$db_data);
                }
            }else{
                //ekleme
                //lang işlemleri sadece eklemede gönderilsin
                $db_data["lang"] = $project_languages_row->short_lang;
                $db_data["lang_id"] = $lang_id;

                $add = $db::insert("gallery",$db_data);
                $lasd_id = $db->getLastInsertedId();
            }
        }
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($id > 0){
            if($update){
                //log atalım
                $log->logThis($log->logTypes['GALLERY_EDIT_SUCC']);
                $message["success"][] = $lang["content-update"];

                $functions->refresh($system->adminUrl("gallery-settings?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['GALLERY_EDIT_ERR']);
                $message["reply"][] = $lang["content-update-error"];
            }
        }else{
            if($add){
                //log atalım
                $log->logThis($log->logTypes['GALLERY_ADD_SUCC']);
                $message["success"][] = $lang["content-insert"];

                $functions->refresh($system->adminUrl("gallery-settings"),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['GALLERY_ADD_ERR']);
                $message["reply"][] = $lang["content-insert-error"];
            }
        }
    }
}


//sayfa başlıkları
$page_title = "Galeri ".(isset($data) ? "Düzenle":"Oluştur");
$sub_title = null;
//butonun gideceği link ve yazısı
$data->pageButtonRedirectLink = "gallery";
$data->pageButtonRedirectText = "Resim Galerileri";
$data->pageButtonIcon = "icon-list";

require $system->adminView('gallery-settings');