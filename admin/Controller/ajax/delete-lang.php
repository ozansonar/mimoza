<?php
if (isset($_GET["delete"]) && !empty($_GET["delete"]) && is_numeric($_GET["delete"])) {
    //silme yetkisi kontrol
    if ($session->sessionRoleControl('lang', $constants::deletePermissionKey) === false) {
        $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::deletePermissionKey);
        $message['reply'][] = 'Silme yetkiniz bulunmuyor.';
    }
    $delId = $functions->cleanGetInt("delete");
    $lang_control = $siteManager->getDefaultLangNotId($delId);
    if ($lang_control === false) {
        $message["reply"][] = "Bu dilden başka varsayılan bir dil yok başka bir dili varsayılan yapıp bu dili öyle silebilirsiniz.";
    }
    if(empty($message)){
        $delete = $db::update('lang',['deleted'=>1],['id'=>$delId]);
        $message = [];
        if ($delete) {
            //log atalım
            $log->this('LANG_DEL_SUCC');
            $message["success"][] = $lang["content-delete"];
        } else {
            //log atalım
            $log->this('LANG_DEL_ERR');
            $message["reply"][] = $lang["content-delete-error"];
        }
    }
}
