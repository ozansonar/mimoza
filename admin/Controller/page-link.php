<?php

use OS\MimozaCore\View;

$pageRoleKey = "page-link";
$pageAddRoleKey = "page-link-settings";
$pageTable = 'file_url';

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if($session->sessionRoleControl($pageRoleKey,$constants::listPermissionKey) === false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$pageRoleKey." permissions => ".$constants::listPermissionKey);
    $session->permissionDenied();
}

$log->logThis($log->logTypes['PAGE_LINK_LIST']);
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


View::backend($pageRoleKey,[
	'title' => 'Sayfa Linkleri',
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageButtonRedirectLink' => $pageAddRoleKey,
	'pageButtonRedirectText' => "Yeni Ekle",
	'pageButtonIcon' => "fas fa-plus-square",
	'css' =>$customCss,
	'js' =>$customJs,
]);