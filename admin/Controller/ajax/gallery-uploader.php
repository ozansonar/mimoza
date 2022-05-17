<?php
/**
 * Created by PhpStorm.
 * User: Ozan PC
 * Date: 30.08.2020
 * Time: 23:28
 * Email: ozansonar1@gmail.com
 */
$page_role_key = "gallery-image-upload";
if ($session->sessionRoleControl($page_role_key, $addPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
    $session->permissionDenied();
}
if(isset($_FILES["file_data"])){
    //ini_set('memory_limit', '9048M');
    //$functions->pre_arr($_POST);
    //$functions->pre_arr($_FILES);
    header('Content-Type: application/json'); // set json response headers
    $gallery_id = $functions->clean_post_int("gallery_id");

    include_once($system->path("includes/System/FileUploader.php"));
    $file = new \Includes\System\FileUploader($constants::fileTypePath);
    $file->global_file_name = "file_data";
    $file->upload_folder = "gallery";
    $file->gallery_id = $gallery_id;
    $file->max_file_size = 5;
    $file->compressor = true;
    $uploaded = $file->file_upload();
    $message = array();
    if($uploaded == 3){
        $message["error"] = "Dosya seçilmedi.";
    }
    if($uploaded == 2){
        $message["error"] = "Resim yüklenirken hata oluştuştu.";
    }
    if(empty($message)){
        $db_data = array();
        $db_data["gallery_id"] = $gallery_id;
        $db_data["image"] = $uploaded["img_name"];
        $db_data["status"] = 1;
        $insert = $db::insert("gallery_image",$db_data);
        if($insert){
            $img_directory = $constants::fileTypePath["gallery"]["url"].$gallery_id."/".$uploaded["img_name"];

            //log atalım
            $log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_SUCC'],"eklenen id row id:".$db->getLastInsertedId());

            $message = [
                //'chunkIndex' => $index,         // the chunk index processed
                'initialPreview' => $img_directory, // the thumbnail preview data (e.g. image)
                'initialPreviewConfig' => [
                    [
                        'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                        'caption' => $uploaded["img_name"], // caption
                        //'key' => $fileId,       // keys for deleting/reorganizing preview
                        //'fileId' => $fileId,    // file identifier
                        'size' => $_FILES["file_data"]["size"],    // file size
                        'zoomData' => $img_directory, // separate larger zoom data
                    ]
                ],
                'append' => true
            ];
        }else{
            //log atalım
            $log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_ERR']);
            $message["error"] = "Resim yüklenirken hata oluştuştu.";
        }
    }

}else{
    $message = array();
    $message["error"] = "Dosya seçilmedi.";

}
echo json_encode($message);
exit;