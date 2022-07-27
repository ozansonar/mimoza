<?php

use Mrt\MimozaCore\View;

$log->logThis($log->logTypes["CONTACT"]);
$customCss = ["plugins/form-validation-engine/css/validationEngine.jquery.css"];
$customJs = [
	"dist/js/sweetalert2.all.min.js",
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-" . $_SESSION["lang"] . ".js",
];

$metaTag->title = $functions->textManager("contact_title");


View::layout('contact', [
	'title' => 'İletişim',
	'customCss' => $customCss,
	'customJs' => $customJs
]);