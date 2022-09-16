<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "gallery-image-upload";

if ($session->sessionRoleControl($pageRoleKey, $constants::addPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
	$session->permissionDenied();
}

$customCss = [
	"plugins/bootstrap-fileinput-master/css/fileinput.css",
	"plugins/bootstrap-fileinput-master/themes/explorer-fas/theme.css",
];
$customJs = [
	"plugins/bootstrap-fileinput-master/js/plugins/piexif.js",
	"plugins/bootstrap-fileinput-master/js/plugins/sortable.js",
	"plugins/bootstrap-fileinput-master/js/fileinput.js",
	"plugins/bootstrap-fileinput-master/js/locales/tr.js",
	"plugins/bootstrap-fileinput-master/themes/explorer-fas/theme.js",
];
$id = 0;

if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
	$del_id = $functions->cleanPostInt("id");
	$data = [];
	$data["deleted"] = 1;
	$delete = $db::update("gallery_image", $data, ["id" => $del_id]);
	$message = [];
	if ($delete) {
		$log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_DELETE_SUCC'], "silinen row id:" . $del_id);
		$message = ['append' => true];
	} else {
		$log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_DELETE_ERR'], "silinemeyen row id:" . $del_id);
		$message = ['error' => "Resim silinemedi tekrar deneyiniz."];
	}
	echo json_encode($message);
	exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("gallery", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}

	$log->logThis($log->logTypes['GALLERY_IMAGE_UPLOAD_DETAIL']);
	echo $functions->csrfToken();

	//galeriye ait resimleri çekeklim
	$images = $db::$db->prepare("SELECT * FROM gallery_image WHERE gallery_id=:g_id AND status=1 AND deleted=0 ORDER BY id ASC");
	$images->bindParam(":g_id", $id, PDO::PARAM_INT);
	$images->execute();
	$images_count = $images->rowCount();

	$initialPreview = "";
	$initialPreviewConfig = "";

	if ($images_count > 0) {
		$images_result = $images->fetchAll(PDO::FETCH_OBJ);
		foreach ($images_result as $images_row) {
			$image_path = $constants::fileTypePath["gallery"]["full_path"] . $id . "/" . $images_row->image;
			if (!empty($images_row->image) && file_exists($image_path)) {
				$file_size = filesize($image_path);
				$initialPreview .= '"' . $constants::fileTypePath["gallery"]["url"] . $id . "/" . $images_row->image . '",';
				$initialPreviewConfig .= '{caption: "' . $images_row->image . '", size: ' . $file_size . ', width: "200px", url: "gallery-image-upload", key: ' . $images_row->id . ', extra: {token: "' . $_SESSION["csrf_token"] . '", id : "' . $images_row->id . '"}  },';
			}
		}
	}
}

View::backend('gallery-image-upload', [
	'title' => "Galeri Resim " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "gallery",
	'pageButtonRedirectText' => "Resim Galerileri",
	'pageButtonIcon' => "fas fa-th-list",
	'initialPreview' => $initialPreview ?? NULL,
	'initialPreviewConfig' => $initialPreviewConfig ?? NULL,
	'css' => $customCss,
	'js' => $customJs,
]);