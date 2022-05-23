<?php
//sayfanın izin keyi
$data->pageRoleKey = "page";
$pageAddRoleKey = "page-settings";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if($session->sessionRoleControl($data->pageRoleKey,$constants::listPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$data->pageRoleKey." permissions => ".$constants::listPermissionKey);
    $session->permissionDenied();
}
//log atalım
$log->logThis($log->logTypes['PAGE_LIST']);

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
    if($session->sessionRoleControl($data->pageRoleKey,$constants::deletePermissionKey) == false){
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$data->pageRoleKey." permissions => ".$constants::deletePermissionKey);
        $session->permissionDenied();
    }

    $del_id = $functions->cleanGetInt("delete");
    $delete = $siteManager->multipleLanguageDataDelete("page",$del_id);
    $message = array();
    if($delete){
        //log atalım
        $log->logThis($log->logTypes['PAGE_LIST_DELETE_SUCC']);

        $message["success"][] = $lang["content-delete"];
        $refresh_time = 5;
        $message["refresh_time"] = $refresh_time;
        $functions->refresh($system->adminUrl("page"),$refresh_time);
    }else{
        //log atalım
        $log->logThis($log->logTypes['PAGE_LIST_DELETE_ERR']);

        $message["reply"][] = $lang["content-delete-error"] ;
    }
}
$data = $db::selectQuery("page",array(
    "lang" => $siteManager->defaultLanguage()->short_lang,
    "deleted" => 0,
));

//sayfa başlıkları
$page_title = "Sayfalar";
$sub_title = "Bu sayfada ki içerikler diğer içeriklerden farklıdır burda olan içerikler siteadi.com/icerik-link olarak erişebilir \"İçerik işlemleri\" sayfasında olan içerikler ise siteadi.com/icerik-kategorisi/icerik-linki-icerik-id olarak gözükür.";
//butonun gideceği link ve yazısı
$data->pageButtonRedirectLink = "page-settings";
$data->pageButtonRedirectText = "Yeni Ekle";
$data->pageButtonIcon = "fas fa-plus-square";

require $system->adminView('page');