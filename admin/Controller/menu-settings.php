<?php

use Mrt\MimozaCore\AdminForm;
use Mrt\MimozaCore\View;

$pageRoleKey = "menu";
$pageAddRoleKey = "menu-settings";

$id = 0;
$pageData = [];

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $data->pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	//log atalım
	$log->logThis($log->logTypes['MENU_DETAIL']);

	//id'ye ait içeriği çekiyoruz
	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("menu", array(
		"id" => $id,
		"deleted" => 0,
	), true);
	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	//id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
	$data_multi_lang = $db::selectQuery("menu", array(
		"lang_id" => $data->lang_id,
		"deleted" => 0,
	));
	foreach ($data_multi_lang as $data_row) {
		$pageData[$data_row->lang] = (array)$data_row;
		$db_data_lang[$data_row->lang] = $data_row->lang;
	}
} else if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
	$session->permissionDenied();
}

$top_menu_array = [];
$menu_data = $db::selectQuery("menu", array(
	"deleted" => 0,
), false, null, 2);
foreach ($menu_data as $menu_data_row) {
	if (empty($menu_data_row["name"])) {
		continue;
	}
	$top_menu_array[$menu_data_row["lang"]][$menu_data_row["id"]] = $menu_data_row["name"];
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

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	foreach ($projectLanguages as $project_languages_row) {
		// formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek
		$functions->formLang = $project_languages_row->short_lang;
		$pageData[$project_languages_row->short_lang]["name"] = $functions->cleanPost("name");
		$pageData[$project_languages_row->short_lang]["link"] = $functions->cleanPost("link");
		$pageData[$project_languages_row->short_lang]["menu_type_control"] = is_numeric($functions->post("menu_type"));
		$pageData[$project_languages_row->short_lang]["menu_type"] = $functions->cleanPostInt("menu_type");
		$pageData[$project_languages_row->short_lang]["top_id_control"] = is_numeric($functions->post("top_id"));
		$pageData[$project_languages_row->short_lang]["top_id"] = $functions->cleanPostInt("top_id");
		$pageData[$project_languages_row->short_lang]["show_order"] = $functions->cleanPostInt("show_order");
		$pageData[$project_languages_row->short_lang]["redirect"] = is_numeric($functions->post("redirect"));
		$pageData[$project_languages_row->short_lang]["redirect_link"] = $functions->cleanPost("redirect_link");
		$pageData[$project_languages_row->short_lang]["redirect_open_type"] = (int)$functions->post("redirect_open_type") === 1 ? 1 : 0;
		$pageData[$project_languages_row->short_lang]["show_type"] = $functions->cleanPostInt("show_type");
		$pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");


		//istenilen kontroller
		if ((int)$project_languages_row->form_validate === 1) {
			if (empty($pageData[$project_languages_row->short_lang]["name"])) {
				$message["reply"][] = $project_languages_row->lang . " - Menü ismi boş olamaz.";
			}
			if (!empty($pageData[$project_languages_row->short_lang]["name"])) {
				if (strlen($pageData[$project_languages_row->short_lang]["name"]) < 2) {
					$message["reply"][] = $project_languages_row->lang . " - Menü ismi 2 karakterden az olamaz.";
				}
				if (strlen($pageData[$project_languages_row->short_lang]["name"]) > 200) {
					$message["success"][] = $project_languages_row->lang . " - Menü ismi 200 karakterden fazla olamaz.";
				}
			}

			if ($pageData[$project_languages_row->short_lang]["menu_type_control"] === false) {
				$message["reply"][] = $project_languages_row->lang . " - Menü türünü seçiniz.";
			} else if (!array_key_exists($pageData[$project_languages_row->short_lang]["menu_type"], $constants::systemMenuTypes)) {
				$message["reply"][] = $project_languages_row->lang . " - Geçersiz menü türü.";
			}

			if ((int)$pageData[$project_languages_row->short_lang]["menu_type"] === 2) {
				if ($pageData[$project_languages_row->short_lang]["top_id_control"] === false) {
					$message["reply"][] = $project_languages_row->lang . " - Üst menü boş olamaz.";
				} else if (!array_key_exists($pageData[$project_languages_row->short_lang]["top_id"], $top_menu_array[$project_languages_row->short_lang])) {
					$message["reply"][] = $project_languages_row->lang . " - Geçersiz üst menü.";
				}
			}

			if ($pageData[$project_languages_row->short_lang]["show_order"] > 5000) {
				$message["reply"][] = $project_languages_row->lang . " - Gösterim sırası 5000 den büyük olamaz.";
			} elseif ((int)$pageData[$project_languages_row->short_lang]["show_order"] === 0) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen gösterim sırasını yazınız.";
			}

			if ($pageData[$project_languages_row->short_lang]["redirect"]) {
				if (empty($pageData[$project_languages_row->short_lang]["redirect_link"])) {
					$message["reply"][] = $project_languages_row->lang . " - Gideceği link boş olamaz.";
				} else {
					if (strlen($pageData[$project_languages_row->short_lang]["redirect_link"]) < 4) {
						$message["reply"][] = $project_languages_row->lang . " - Gideği link 4 karakterden az olamaz.";
					}
					if (strlen($pageData[$project_languages_row->short_lang]["redirect_link"]) > 200) {
						$message["success"][] = $project_languages_row->lang . " - Gideceği link 200 karekterden fazla olamaz.";
					}
				}

			}

			if (!array_key_exists($pageData[$project_languages_row->short_lang]["show_type"], $constants::menuShowType)) {
				$message["reply"][] = $project_languages_row->lang . " - Geçersiz gösterim yeri seçimi.";
			}

			if (!array_key_exists($pageData[$project_languages_row->short_lang]["status"], $constants::systemStatus)) {
				$message["reply"][] = $project_languages_row->lang . " - Geçersiz onay durumu.";
			}

		}
	}

	if (empty($message)) {
		$lang_id = date("YmdHis");
		foreach ($projectLanguages as $project_languages_row) {
			$db_data = [];
			$db_data["name"] = $pageData[$project_languages_row->short_lang]["name"];
			$db_data["link"] = !empty($pageData[$project_languages_row->short_lang]["link"]) ? $pageData[$project_languages_row->short_lang]["link"] : $functions->permalink($pageData[$project_languages_row->short_lang]["name"]);
			$db_data["show_order"] = $pageData[$project_languages_row->short_lang]["show_order"];
			$db_data["menu_type"] = $pageData[$project_languages_row->short_lang]["menu_type"];
			$db_data["top_id"] = $pageData[$project_languages_row->short_lang]["top_id"];
			$db_data["redirect"] = $pageData[$project_languages_row->short_lang]["redirect"];
			$db_data["redirect_link"] = $pageData[$project_languages_row->short_lang]["redirect_link"];
			$db_data["redirect_open_type"] = $pageData[$project_languages_row->short_lang]["redirect_open_type"];
			$db_data["show_type"] = $pageData[$project_languages_row->short_lang]["show_type"];
			$db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
			$db_data["user_id"] = $session->get("user_id");

			if(empty($db_data['top_id'])){
				unset($db_data['top_id']);
			}

			if(empty($db_data['redirect_link'])){
				unset($db_data['redirect_link']);
			}

			if ($id > 0) {
				if (array_key_exists($project_languages_row->short_lang, $db_data_lang)) {
					//şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
					//çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
					//güncelleme
					$update = $db::update("menu", $db_data, array("id" => $pageData[$project_languages_row->short_lang]["id"]));
				} else {
					//yeni dil insert ediliyor
					//lang işlemleri sadece eklemede gönderilsin
					$db_data["lang"] = $project_languages_row->short_lang;
					$db_data["lang_id"] = $data->lang_id;
					$add = $db::insert("menu", $db_data);
				}
			} else {
				//ekleme
				//lang işlemleri sadece eklemede gönderilsin
				$db_data["lang"] = $project_languages_row->short_lang;
				$db_data["lang_id"] = $lang_id;

				$add = $db::insert("menu", $db_data);
			}
		}
		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			if ($update) {
				//log atalım
				$log->logThis($log->logTypes['MENU_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];

				$functions->refresh($system->adminUrl("menu-settings?id=" . $id), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['MENU_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else {
			if ($add) {
				//log atalım
				$log->logThis($log->logTypes['MENU_ADD_SUCC']);
				$message["success"][] = $lang["content-insert"];

				$functions->refresh($system->adminUrl("menu-settings"), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['MENU_ADD_ERR']);
				$message["reply"][] = $lang["content-insert-error"];
			}
		}
	}
}

View::backend('menu-settings', [
	'title' => "Menü " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "menu",
	'pageButtonRedirectText' => "Menüler",
	'pageButtonIcon' => "fas fa-th-list",
	'pageData' => $pageData,
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'topMenuArray' => $top_menu_array,
	'css' =>$customCss,
	'js' =>$customJs,
]);