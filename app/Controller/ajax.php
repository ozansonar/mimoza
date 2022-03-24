<?php
/**
 * Created by PhpStorm.
 * User: Ozan PC
 * Date: 27.11.2020
 * Time: 10:25
 * Email: ozansonar1@gmail.com
 */
$log->logThis($log->logTypes["AJAX_REQUEST"]);
$message = [];
$type = $system->route(1);
if (!$type) {
    exit;
}

$customFileUrl = $siteManager->customFileUrl($type);

if (!empty($customFileUrl)) {
    include $system->controller('ajax/' . $customFileUrl->controller);
}else if(file_exists($system->controller('ajax/' . $type))){
    include $system->controller('ajax/' . $type);
}

try {
    echo json_encode($message, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    $log->logThis($log->logTypes["AJAX_REQUEST_ERROR"],"ajax cevap hatası:".$e->getMessage());
}