<?php

use Mrt\MimozaCore\View;

if ((int)$settings->site_status !== 1) {
	$functions->redirect($system->url());
}
$log->logThis($log->logTypes["SITE_BAKIMDA"]);
$metaTag->title = $functions->textManager("site_bakimda_baslik");

View::layout('maintaining-mode');