<?php

use Includes\System\View;

if ((int)$settings->site_status !== 1) {
	$functions->redirect($functions->site_url_lang());
}
$log->logThis($log->logTypes["SITE_BAKIMDA"]);
$metaTag->title = $functions->textManager("site_bakimda_baslik");

View::layout('maintaining-mode');