<?php

use Mrt\MimozaCore\FileUploader;

$data->pageRoleKey = "gallery-image-upload";
if ($session->sessionRoleControl($data->pageRoleKey, $constants::addPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $data->pageRoleKey . " permissions => " . $constants::editPermissionKey);
	$session->permissionDenied();
}
if (isset($_FILES["file_data"])) {
	header('Content-Type: application/json');
	$gallery_id = $functions->cleanPostInt("gallery_id");

	$file = new FileUploader($constants::fileTypePath);
	$file->globalFileName = "file_data";
	$file->uploadFolder = "gallery";
	$file->galleryId = $gallery_id;
	$file->maxFileSize = 5;
	$file->compressor = true;
	$uploaded = $file->fileUpload();
	$message = [];
	if ((int)$uploaded === 3) {
		$message["error"] = "Dosya seçilmedi.";
	}
	if ((int)$uploaded === 2) {
		$message["error"] = "Resim yüklenirken hata oluştuştu.";
	}
	if (empty($message)) {
		$db_data = [];
		$db_data["gallery_id"] = $gallery_id;
		$db_data["image"] = $uploaded["img_name"];
		$db_data["status"] = 1;
		$insert = $db::insert("gallery_image", $db_data);
		if ($insert) {
			$img_directory = $constants::fileTypePath["gallery"]["url"] . $gallery_id . "/" . $uploaded["img_name"];
			$log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_SUCC'], "eklenen id row id:" . $db->getLastInsertedId());
			$message = [
				'initialPreview' => $img_directory, // the thumbnail preview data (e.g. image)
				'initialPreviewConfig' => [
					[
						'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
						'caption' => $uploaded["img_name"], // caption
						'size' => $_FILES["file_data"]["size"],    // file size
						'zoomData' => $img_directory, // separate larger zoom data
					]
				],
				'append' => true
			];
		} else {
			$log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_ERR']);
			$message["error"] = "Resim yüklenirken hata oluştuştu.";
		}
	}
} else {
	$message = [];
	$message["error"] = "Dosya seçilmedi.";
}
echo json_encode($message);
exit;