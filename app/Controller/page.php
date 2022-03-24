<?php
use Includes\System\View;

$log->logThis($log->logTypes["PAGE"], $page_data->id);

//bu sayfadakullanılan özel css'ler
$customCss = [];

//bu sayfadakullanılan özel js'ler
$customJs = [];

$metaTag->title = $page_data->title;
if(!empty($page_data->keywords)){
    $metaTag->keywords = $page_data->keywords;
}
if(!empty($page_data->description)){
    $metaTag->description = $page_data->description;
}
//$page_data ana indexten geliyor
View::layout('page',[
    'page_data' => $page_data
]);