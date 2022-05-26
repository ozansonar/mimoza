<?php

use Mrt\MimozaCore\FileUploader;

$message = [];
if(isset($_FILES["attachment"]) && !empty($_FILES["attachment"]["name"])){
    $file = new FileUploader($constants::fileTypePath);
    $file->globalFileName = "attachment";
    $file->uploadFolder = "mailing_attachment";
    $file->uploadType = "pdf_word_image_excel";
    $file->maxFileSize = 5;
    $uploaded = $file->fileUpload();
    if((int)$uploaded["result"] === 1){
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