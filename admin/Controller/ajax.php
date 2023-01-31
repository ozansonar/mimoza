<?php

$message = [];
$type = $system->route(2);
if (!$type) {
    exit;
}

if (file_exists($system->adminController('ajax/' . $type))) {
    require $system->adminController('ajax/' . $type);
}

//eğer server-side isteği yoksa planlandığı gibi devam etsin
if(strpos($type, "server-side") === false){
    echo json_encode($message, JSON_THROW_ON_ERROR);
}