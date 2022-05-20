<?php

include_once ROOT_PATH . '/includes/Project/Helpers.php';
//sayfanın izin keyi
use Mrt\MimozaCore\View;

$page_role_key = "index";
$page_add_role_key = "index";

//sayfa başlıkları
$page_title = "Anasayfa";
$sub_title = null;

//butonun gideceği link ve yazısı
$page_button_redirect_link = "index";
$page_button_redirect_text = "Yeni Ekle";
$page_button_icon = "fas fa-plus-square";

View::backend('index',[
	'title' => __('Home'),
	'sub_title' => '',

]);