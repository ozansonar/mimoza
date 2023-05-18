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
//diğer diller için ayarlanıyor
foreach ($projectLanguages as $rowLang){
    $getLangPrefix = $siteManager->getPrefix('content',$rowLang->short_lang);
    if(empty($getLangPrefix)){
        continue;
    }
    $otherLanguageContent[$rowLang->short_lang] = $system->urlWithoutLanguage($rowLang->short_lang.'/'.$siteManager->getPrefix('iletisim',$rowLang->short_lang));
}

$metaTag->title = $functions->textManager("contact_title");


View::layout('contact', [
	'title' => 'İletişim',
	'customCss' => $customCss,
	'customJs' => $customJs
]);