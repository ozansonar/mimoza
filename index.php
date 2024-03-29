<?php

include(__DIR__ . "/includes/Init.php");

$routeExplode = explode('?', $_SERVER['REQUEST_URI']);
$route = array_values(array_filter(explode('/', $routeExplode[0])));
if (SUBFOLDER_NAME !== '/') {
	$sub_folder_explode = explode("/", SUBFOLDER_NAME);
	foreach ($sub_folder_explode as $sub_exp) {
		if (!empty($sub_exp)) {
			array_shift($route);
		}
	}
}
$projectLanguages = [];
$linkKeyArray = [1,3];
if (!is_null($langData)) {
    foreach ($langData as $langDataRow) {
        $projectLanguages[$langDataRow->short_lang] = $langDataRow;
    }
    $_SESSION["lang"] = !empty($system->route(0)) && array_key_exists($system->route(0),$projectLanguages)
        ? $functions->cleaner($system->route(0))
        : $functions->cleaner($siteDefaultLanguages->short_lang);

    if((int)$settings->link_sort_lang === 3 && $_SESSION["lang"] === $siteDefaultLanguages->short_lang){
        //buraso kalsın lazım olabilir
    }else{
        if (in_array($settings->link_sort_lang, $linkKeyArray) || $_SESSION["lang"] !== $siteDefaultLanguages->short_lang) {
            //adres çubuğunda dil kısaltması olacak örnek site.com/tr/hakkimizda
            define("MULTIPLE_LANGUAGE", 1);
        }
        if ((int)$settings->link_sort_lang === 1
            && $system->route(0) !== "admin"
            && !empty($system->route(0))
            && !array_key_exists($system->route(0), $projectLanguages)) {
            //linklerde dil kısaltması olması gerek yoksa yönlendir
            $_SESSION["lang"] = $functions->cleaner($siteDefaultLanguages->short_lang);
            $system->abort(404);
        }
    }

    if (!empty($system->route(0)) && $system->route(0) !== "admin" && in_array($settings->link_sort_lang, $linkKeyArray) && array_key_exists($system->route(0),$projectLanguages)) {
        //linklerde dil kısaltması isteniyor site.com/tr/hakkimizda gibi olacak biz $route den tr yi kaldıracağız sistem işlemeye devam edecek
        array_shift($route);
    } else {
        $_SESSION["lang"] = $functions->cleaner($siteManager->defaultLanguage()->short_lang);
    }
}

// Locale-Zaman Ayarlaması
setlocale(LC_TIME, $projectLanguages[$_SESSION['lang']]->lang_iso);
setlocale(LC_ALL, $projectLanguages[$_SESSION['lang']]->lang_iso.'.utf8');
date_default_timezone_set($projectLanguages[$_SESSION['lang']]->timezone);


//html purifier xss vb attackları engellemek için kullandığımız library
$purifierConfig = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($purifierConfig);
foreach ($_GET as $getkey => $getvalue) {
    if (!is_array($getvalue)) {
        $_GET[$getkey] = $purifier->purify($getvalue);
    }

}

foreach ($_POST as $postKey => $postValue) {
	if (!is_array($postValue) && !in_array($postKey, $allowedSpecialHtmlPost, true)) {
		$postValue = $purifier->purify($postValue);
        $postValue = filter_var($postValue, FILTER_UNSAFE_RAW);
		$_POST[$postKey] = $postValue;
	}
}

if (!$system->route(0)) {
	$route[0] = 'index';
}

// istediğimiz linki istediğimiz sayfaya bu şekilde yönlendirebiliyoruz
$customFileUrl = $siteManager->customFileUrl($system->route(0));
if (!empty($customFileUrl)) {
	$route[0] = $customFileUrl->controller;
}

if (!file_exists($system->controller($system->route(0)))) {
	$pageData = $siteManager->pageControl($functions->cleaner($system->route(0)));
	if (!empty($pageData)) {
		$route[0] = 'page';
	} else {
		// Herhangi bir data çekilmediyse
		$route[0] = '404';
	}
}

// Under construction mode check
if ((int)$settings->site_status === 1 && $system->route(0) !== 'admin') {
	$route[0] = 'maintaining-mode';
}

$loggedUser = null;
// sitenin her yerinde kullanılan işlemleri her yerde ayrı yapmaktansa burda değişkene atayıp burdan yapabilirz
if ($session->isThereUserSession()) {
	//işlemler yaparken her yerde aryı ayrgı fonksiyonu çalıştırmaktansa her defasında burda çalışsınve bu değişkenden kullanalım
	$loggedUser = $session->getUserInfo();
}
//else if ($system->route(0) !== "giris") {
//	$functions->redirect($system->url("giris"));
//}

include($system->path('app/Controller/' . strtolower($system->route(0)) . ".php"));
ob_end_flush();