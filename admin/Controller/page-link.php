<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "page-link";
$pageAddRoleKey = "page-link-settings";

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

if(isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])){
    if($session->sessionRoleControl($pageRoleKey,$constants::deletePermissionKey) === false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$pageRoleKey." permissions => ".$constants::deletePermissionKey);
        $session->permissionDenied();
    }

    $del_id = $functions->cleanGetInt("delete");
    $data = [];
    $data["deleted"] = 1;
    $delete = $db::update("file_url",$data,array("id"=>$del_id));

    $message = [];
    if($delete){
        $log->logThis($log->logTypes['PAGE_LINK_DELETE_SUCC']);
        $message["success"][] = $lang["content-delete"];
        $refresh_time = 3;
        $message["refresh_time"] = $refresh_time;
        $functions->refresh($system->adminUrl("page-link"),$refresh_time);
    }else{
        $log->logThis($log->logTypes['PAGE_LINK_DELETE_ERR']);
        $message["reply"][] = $lang["content-delete-error"] ;
    }
}

$data = $db::selectQuery("file_url",array(
    "deleted" => 0,
));

View::backend('page-link',[
	'title' => 'Sayfa Linkleri',
	'content' => $data,
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageButtonRedirectLink' => "page-link-settings",
	'pageButtonRedirectText' => "Yeni Ekle",
	'pageButtonIcon' => "fas fa-plus-square",
	'css' =>$customCss,
	'js' =>$customJs,
]);