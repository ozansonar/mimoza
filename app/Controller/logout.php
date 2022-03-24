<?php

$log->logThis($log->logTypes["USER_LOGOUT"]);
session_destroy();
$functions->redirect($functions->site_url());
exit;