<?php

use Mrt\MimozaCore\FileUploader;
use Mrt\MimozaCore\View;

$pageRoleKey = "page";
$pageAddRoleKey = "page-settings";

$id = 0;
$pageData = [];

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false || $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	//log atalım
	$log->logThis($log->logTypes['PAGE_DETAIL']);

	$id = $functions->cleanGetInt("id");
	//id'ye ait içeriği çekiyoruz
	$data = $db::selectQuery("page", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	//id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
	$data_multi_lang = $db::selectQuery("page", array(
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
		$pageData[$project_languages_row->short_lang]["link"] = $functions->cleanPost("link");
		$pageData[$project_languages_row->short_lang]["abstract"] = $functions->cleanPostTextarea("abstract");
		$pageData[$project_languages_row->short_lang]["text"] = $functions->cleanPostTextarea("text");
		$pageData[$project_languages_row->short_lang]["keywords"] = $functions->cleanPost("keywords");
		$pageData[$project_languages_row->short_lang]["description"] = $functions->cleanPost("description");
		$pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");
		$pageData[$project_languages_row->short_lang]["status_control"] = is_numeric($functions->post("status"));

		//istenilen kontroller
		if ((int)$project_languages_row->form_validate === 1) {
			if (empty($pageData[$project_languages_row->short_lang]["title"])) {
				$message["reply"][] = $project_languages_row->lang . " - Başlık boş olamaz.";
			}
			if (!empty($pageData[$project_languages_row->short_lang]["title"])) {
				if (strlen($pageData[$project_languages_row->short_lang]["title"]) < 2) {
					$message["reply"][] = $project_languages_row->lang . " - Başlık 2 karakterden az olamaz.";
				}
				if (strlen($pageData[$project_languages_row->short_lang]["title"]) > 200) {
					$message["reply"][] = $project_languages_row->lang . " - Başlık 200 karakterden fazla olamaz.";
				}
			}

			if (empty($pageData[$project_languages_row->short_lang]["text"])) {
				$message["reply"][] = $project_languages_row->lang . " - İçerik boş olamaz.";
			}

			if (!empty($pageData[$project_languages_row->short_lang]["keywords"]) && strlen($pageData[$project_languages_row->short_lang]["keywords"]) > 200) {
				$message["reply"][] = $project_languages_row->lang . " - Anahtar kelimeler SEO 200 kararakterden fazla olamaz.";
			}

			if (!empty($pageData[$project_languages_row->short_lang]["description"]) && strlen($pageData[$project_languages_row->short_lang]["description"]) > 200) {
				$message["success"][] = $project_languages_row->lang . " - Açıklama SEO 350 kararakterden fazla olamaz.";
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
			$file->uploadFolder = "page_image";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$pageData[$project_languages_row->short_lang]["img"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = $uploaded["result_message"];
			}
		}
	}
	if (empty($message)) {
		$lang_id = date("YmdHis");
		foreach ($projectLanguages as $project_languages_row) {
			$db_data = [];
			$db_data["title"] = $pageData[$project_languages_row->short_lang]["title"];
			$db_data["link"] = !empty($pageData[$project_languages_row->short_lang]["link"])
				? $pageData[$project_languages_row->short_lang]["link"]
				: $functions->permalink($pageData[$project_languages_row->short_lang]["title"]);
			$db_data["abstract"] = $pageData[$project_languages_row->short_lang]["abstract"];
			$db_data["text"] = $pageData[$project_languages_row->short_lang]["text"];
			if (isset($pageData[$project_languages_row->short_lang]["img"])) {
				$db_data["img"] = $pageData[$project_languages_row->short_lang]["img"];
			}
			$db_data["keywords"] = $pageData[$project_languages_row->short_lang]["keywords"];
			$db_data["description"] = $pageData[$project_languages_row->short_lang]["description"];
			$db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
			$db_data["user_id"] = $session->get("user_id");
			if ($id > 0) {
				if (array_key_exists($project_languages_row->short_lang, $db_data_lang)) {
					//şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
					//çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
					//güncelleme
					$update = $db::update("page", $db_data, array("id" => $pageData[$project_languages_row->short_lang]["id"]));
				} else {
					//yeni dil insert ediliyor
					//lang işlemleri sadece eklemede gönderilsin
					$db_data["lang"] = $project_languages_row->short_lang;
					$db_data["lang_id"] = $data->lang_id;
					$add = $db::insert("page", $db_data);
				}
			} else {
				//ekleme
				//lang işlemleri sadece eklemede gönderilsin
				$db_data["lang"] = $project_languages_row->short_lang;
				$db_data["lang_id"] = $lang_id;
				$add = $db::insert("page", $db_data);
			}
		}
		$refresh_time = 3;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			if ($update) {
				//log atalım
				$log->logThis($log->logTypes['PAGE_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];
				$functions->refresh($system->adminUrl("page-settings?id=" . $id), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['PAGE_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else if ($add) {
			//log atalım
			$log->logThis($log->logTypes['PAGE_ADD_SUCC']);
			$message["success"][] = $lang["content-insert"];
			$functions->refresh($system->adminUrl("content-settings"), $refresh_time);
		} else {
			//log atalım
			$log->logThis($log->logTypes['PAGE_ADD_ERR']);
			$message["reply"][] = $lang["content-insert-error"];
		}
	}
}

View::backend('page-settings', [
	'title' => "Sayfa " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "menu",
	'pageButtonRedirectText' => "Sayfalar",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageData' => $pageData,
	'id' => $id,
	'css' =>$customCss,
	'js' =>$customJs,
]);