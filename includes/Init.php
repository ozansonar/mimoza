<?php

use Mrt\MimozaCore\Functions;
use Mrt\MimozaCore\Database;
use Mrt\MimozaCore\Session;
use Mrt\MimozaCore\SiteManager;
use Mrt\MimozaCore\Core;
use Mrt\MimozaCore\Log;
use Mrt\MimozaCore\Constants;

// Debug sınıfını kullanmayacaksanız kaldırabilirsiniz.
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;


// Includes all
include(__DIR__ . "/Config.php");
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


// Html purify exception array
$allowedSpecialHtmlPost = ["text", "adres", "contact_despription"];
//çoklu dildeki keyleri bu şekilde ekleyelim
foreach ($langData as $langRow) {
	$allowedSpecialHtmlPost[] = [
		"text_" . $langRow->short_lang,
		"abstract_" . $langRow->short_lang,
		"site_bakimda_aciklama_" . $langRow->short_lang,
		"404_page_text_" . $langRow->short_lang,
		"info_right_bar_text_2_" . $langRow->short_lang,
		"info_right_bar_text_3" . $langRow->short_lang,
	];
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