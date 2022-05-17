<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan PC
 * Date: 11.03.2021
 * Time: 15:54
 */
if(isset($_FILES["file"]) && !empty($_FILES["file"]["name"])){
    include_once($system->path("includes/System/FileUploader.php"));
    $file = new \Includes\System\FileUploader($fileTypePath);
    $file->global_file_name = "file";
    $file->upload_folder = "mailing";
    $file->max_file_size = 5;
    $uploaded = $file->file_upload();
    if($uploaded["result"] == 1){
        $message["success"][] = "Resim başarıyla yüklendi.";
        $message["img_path"] = $fileTypePath["mailing"]["url"].$uploaded["img_name"];
        $message["img_name"] = $uploaded["img_name"];
    }else{
        $message["reply"][] = $uploaded["result_message"];
    }
}else{
    $message["reply"][] = "Lütfen bir dosya seçiniz.";
}