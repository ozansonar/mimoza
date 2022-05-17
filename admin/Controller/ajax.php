<?php

$message = [];
$type = $system->route(2);
if (!$type) {
    exit;
}

if (file_exists($system->adminController('ajax/' . $type))) {
    require $system->adminController('ajax/' . $type);
}

echo json_encode($message);