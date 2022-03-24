<?php

$page_role_key = "user-tracing";
if ($session->sessionRoleControl($page_role_key, $listPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$editPermissionKey);
    $session->permissionDenied();
}

//log atalım
$log->logThis($log->logTypes['USER_TRACING']);

$id = 0;
if(isset($_GET["id"])){
    $id = $functions->clean_get_int("id");
    $selectQuery = $db::selectQuery("users",array(
        "id" => $id,
        "deleted" => 0,
    ),true);
    if(empty($selectQuery)){
        $functions->redirect($adminSystem->adminUrl());
    }
    $logs_get = $db::query("SELECT l.*,lt.log_key FROM logs l INNER JOIN log_types lt ON lt.log_val=l.log_type WHERE user_id=:uid ORDER BY l.id DESC");
    $logs_get->bindParam(":uid",$id,PDO::PARAM_INT);
    $logs_get->execute();
    $logs_count = $logs_get->rowCount();
    $log_array = array();
    if($logs_count > 0){
        $logs_data = $logs_get->fetchAll(PDO::FETCH_OBJ);
        foreach ($logs_data as $logs_row){
            $date = date('Y-m-d', strtotime($logs_row->log_datetime));
            $log_array[$date][] = $logs_row;
        }
    }
}

//sayfa başlıkları
$page_title = "Kullanıcı Hareketleri";
$sub_title = $selectQuery->name." ".$selectQuery->surname." isimli kullanıcının sistemimizdeki hareketleri.";
//butonun gideceği link ve yazısı
$page_button_redirect_link = "user";
$page_button_redirect_text = "Kullancılar";
$page_button_icon = "icon-list";
require $adminSystem->adminView('user-tracing');