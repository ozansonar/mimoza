<?php
if (isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])) {
    //silme yetkisi kontrol
    if ($session->sessionRoleControl('user', $constants::deletePermissionKey) === false) {
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::deletePermissionKey);
        $message['reply'][] = 'Silme yetkiniz bulunmuyor.';
    }
    $delId = $functions->cleanGetInt("delete");
    if ((int)$_SESSION["user_id"] === (int)$delId) {
        $message['reply'][] = 'Kendinizi silemezsiniz!';
    }
    if(empty($message)){

        $delete = $db::update('users',['deleted'=>1],['id'=>$delId]);
        $message = [];
        if ($delete) {
            //log atalım
            $log->this('USER_DELETE_SUCC');
            $message["success"][] = $lang["content-delete"];
        } else {
            //log atalım
            $log->this('USER_DELETE_ERR');
            $message["reply"][] = $lang["content-delete-error"];
        }
    }
}
