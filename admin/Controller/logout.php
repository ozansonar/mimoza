<?php
$log->logThis($log->logTypes['ADMIN_LOGOUT'],"user id: ".$_SESSION["user_id"]);
session_destroy();
$functions->redirect(SITE_URL);
exit;