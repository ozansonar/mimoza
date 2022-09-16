<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "page-link";
$pageAddRoleKey = "page-link-settings";

$id = 0;
$pageData = [];

$default_lang = $siteManager->defaultLanguage();

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['PAGE_LINK_DETAIL']);
	$id = $functions->cleanGetInt("id");

	$data = $db::selectQuery("file_url", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl("page-link"));
	}

	$pageData[$default_lang->short_lang] = (array)$data;
} else {
	//add yetki kontrolü
	if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['PAGE_LINK_ADD_PAGE']);
}

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css",
	"plugins/icheck-bootstrap/icheck-bootstrap.min.css",
	"plugins/select2/css/select2.min.css",
	"plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
	"plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css",
	"plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
];

$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
	"plugins/select2/js/select2.full.min.js",
	"plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js",
	"plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
	"plugins/ckeditor/ckeditor.js",
];

$file_url_array = [];

$data_file_url = $db::selectQuery("file_url", array(
	"deleted" => 0,
));

foreach ($data_file_url as $file_url_row) {
	$file_url_array[$file_url_row->url] = $file_url_row->url;
}

$pageLanguages = [];
foreach ($projectLanguages as $projectLanguagesKey => $projectLanguagesValue) {
	$pageLanguages[$projectLanguagesKey] = $projectLanguagesKey;
}

$pl_controller = [];
foreach (glob(ROOT_PATH . '/app/Controller/*.php') as $folder) {
	$folder = explode('/', rtrim($folder, '/'));
	$folder_end = end($folder);
	$folder_parse = explode(".", $folder_end);
	if (in_array($folder_parse[0], $constants::pageLinkNoListController, true)) {
		continue;
	}
	$pl_controller[$folder_parse[0]] = $folder_parse[0];
}

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	$pageData[$default_lang->short_lang]["url"] = $functions->cleanPost("url");
	$pageData[$default_lang->short_lang]["controller"] = $functions->cleanPost("controller");
	$pageData[$default_lang->short_lang]["lang"] = $functions->cleanPost("lang");
	$pageData[$default_lang->short_lang]["status_control"] = is_numeric($functions->post("status"));
	$pageData[$default_lang->short_lang]["status"] = $functions->cleanPostInt("status");


	if (empty($pageData[$default_lang->short_lang]["url"])) {
		$message["reply"][] = "Link boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["url"])) {
		if (strlen($pageData[$default_lang->short_lang]["url"]) < 2) {
			$message["reply"][] = "Link 2 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["url"]) > 200) {
			$message["success"][] = "Link 200 karakterden fazla olamaz.";
		}
	}

	if (empty($pageData[$default_lang->short_lang]["controller"])) {
		$message["reply"][] = "Lütfen gideceği dosyayı seçin.";
	}
	if (!empty($pageData[$default_lang->short_lang]["controller"])
		&& !in_array($pageData[$default_lang->short_lang]["controller"], $pl_controller, true)) {
		$message["reply"][] = "Lütfen geçerli bir dosya seçin.";
	}

	if (!$pageData[$default_lang->short_lang]["status_control"]) {
		$message["reply"][] = "Lütfen onay durumunu seçiniz.";
	} else if (!array_key_exists($pageData[$default_lang->short_lang]["status"], $constants::systemStatus)) {
		$message["reply"][] = "Geçersiz onay durumu.";
	}

	if ($pageData[$default_lang->short_lang]["lang"] && !array_key_exists($pageData[$default_lang->short_lang]["lang"], $pageLanguages)) {
		$message["reply"][] = "Geçersiz dil seçimi";
	}

	if (array_key_exists($pageData[$default_lang->short_lang]["url"], $file_url_array)) {
		$message["reply"][] = "Bu link zaten mevcut.";
	}

	if (empty($message)) {
		$db_data = [];
		$db_data["url"] = $functions->permalink($pageData[$default_lang->short_lang]["url"]);
		$db_data["controller"] = $pageData[$default_lang->short_lang]["controller"];
		$db_data["lang"] = $pageData[$default_lang->short_lang]["lang"];
		$db_data["status"] = $pageData[$default_lang->short_lang]["status"];
		$db_data["user_id"] = $session->get("user_id");

		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			//güncelleme
			$update = $db::update("file_url", $db_data, array("id" => $id));
			if ($update) {
				//log atalım
				$log->logThis($log->logTypes['PAGE_LINK_EDIT_SUCC']);

				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl("page-link-settings?id=" . $id), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['PAGE_LINK_EDIT_ERR']);

				$message["reply"][] = $lang["content-update-error"];
			}
		} else {
			//ekleme
			$add = $db::insert("file_url", $db_data);
			if ($add) {
				$log->logThis($log->logTypes['PAGE_LINK_ADD_SUCC']);
				$message["success"][] = $lang["content-insert"];
				$functions->refresh($system->adminUrl("page-link"), $refresh_time);
			} else {
				$log->logThis($log->logTypes['PAGE_LINK_ADD_ERR']);
				$message["reply"][] = $lang["content-insert-error"];
			}
		}
	}
}

View::backend('page-link-settings',[
	'title' =>"Sayfa Linki " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "page-link",
	'pageButtonRedirectText' => "Sayfa Linkleri",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'defaultLanguage' => $default_lang,
	'pageData' => ($data) ?? [],
	'pl_controller' => $pl_controller,
	'pageLanguages' => $pageLanguages,
	'css' =>$customCss,
	'js' =>$customJs,
]);