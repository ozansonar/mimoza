<?php
/**
 * Created by PhpStorm.
 * User: ozan
 * Date: 24.02.2019
 * Time: 16:14
 */
//log atalım
$log->logThis($log->logTypes['ADMIN_LOGOUT'],"user id: ".$_SESSION["user_id"]);
session_destroy();
$functions->redirect(SITE_URL);
exit;