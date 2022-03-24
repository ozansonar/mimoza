<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan PC
 * Date: 11.03.2021
 * Time: 15:54
 */

$message = array();
if(isset($_FILES["attachment"]) && !empty($_FILES["attachment"]["name"])){
    include_once($functions->root_url("includes/System/FileUploader.php"));
    $file = new \Includes\System\FileUploader($fileTypePath);
    $file->global_file_name = "attachment";
    $file->upload_folder = "mailing_attachment";
    $file->upload_type = "pdf_word_image_excel";
    $file->max_file_size = 5;
    $uploaded = $file->file_upload();
    if($uploaded["result"] == 1){
        $message["success"][] = "Ek başarıyla yüklendi.";
        $message["img_path"] = $fileTypePath["mailing_attachment"]["url"].$uploaded["img_name"];
        $message["img_name"] = $uploaded["img_name"];
        $message["uniq_id"] = uniqid();
    }else{
        $message["reply"][] = $uploaded["result_message"];
    }
}else{
    $message["reply"][] = "Lütfen bir dosya seçiniz.";
}