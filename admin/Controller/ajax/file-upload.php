<?php

use Mrt\MimozaCore\FileUploader;

if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
	$file = new FileUploader($constants::fileTypePath);
	$file->globalFileName = "file";
	$file->uploadFolder = "mailing";
	$file->maxFileSize = 5;
	$uploaded = $file->fileUpload();
	if ((int)$uploaded["result"] === 1) {
		$message["success"][] = "Resim başarıyla yüklendi.";
		$message["img_path"] = $constants::fileTypePath["mailing"]["url"] . $uploaded["img_name"];
		$message["img_name"] = $uploaded["img_name"];
	} else {
		$message["reply"][] = $uploaded["result_message"];
	}
} else {
	$message["reply"][] = "Lütfen bir dosya seçiniz.";
}