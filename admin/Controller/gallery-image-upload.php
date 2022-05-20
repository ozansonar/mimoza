<?php
$page_role_key = "gallery-image-upload";
if ($session->sessionRoleControl($page_role_key, $constants::addPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$constants::editPermissionKey);
    $session->permissionDenied();
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/bootstrap-fileinput-master/css/fileinput.css";
$customCss[] = "plugins/bootstrap-fileinput-master/themes/explorer-fas/theme.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/bootstrap-fileinput-master/js/plugins/piexif.js";
$customJs[] = "plugins/bootstrap-fileinput-master/js/plugins/sortable.js";
$customJs[] = "plugins/bootstrap-fileinput-master/js/fileinput.js";
$customJs[] = "plugins/bootstrap-fileinput-master/js/locales/tr.js";
$customJs[] = "plugins/bootstrap-fileinput-master/themes/explorer-fas/theme.js";


$id = 0;
if(isset($_POST["id"]) && is_numeric($_POST["id"])){
    $del_id = $functions->cleanPostInt("id");
    $data = array();
    $data["deleted"] = 1;
    $delete = $db::update("gallery_image",$data,array("id"=>$del_id));
    $message = array();
    if($delete){
        //log atalım
        $log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_DELETE_SUCC'],"silinen row id:".$del_id);

        $message = [
        'append' => true
        ];
    }else{
        //log atalım
        $log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_DELETE_ERR'],"silinemeyen row id:".$del_id);

        $message = [
            'error' => "Resim silinemedi tekrar deneyiniz."
        ];
    }
    echo json_encode($message);
    exit;
}
if(isset($_GET["id"]) && is_numeric($_GET["id"])){
    $id = $functions->cleanGetInt("id");
    $data = $db::selectQuery("gallery",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($data)){
        $functions->redirect($system->adminUrl());
    }
    //log atalım
    $log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_DETAIL']);

    echo $functions->csrfToken();

    //galeriye ait resimleri çekeklim
    $images = $db::$db->prepare("SELECT * FROM gallery_image WHERE gallery_id=:g_id AND status=1 AND deleted=0 ORDER BY id ASC");
    $images->bindParam(":g_id",$id,PDO::PARAM_INT);
    $images->execute();
    $images_count = $images->rowCount();

    $initialPreview = "";
    $initialPreviewConfig = "";

    if($images_count > 0){
        $images_result = $images->fetchAll(PDO::FETCH_OBJ);
        foreach ($images_result as $images_row){
            $image_path = $constants::fileTypePath["gallery"]["full_path"].$id."/".$images_row->image;
            if(!empty($images_row->image) && file_exists($image_path)){
                $file_size = filesize($image_path);
                $initialPreview .= '"'.$constants::fileTypePath["gallery"]["url"].$id."/".$images_row->image.'",';
                $initialPreviewConfig .= '{caption: "'.$images_row->image.'", size: '.$file_size.', width: "200px", url: "gallery-image-upload", key: '.$images_row->id.', extra: {token: "'.$_SESSION["token"].'", id : "'.$images_row->id.'"}  },';
            }
            // {caption: "nature-21.jpg", size: 329892, width: "120px", url: "{$url}", key: 1},
        }
    }
}



//sayfa başlıkları
$page_title = "Galeri Resim ".(isset($data) ? "Düzenle":"Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "gallery";
$page_button_redirect_text = "Resim Galerileri";
$page_button_icon = "icon-list";

require $system->adminView('gallery-image-upload');
