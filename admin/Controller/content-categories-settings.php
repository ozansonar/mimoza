<?php

use Mrt\MimozaCore\AdminForm;
use Mrt\MimozaCore\FileUploader;
use Mrt\MimozaCore\View;

$pageRoleKey = "content-categories";
$pageAddRoleKey = "content-categories-settings";

$id = 0;
$pageData = [];

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	//log atalım
	$log->logThis($log->logTypes['CONTENT_CATEGORIES_DETAIL']);

	//id ye ait içeriği çekiyoruz
	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("content_categories", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	//id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
	$data_multi_lang = $db::selectQuery("content_categories", array(
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
		$pageData[$project_languages_row->short_lang]["title"] = $functions->cleanPost("title");
		$pageData[$project_languages_row->short_lang]["show_order_control"] = is_numeric($functions->post("show_order"));
		$pageData[$project_languages_row->short_lang]["show_order"] = $functions->cleanPostInt("show_order");
		$pageData[$project_languages_row->short_lang]["show_type_control"] = is_numeric($functions->post("show_type"));
		$pageData[$project_languages_row->short_lang]["show_type"] = $functions->cleanPostInt("show_type");
		$pageData[$project_languages_row->short_lang]["status_control"] = is_numeric($functions->post("status"));
		$pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");

		//istenilen kontroller
		if ((int)$project_languages_row->form_validate === 1) {
			if (empty($pageData[$project_languages_row->short_lang]["title"])) {
				$message["reply"][] = $project_languages_row->lang . " - Katetori ismi boş olamaz.";
			}

			if (!empty($pageData[$project_languages_row->short_lang]["title"])) {
				if (strlen($pageData[$project_languages_row->short_lang]["title"]) < 2) {
					$message["reply"][] = $project_languages_row->lang . " - Kategori ismi 2 karakterden az olamaz.";
				}
				if (strlen($pageData[$project_languages_row->short_lang]["title"]) > 200) {
					$message["reply"][] = $project_languages_row->lang . " - Kategori ismi 200 karakterden fazla olamaz.";
				}
			}

			if ($pageData[$project_languages_row->short_lang]["show_order_control"] === false) {
				$message["reply"][] = $project_languages_row->lang . " - Gösterim sırası sadece rakam olmalıdır.";
			}

			if ($pageData[$project_languages_row->short_lang]["show_order"] > 5000) {
				$message["reply"][] = $project_languages_row->lang . " - Gösterim sırası 5000 den büyük olamaz.";
			} elseif ((int)$pageData[$project_languages_row->short_lang]["show_order"] === 0) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen gösterim sırasını yazınız.";
			}

			if ((int)$pageData[$project_languages_row->short_lang]["show_type_control"] === false) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen gösterim seklini seçiniz.";
			}
			if ($pageData[$project_languages_row->short_lang]["show_type_control"] && !array_key_exists($pageData[$project_languages_row->short_lang]["show_type"], $constants::systemContentCategoriesShowTypes2)) {
				$message["reply"][] = $project_languages_row->lang . " - Geçersiz gösterim şekli seçimi.";
			}

			if ($pageData[$project_languages_row->short_lang]["status_control"] === false) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen onay durumunu seçiniz.";
			}
			if ($pageData[$project_languages_row->short_lang]["status_control"] && !array_key_exists($pageData[$project_languages_row->short_lang]["status"], $constants::systemStatus)) {
				$message["reply"][] = $project_languages_row->lang . " - Geçersiz onay durumu.";
			}
		}
	}
	if (empty($message)) {
		//üsteki şartlar sağlandığında resim yükleme işlemlerine geç her seferde resim yüklenmesin
		foreach ($projectLanguages as $project_languages_row) {
			$file = new FileUploader($constants::fileTypePath);
			$file->globalFileName = "img_" . $project_languages_row->short_lang;
			$file->uploadFolder = "content_categories";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if (isset($uploaded["result"]) && (int)$uploaded["result"] === 1) {
				$pageData[$project_languages_row->short_lang]["img"] = $uploaded["img_name"];
			}
			if (isset($uploaded["result"]) && (int)$uploaded["result"] === 2) {
				$message["reply"][] = $uploaded["result_message"];
			}
		}
	}
	if (empty($message)) {
		$lang_id = date("YmdHis");
		foreach ($projectLanguages as $project_languages_row) {
			$db_data = [];
			$db_data["title"] = $pageData[$project_languages_row->short_lang]["title"];
			$db_data["link"] = $functions->permalink($pageData[$project_languages_row->short_lang]["title"]);
			if (isset($pageData[$project_languages_row->short_lang]["img"])) {
				$db_data["img"] = $pageData[$project_languages_row->short_lang]["img"];
			}
			$db_data["show_type"] = $pageData[$project_languages_row->short_lang]["show_type"];
			$db_data["show_order"] = $pageData[$project_languages_row->short_lang]["show_order"];
			$db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
			$db_data["user_id"] = $session->get("user_id");
			if ($id > 0) {
				if (array_key_exists($project_languages_row->short_lang, $db_data_lang)) {
					//şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
					//çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
					//güncelleme
					$update = $db::update("content_categories", $db_data, array("id" => $pageData[$project_languages_row->short_lang]["id"]));
				} else {
					//yeni dil insert ediliyor
					//lang işlemleri sadece eklemede gönderilsin
					$db_data["lang"] = $project_languages_row->short_lang;
					$db_data["lang_id"] = $data->lang_id;
					$add = $db::insert("content_categories", $db_data);
				}
			} else {
				//ekleme
				//lang işlemleri sadece eklemede gönderilsin
				$db_data["lang"] = $project_languages_row->short_lang;
				$db_data["lang_id"] = $lang_id;

				$add = $db::insert("content_categories", $db_data);
			}
		}
		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			if ($update) {
				//log atalım
				$log->logThis($log->logTypes['CONTENT_CATEGORIES_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];

				$functions->refresh($system->adminUrl("content-categories-settings?id=" . $id), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['CONTENT_CATEGORIES_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else if ($add) {
			//log atalım
			$log->logThis($log->logTypes['CONTENT_CATEGORIES_ADD_SUCC']);
			$message["success"][] = $lang["content-insert"];

			$functions->refresh($system->adminUrl("content-categories-settings"), $refresh_time);
		} else {
			//log atalım
			$log->logThis($log->logTypes['CONTENT_CATEGORIES_ADD_ERR']);
			$message["reply"][] = $lang["content-insert-error"];
		}
	}
}

View::backend('content-categories-settings', [
	'title' => "İçerik Kategorisi " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "content-categories",
	'pageButtonRedirectText' => "Kategoriler",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageData' => $pageData,
	'id' => $id,
	'css' =>$customCss,
	'js' =>$customJs,
]);