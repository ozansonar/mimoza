<?php

$metaTag->title = "Yetkiniz yok izinsiz erişim.";
//sayfa başlıkları
$page_title = "Yetkiniz Yok";
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "user";
$page_button_redirect_text = "Kullanicilar";
$page_button_icon = "icon-list";
require $adminSystem->adminView('403');