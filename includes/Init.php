<?php

use Includes\System\Functions;
use Includes\System\AdminSystem;
use Includes\System\Database;
use Includes\System\Session;
use Includes\System\SiteManager;
use Includes\System\Core;
use Includes\System\Log;

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
$adminSystem = new AdminSystem();
$system = new Core();
$log = new Log($db);

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

include(__DIR__ . "/Statics/Common.php");

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
	&& file_exists($fileTypePath["project_image"]["full_path"] . $settings->banner_img)) {
	$pageBannerImage = $fileTypePath["project_image"]["url"] . $settings->banner_img;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!isset($_POST["token"], $_SESSION["token"]) || ($_POST['token'] !== $_SESSION['token'])) {
		// TODO:: return back with error message
		//exit('Geçersiz CSRF Token!');
	}
}