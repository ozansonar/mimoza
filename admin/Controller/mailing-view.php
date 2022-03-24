<?php
//sayfanın izin keyi
$page_role_key = "mailler";
$page_add_role_key = "mail-settings";

//sayfada işlem yapılacak table
$table = "mailing";
$table_2 = "mailing_user";

if ($session->sessionRoleControl($page_role_key, $detailPermissionKey) == false) {
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $page_role_key . " permissions => " . $listPermissionKey);
    $session->permissionDenied();
}

//log atalım
$log->logThis($log->logTypes['MAILLER_DETAIL']);

$id = $functions->clean_get_int("id");
$mailing = $db::selectQuery($table, array(
    "id" => $id,
    "deleted" => 0,
),true);
if(empty($mailing)){
    $functions->redirect($adminSystem->adminUrl("mailler"));
}
if(!empty($mailing->image)){
    $image = unserialize($mailing->image);
    foreach ($image as $m_key=>$m_value){
        $mailing->text = str_replace("cid:image_".$m_key,$fileTypePath["mailing"]["url"].$m_value,$mailing->text);
    }
}

if(!empty($mailing->attachment)){
    $attachment_array = array();
    $attachment = unserialize($mailing->attachment);
    foreach ($attachment as $attachment_key=>$attachment_row){
        $at_name = $functions->cleaner($attachment_row);
        if(file_exists($fileTypePath["mailing_attachment"]["full_path"].$at_name)){
            $attachment_array[$attachment_key]["url"] = $fileTypePath["mailing_attachment"]["url"].$at_name;
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
$page_button_redirect_link = $page_role_key;
$page_button_redirect_text = "Mail Listesi";
$page_button_icon = "fas fa-plus-square";

require $adminSystem->adminView('mailing-view');