<?php
if (isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])) {
    //silme yetkisi kontrol
    if ($session->sessionRoleControl('email-themes', $constants::deletePermissionKey) === false) {
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::deletePermissionKey);
        $message['reply'][] = 'Silme yetkiniz bulunmuyor.';
    }

    if(empty($message)){
        $delId = $functions->cleanGetInt("delete");
        $delete = $siteManager->multipleLanguageDataDelete("email_template", $delId);
        $message = [];
        if ($delete) {
            //log atalım
            $log->logThis($log->logTypes['EMAIL_TEMALARI_DEL_SUCC']);
            $message["success"][] = $lang["content-delete"];
        } else {
            //log atalım
            $log->logThis($log->logTypes['EMAIL_TEMALARI_DEL_ERR']);
            $message["reply"][] = $lang["content-delete-error"];
        }
    }
}
