<?php

$message = [];
$type = $system->route(2);
if (!$type) {
    exit;
}

if (file_exists($adminSystem->adminController('ajax/' . $type))) {
    require $adminSystem->adminController('ajax/' . $type);
}

echo json_encode($message);