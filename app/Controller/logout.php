<?php

$log->logThis($log->logTypes["USER_LOGOUT"]);
session_destroy();
$functions->redirect($system->url());
exit;