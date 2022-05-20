<?php
$page_role_key = "video-upload";

$pageData = array();

if ($session->sessionRoleControl($page_role_key, $constants::addPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$constants::editPermissionKey);
    $session->permissionDenied();
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";
$customCss[] = "plugins/icheck-bootstrap/icheck-bootstrap.min.css";
$customCss[] = "plugins/select2/css/select2.min.css";
$customCss[] = "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css";
$customCss[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css";
$customCss[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css";
$customCss[] = "plugins/datatables-bs4/css/dataTables.bootstrap4.min.css";
$customCss[] = "plugins/datatables-responsive/css/responsive.bootstrap4.min.css";
$customCss[] = "plugins/datatables-buttons/css/buttons.bootstrap4.min.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
$customJs[] = "plugins/select2/js/select2.full.min.js";
$customJs[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js";
$customJs[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js";
//$customJs[] = "plugins/ckeditor/ckeditor.js";
$customJs[] = "plugins/datatables/jquery.dataTables.min.js";
$customJs[] = "plugins/datatables-bs4/js/dataTables.bootstrap4.min.js";
$customJs[] = "plugins/datatables-responsive/js/dataTables.responsive.min.js";
$customJs[] = "plugins/datatables-responsive/js/responsive.bootstrap4.min.js";
$customJs[] = "plugins/datatables-buttons/js/dataTables.buttons.min.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.bootstrap4.min.js";
$customJs[] = "plugins/jszip/jszip.min.js";
$customJs[] = "plugins/pdfmake/pdfmake.min.js";
$customJs[] = "plugins/pdfmake/vfs_fonts.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.html5.min.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.print.min.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.colVis.min.js";


if(isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])){
    //silme yetkisi kontrol
    if($session->sessionRoleControl($page_role_key,$constants::addPermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$deletePermissionKey);
        $session->permissionDenied();
    }

    $del_id = $functions->cleanGetInt("delete");
    $delete = $siteManager->multipleLanguageDataDelete("youtube_videos",$del_id);

    $message = array();
    if($delete){
        //log atalım
        $log->logThis($log->logTypes['GALLERY_VIDEO_DEL_SUCC']);

        $message["success"][] = $lang["content-delete"];
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        $functions->refresh($system->adminUrl("gallery-video-upload?id=".intval($_GET["id"])),$refresh_time);
    }else{
        //log atalım
        $log->logThis($log->logTypes['GALLERY_VIDEO_DEL_ERR']);

        $message["reply"][] = $lang["content-delete-error"] ;
    }
}

$id = 0;
$video_id = 0;
if(isset($_GET["id"]) && is_numeric($_GET["id"]) || isset($_GET["video_id"]) && is_numeric($_GET["video_id"])){
    $id = $functions->cleanGetInt("id");
    $gallery_data = $db::selectQuery("gallery",array(
        "status" => $id,
        "deleted" => 0,
    ),true);
    if(empty($gallery_data)){
        $functions->redirect($system->adminUrl());
    }
    if(isset($_GET["video_id"])){
        $video_id = $functions->cleanGetInt("video_id");
        $data = $db::selectQuery("youtube_videos",array(
            "id" => $video_id,
            "deleted" => 0,
        ),true);
        if(empty($data)){
            $functions->redirect($system->adminUrl());
        }
        //id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
        $data_multi_lang = $db::selectQuery("youtube_videos",array(
            "lang_id" => $data->lang_id,
            "deleted" => 0,
        ));
        foreach($data_multi_lang as $data_row){
            $pageData[$data_row->lang] = (array) $data_row;
            $db_data_lang[$data_row->lang] = $data_row->lang;
        }
    }
    //log atalım
    $log->logThis($log->logTypes['GALLERY_VIDEO_UPLOAD_DETAIL']);


    //galeriye ait videoları çekeklim
    $videos = $db::$db->prepare("SELECT * FROM youtube_videos WHERE gallery_id=:g_id AND lang=:lang AND deleted=0 ORDER BY id ASC");
    $videos->bindParam(":g_id",$id,PDO::PARAM_INT);
    $videos->bindParam(":lang",$siteManager->defaultLanguage()->short_lang,PDO::PARAM_STR);
    $videos->execute();
    $videos_count = $videos->rowCount();
    $videos_data = $videos->fetchAll(PDO::FETCH_OBJ);
}
if(isset($_POST["submit"]) && $_POST["submit"] == 1){

    foreach ($projectLanguages as $project_languages_row){
        $functions->form_lang = $project_languages_row->short_lang; // formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek

        $pageData[$project_languages_row->short_lang]["title"] = $functions->cleanPost("title");
        $pageData[$project_languages_row->short_lang]["link"] = $functions->cleanPost("link");
        $pageData[$project_languages_row->short_lang]["show_order"] = $functions->cleanPostInt("show_order");
        $pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");

        //istenilen kontroller
        if($project_languages_row->form_validate == 1){
            if(empty($pageData[$project_languages_row->short_lang]["title"])){
                $message["reply"][] = $project_languages_row->lang." - Başlık boş olamaz.";
            }
            if(!empty($pageData[$project_languages_row->short_lang]["title"])){
                if(strlen($pageData[$project_languages_row->short_lang]["title"]) < 2){
                    $message["reply"][] = $project_languages_row->lang." - Başlık 2 karakterden az olamaz.";
                }
                if(strlen($pageData[$project_languages_row->short_lang]["title"]) > 200){
                    $message["reply"][] = $project_languages_row->lang." - Başlık 200 karakterden fazla olamaz.";
                }
            }

            if(empty($pageData[$project_languages_row->short_lang]["link"])){
                $message["reply"][] = $project_languages_row->lang." - Link boş olamaz.";
            }
            if(!empty($pageData[$project_languages_row->short_lang]["link"])){
                if(strlen($pageData[$project_languages_row->short_lang]["link"]) < 2){
                    $message["reply"][] = $project_languages_row->lang." - Link 2 karakterden az olamaz.";
                }
                if(strlen($pageData[$project_languages_row->short_lang]["link"]) > 200){
                    $message["success"][] = $project_languages_row->lang." - Link 200 karakterden fazla olamaz.";
                }
            }


            if($pageData[$project_languages_row->short_lang]["show_order"] > 5000){
                $message["reply"][] = $project_languages_row->lang." - Gösterim sırası 5000 den büyük olamaz.";
            }elseif ($pageData[$project_languages_row->short_lang]["show_order"] == 0){
                $message["reply"][] = $project_languages_row->lang." - Lütfen gösterim sırasını yazınız.";
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
            $db_data["gallery_id"] = $id;
            $db_data["title"] = $pageData[$project_languages_row->short_lang]["title"];
            $db_data["link"] = $pageData[$project_languages_row->short_lang]["link"];
            $db_data["show_order"] = $pageData[$project_languages_row->short_lang]["show_order"];
            $db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
            $db_data["user_id"] = $session->get("user_id");
            if($video_id > 0){
                if(array_key_exists($project_languages_row->short_lang,$db_data_lang)){
                    //şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
                    //çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
                    //güncelleme
                    $update = $db::update("youtube_videos",$db_data,array("id"=>$pageData[$project_languages_row->short_lang]["id"]));
                }else{
                    //yeni dil insert ediliyor
                    //lang işlemleri sadece eklemede gönderilsin
                    $db_data["lang"] = $project_languages_row->short_lang;
                    $db_data["lang_id"] = $data->lang_id;
                    $add = $db::insert("youtube_videos",$db_data);
                }
            }else{
                //ekleme
                //lang işlemleri sadece eklemede gönderilsin
                $db_data["lang"] = $project_languages_row->short_lang;
                $db_data["lang_id"] = $lang_id;
                $add = $db::insert("youtube_videos",$db_data);
            }
        }
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        if($video_id > 0){
            if($update){
                //log atalım
                $log->logThis($log->logTypes['GALLERY_VIDEO_EDIT_SUCC']);
                $message["success"][] = $lang["content-update"];

                $functions->refresh($system->adminUrl("gallery-video-upload?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['GALLERY_VIDEO_EDIT_ERR']);
                $message["reply"][] = $lang["content-update-error"];
            }
        }else{
            if($add){
                //log atalım
                $log->logThis($log->logTypes['GALLERY_VIDEO_ADD_SUCC']);
                $message["success"][] = $lang["content-insert"];

                $functions->refresh($system->adminUrl("gallery-video-upload?id=".$id),$refresh_time);
            }else{
                //log atalım
                $log->logThis($log->logTypes['GALLERY_VIDEO_ADD_ERR']);
                $message["reply"][] = $lang["content-insert-error"];
            }
        }
    }
}

include($system->path("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Video ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "gallery";
$page_button_redirect_text = "Resim Galerileri";
$page_button_icon = "icon-list";

require $system->adminView('gallery-video-upload');