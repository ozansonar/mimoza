<?php
use Mrt\MimozaCore\View;

$log->logThis($log->logTypes["CONTACT"]);

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";

//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "dist/js/sweetalert2.all.min.js";
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-".$_SESSION["lang"].".js";

$metaTag->title = $functions->textManager("contact_title");

include($system->path("includes/System/Form.php"));
$form = new Includes\System\Form();

View::layout('contact',[
    'customCss' => $customCss,
    'customJs' => $customJs
]);