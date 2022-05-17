<?php

//sayfanın izin keyi
$page_role_key = "page-link";
$page_add_role_key = "page-link-settings";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if($session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$listPermissionKey);
    $session->permissionDenied();
}
//log atalım
$log->logThis($log->logTypes['PAGE_LINK_LIST']);

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/datatables-bs4/css/dataTables.bootstrap4.min.css";
$customCss[] = "plugins/datatables-responsive/css/responsive.bootstrap4.min.css";
$customCss[] = "plugins/datatables-buttons/css/buttons.bootstrap4.min.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/datatables/jquery.dataTables.min.js";
$customJs[] = "plugins/datatables-bs4/js/dataTables.bootstrap4.min.js";
$customJs[] = "plugins/datatables-responsive/js/dataTables.responsive.min.js";
$customJs[] = "plugins/datatables-responsive/js/responsive.bootstrap4.min.js";
$customJs[] = "plugins/datatables-buttons/js/dataTables.buttons.min.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.bootstrap4.min.js";
$customJs[] = "plugins/jszip/jszip.min.js";
$customJs[] = "plugins/pdfmake/pdfmake.min.js";
$customJs[] = "plugins/pdfmake/vfs_fonts.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.html5.min.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.print.min.js";
$customJs[] = "plugins/datatables-buttons/js/buttons.colVis.min.js";


if(isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])){
    //silme yetkisi kontrol
    if($session->sessionRoleControl($page_role_key,$deletePermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$deletePermissionKey);
        $session->permissionDenied();
    }

    $del_id = $functions->clean_get_int("delete");
    $data = array();
    $data["deleted"] = 1;
    $delete = $db::update("file_url",$data,array("id"=>$del_id));

    $message = array();
    if($delete){
        //log atalım
        $log->logThis($log->logTypes['PAGE_LINK_DELETE_SUCC']);

        $message["success"][] = $lang["content-delete"];
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        $functions->refresh($system->adminUrl("page-link"),$refresh_time);
    }else{
        //log atalım
        $log->logThis($log->logTypes['PAGE_LINK_DELETE_ERR']);

        $message["reply"][] = $lang["content-delete-error"] ;
    }
}
$data = $db::selectQuery("file_url",array(
    "deleted" => 0,
));

//sayfa başlıkları
$page_title = "Sayfa Linkleri";
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "page-link-settings";
$page_button_redirect_text = "Yeni Ekle";
$page_button_icon = "fas fa-plus-square";

require $system->adminView('page-link');