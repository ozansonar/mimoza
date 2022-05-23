<?php

use Mrt\MimozaCore\View;

include_once ROOT_PATH . '/includes/Project/Helpers.php';


View::backend('index',[
	'title' => __('Home'),
	'pageRoleKey' => 'index',
	'pageAddRoleKey' => 'index',
	'pageButtonRedirectLink' => 'index',
	'pageButtonRedirectText' => 'Yeni Ekle',
	'pageButtonIcon' => 'fas fa-plus-square',
]);