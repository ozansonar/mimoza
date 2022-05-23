<?php
//sayfanın izin keyi
$data->pageRoleKey = "mailler";
$page_add_role_key = "mail-settings";

//sayfada işlem yapılacak table
$table = "mailing";
$table_2 = "mailing_user";

if ($session->sessionRoleControl($data->pageRoleKey, $detailPermissionKey) == false) {
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $data->pageRoleKey . " permissions => " . $constants::listPermissionKey);
    $session->permissionDenied();
}

//log atalım
$log->logThis($log->logTypes['MAILLER_DETAIL']);

$id = $functions->cleanGetInt("id");
$mailing = $db::selectQuery($table, array(
    "id" => $id,
    "deleted" => 0,
),true);
if(empty($mailing)){
    $functions->redirect($system->adminUrl("mailler"));
}
if(!empty($mailing->image)){
    $image = unserialize($mailing->image);
    foreach ($image as $m_key=>$m_value){
        $mailing->text = str_replace("cid:image_".$m_key,$constants::fileTypePath["mailing"]["url"].$m_value,$mailing->text);
    }
}

if(!empty($mailing->attachment)){
    $attachment_array = array();
    $attachment = unserialize($mailing->attachment);
    foreach ($attachment as $attachment_key=>$attachment_row){
        $at_name = $functions->cleaner($attachment_row);
        if(file_exists($constants::fileTypePath["mailing_attachment"]["full_path"].$at_name)){
            $attachment_array[$attachment_key]["url"] = $constants::fileTypePath["mailing_attachment"]["url"].$at_name;
            $attachment_array[$attachment_key]["name"] = $at_name;
        }
    }
}

//mailig alıcı listesi
$mailing_user = $db::selectQuery($table_2, array(
    "mailing_id" => $mailing->id,
    "deleted" => 0,
));
$mailing_user_list = array();
foreach ($mailing_user as $user_row){
    $mailing_user_list[$user_row->send][$user_row->id] = $user_row;
}
//sayfa başlıkları
$page_title = "Mail Detayları";
$sub_title = null;
//butonun gideceği link ve yazısı
$data->pageButtonRedirectLink = $data->pageRoleKey;
$data->pageButtonRedirectText = "Mail Listesi";
$data->pageButtonIcon = "fas fa-plus-square";

require $system->adminView('mailing-view');