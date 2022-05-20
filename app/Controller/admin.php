<?php

if(!$session->isThereAdminSession()){
    $route[1] = 'login';
}
if (!$system->route(1)) {
    $route[1] = 'index';
}
if (!file_exists($system->adminController($system->route(1)))){
    $route[1] = '404';
}
$userHeaderTopImg = $system->adminPublicUrl("dist/img/user2-160x160.jpg");
if(!empty($loggedUser->img) && file_exists($system->path("uploads/user/".$loggedUser->img))){
    $userHeaderTopImg = $system->url("uploads/user/".$loggedUser->img);
}

include $system->adminController($system->route(1));