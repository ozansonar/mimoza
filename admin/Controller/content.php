<?php
//sayfanın izin keyi
use Mrt\MimozaCore\View;

$pageRoleKey = "content";
$pageAddRoleKey = "content-settings";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if ($session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::listPermissionKey);
	$session->permissionDenied();
}

$log->logThis($log->logTypes['CONTENT_LIST']);
$customCss = [
	"plugins/datatables-bs4/css/dataTables.bootstrap4.min.css",
	"plugins/datatables-responsive/css/responsive.bootstrap4.min.css",
	"plugins/datatables-buttons/css/buttons.bootstrap4.min.css",
];
$customJs = [
	"plugins/datatables/jquery.dataTables.min.js",
	"plugins/datatables-bs4/js/dataTables.bootstrap4.min.js",
	"plugins/datatables-responsive/js/dataTables.responsive.min.js",
	"plugins/datatables-responsive/js/responsive.bootstrap4.min.js",
	"plugins/datatables-buttons/js/dataTables.buttons.min.js",
	"plugins/datatables-buttons/js/buttons.bootstrap4.min.js",
	"plugins/jszip/jszip.min.js",
	"plugins/pdfmake/pdfmake.min.js",
	"plugins/pdfmake/vfs_fonts.js",
	"plugins/datatables-buttons/js/buttons.html5.min.js",
	"plugins/datatables-buttons/js/buttons.print.min.js",
	"plugins/datatables-buttons/js/buttons.colVis.min.js",
];

if (isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])) {
	//silme yetkisi kontrol
	if ($session->sessionRoleControl($data->pageRoleKey, $constants::deletePermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::deletePermissionKey);
		$session->permissionDenied();
	}
	$del_id = $functions->cleanGetInt("delete");
	$delete = $siteManager->multipleLanguageDataDelete("content", $del_id);
	$message = [];
	if ($delete) {
		//log atalım
		$log->logThis($log->logTypes['CONTENT_DEL_SUCC']);

		$message["success"][] = $lang["content-delete"];
		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		$functions->refresh($system->adminUrl("content"), $refresh_time);
	} else {
		//log atalım
		$log->logThis($log->logTypes['CONTENT_DEL_ERR']);
		$message["reply"][] = $lang["content-delete-error"];
	}
}
$data = $db::selectQuery("content", array(
	"lang" => $siteManager->defaultLanguage()->short_lang,
	"deleted" => 0,
));

$selectQuery = $db::selectQuery("content_categories", array(
	"lang" => $siteManager->defaultLanguage()->short_lang,
	"deleted" => 0,
));
$cats = [];
foreach ($selectQuery as $key => $value) {
	$cats[$value->id] = $value->title;
}

View::backend('content',[
	'title' => 'İçerikler',
	'pageButtonRedirectLink' => "content-settings",
	'pageButtonRedirectText' => "Yeni Ekle",
	'pageButtonIcon' => "fas fa-plus-square",
	'pageRoleKey' =>$pageRoleKey,
	'pageAddRoleKey' =>$pageAddRoleKey,
	'content' =>$data,
]);