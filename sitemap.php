<?php
use Includes\SiteMap\SiteMap;

include "includes/Init.php";
include "includes/Project/SiteMap.php";
header('Content-type: application/xml');

$siteMap = new SiteMap($db,$system->url(),$langData,$siteManager->defaultLanguage()->short_lang);


echo $siteMap->generate();

?>