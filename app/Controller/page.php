<?php
use Mrt\MimozaCore\View;

$log->logThis($log->logTypes["PAGE"], $pageData->id);

//bu sayfadakullanılan özel css'ler
$customCss = [
    "plugins/fancybox/jquery.fancybox.min.css"
];

//bu sayfadakullanılan özel js'ler
$customJs = [
    "plugins/fancybox/jquery.fancybox.min.js"
];

$metaTag->title = $pageData->title;
if(!empty($pageData->keywords)){
    $metaTag->keywords = $pageData->keywords;
}
if(!empty($pageData->description)){
    $metaTag->description = $pageData->description;
}
//$pageData ana indexten geliyor
View::layout('page',[
    'page_data' => $pageData,
    "customCss" => $customCss,
    "customJs" => $customJs,
]);