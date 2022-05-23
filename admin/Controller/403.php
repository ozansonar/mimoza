<?php

$metaTag->title = "Yetkiniz yok izinsiz erişim.";
//sayfa başlıkları
$page_title = "Yetkiniz Yok";
$sub_title = null;
//butonun gideceği link ve yazısı
$data->pageButtonRedirectLink = "user";
$data->pageButtonRedirectText = "Kullanicilar";
$data->pageButtonIcon = "icon-list";
require $system->adminView('403');