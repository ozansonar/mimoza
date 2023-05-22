<?php
if (isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])) {
    //silme yetkisi kontrol
    if ($session->sessionRoleControl('content-categories', $constants::deletePermissionKey) === false) {
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::deletePermissionKey);
        $message['reply'][] = 'Silme yetkiniz bulunmuyor.';
    }

    if(empty($message)){
        $delId = $functions->cleanGetInt("delete");
        $delete = $siteManager->multipleLanguageDataDelete("content_categories", $delId);
        $message = [];
        if ($delete) {
            //log atalım
            $log->logThis($log->logTypes['CONTENT_CATEGORIES_DEL_SUCC']);
            $message["success"][] = $lang["content-delete"];
        } else {
            //log atalım
            $log->logThis($log->logTypes['CONTENT_CATEGORIES_DEL_ERR']);
            $message["reply"][] = $lang["content-delete-error"];
        }
    }
}
