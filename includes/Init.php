<?php

use OS\MimozaCore\Functions;
use OS\MimozaCore\Database;
use OS\MimozaCore\Session;
use OS\MimozaCore\SiteManager;
use OS\MimozaCore\Core;
use OS\MimozaCore\Log;
use Includes\Project\Constants;

// Debug sınıfını kullanmayacaksanız kaldırabilirsiniz.
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;


// Includes all
include(__DIR__ . "/Config.php");
include(__DIR__ . "/Project/Constants.php");
include(__DIR__ . "/Project/Functions.php");
include(__DIR__ . "/Statics/AdminText.php");
include(__DIR__ . "/Statics/Language.php");

//Composer autoload
require dirname(__DIR__) . "/vendor/autoload.php";

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$functions = new Functions();
$session = new Session($db);
$siteManager = new SiteManager($db);
$system = new Core();
$log = new Log($db);
$constants = new Constants();
$projectFunctions = new \Includes\Project\Functions($db);

// If DEBUG_MOD is true, errors will show with whoops package
if (DEBUG_MODE) {
	$whoops = new Run;
	$whoops->pushHandler(new PrettyPageHandler);
	$whoops->register();
}

// Site languages
$langData = $db::selectQuery("lang", array(
	"status" => 1,
	"deleted" => 0,
));
//site default languages
$siteDefaultLanguages = $siteManager->defaultLanguage();


// Html purify exception array
$allowedSpecialHtmlPost = ["text", "adres", "contact_despription"];
//çoklu dildeki keyleri bu şekilde ekleyelim
foreach ($langData as $langRow) {
    $allowedSpecialHtmlPost[] = "text_" . $langRow->short_lang;
    $allowedSpecialHtmlPost[] = "abstract_" . $langRow->short_lang;
    $allowedSpecialHtmlPost[] = "site_bakimda_aciklama_" . $langRow->short_lang;
    $allowedSpecialHtmlPost[] = "404_page_text_" . $langRow->short_lang;
	for ($i = 1; $i <= 10; $i++) {
		$allowedSpecialHtmlPost[] = "bilgilendirme_alani_tab_text_" . $i . "_" . $langRow->short_lang;
	}
}

// Getting site settings from database
$settingQuery = $db::selectQuery("settings");
$settings = [];
$textManager = [];

foreach ($settingQuery as $settingRow) {
	if (!empty($settingRow->lang)) {
		$textManager[$settingRow->lang][$settingRow->name] = $settingRow->val;
	} else {
		$settings[$settingRow->name] = $settingRow->val;
	}
}
$settings = (object)$settings;
$textManager = (object)$textManager;

$metaTag = [
	"description" => $settings->description,
	"keywords" => $settings->keywords,
	"title" => $settings->title,
];

$metaTag = (object)$metaTag;

if (isset($settings->project_image)
	&& !empty($settings->project_image)
	&& file_exists(Constants::fileTypePath["project_image"]["full_path"] . $settings->banner_img)) {
	$pageBannerImage = Constants::fileTypePath["project_image"]["url"] . $settings->banner_img;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!isset($_POST["csrf_token"], $_SESSION["csrf_token"]) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
		// TODO:: return back with error message
		exit('Geçersiz CSRF Token!');
	}
}