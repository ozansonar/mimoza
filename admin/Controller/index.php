<?php

use OS\MimozaCore\View;

View::backend('index',[
	'title' => 'Ana Sayfa',
	'pageRoleKey' => 'index',
	'pageAddRoleKey' => 'index',
	'pageButtonRedirectLink' => 'index',
	'pageButtonRedirectText' => 'Yeni Ekle',
	'pageButtonIcon' => 'fas fa-plus-square',
]);