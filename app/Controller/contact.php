<?php

use OS\MimozaCore\View;

$log->logThis($log->logTypes["CONTACT"]);
$customCss = [
    "plugins/sweetalert2/sweetalert2.min.css",
    "plugins/form-validation-engine/css/validationEngine.jquery.css"
];
$customJs = [
	"plugins/sweetalert2/sweetalert2.min.js",
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-" . $_SESSION["lang"] . ".js",
];

$metaTag->title = $functions->textManager("contact_title");


View::layout('contact', [
	'title' => 'İletişim',
	'customCss' => $customCss,
	'customJs' => $customJs
]);