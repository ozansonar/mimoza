<?php
use Includes\System\View;

$log->logThis($log->logTypes["PAGE"], $pageData->id);

//bu sayfadakullanılan özel css'ler
$customCss = [];

//bu sayfadakullanılan özel js'ler
$customJs = [];

$metaTag->title = $pageData->title;
if(!empty($pageData->keywords)){
    $metaTag->keywords = $pageData->keywords;
}
if(!empty($pageData->description)){
    $metaTag->description = $pageData->description;
}
//$pageData ana indexten geliyor
View::layout('page',[
    'page_data' => $pageData
]);