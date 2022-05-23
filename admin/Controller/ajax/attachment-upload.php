<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan PC
 * Date: 11.03.2021
 * Time: 15:54
 */

$message = [];
if(isset($_FILES["attachment"]) && !empty($_FILES["attachment"]["name"])){
    include_once($system->path("includes/System/FileUploader.php"));
    $file = new \Includes\System\FileUploader($constants::fileTypePath);
    $file->globalFileName = "attachment";
    $file->uploadFolder = "mailing_attachment";
    $file->upload_type = "pdf_word_image_excel";
    $file->maxFileSize = 5;
    $uploaded = $file->fileUpload();
    if($uploaded["result"] == 1){
        $message["success"][] = "Ek başarıyla yüklendi.";
        $message["img_path"] = $constants::fileTypePath["mailing_attachment"]["url"].$uploaded["img_name"];
        $message["img_name"] = $uploaded["img_name"];
        $message["uniq_id"] = uniqid();
    }else{
        $message["reply"][] = $uploaded["result_message"];
    }
}else{
    $message["reply"][] = "Lütfen bir dosya seçiniz.";
}