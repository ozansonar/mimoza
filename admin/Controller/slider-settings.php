<?php

//sayfanın izin keyi
$data->pageRoleKey = "slider";
$pageAddRoleKey = "slider-settings";

$id = 0;
$pageData = array();
if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) == false || $session->sessionRoleControl($data->pageRoleKey, $constants::listPermissionKey) == false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $data->pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	//log atalım
	$log->logThis($log->logTypes['SLIDER_DETAIL']);

	$id = $functions->cleanGetInt("id");
	$data = $db::selectQuery("slider", array(
		"id" => $id,
		"deleted" => 0,
	), true);
	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}
	//id ye ait içeriği çektik şimdi bulduğumuz datadan gelen lang_id ile eşleşen dataları bulup arraya atalım
	$data_multi_lang = $db::selectQuery("slider", array(
		"lang_id" => $data->lang_id,
		"deleted" => 0,
	));
	foreach ($data_multi_lang as $data_row) {
		$pageData[$data_row->lang] = (array)$data_row;
		$db_data_lang[$data_row->lang] = $data_row->lang;
	}
} else {
	//add yetki kontrolü
	if ($session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey) == false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $data->pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}
	//log atalım
	$log->logThis($log->logTypes['SLIDER_ADD_PAGE']);
}

//bu sayfadakullanılan özel css'ler
$customCss = [];
$customCss[] = "plugins/form-validation-engine/css/validationEngine.jquery.css";
$customCss[] = "plugins/icheck-bootstrap/icheck-bootstrap.min.css";
$customCss[] = "plugins/select2/css/select2.min.css";
$customCss[] = "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css";
$customCss[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css";
$customCss[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css";
//bu sayfadakullanılan özel js'ler
$customJs = [];
$customJs[] = "plugins/form-validation-engine/js/jquery.validationEngine.js";
$customJs[] = "plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js";
$customJs[] = "plugins/select2/js/select2.full.min.js";
$customJs[] = "plugins/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js";
$customJs[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js";
//$customJs[] = "plugins/ckeditor/ckeditor.js";

if (isset($_POST["submit"]) && $_POST["submit"] == 1) {

	foreach ($projectLanguages as $project_languages_row) {
		$functions->form_lang = $project_languages_row->short_lang; // formda dil ektensi olduğunu belirtiyoruz class ona göre post edecek

		$pageData[$project_languages_row->short_lang]["title"] = $functions->cleanPost("title");
		$pageData[$project_languages_row->short_lang]["link"] = $functions->cleanPost("link");
		$pageData[$project_languages_row->short_lang]["abstract"] = $functions->clean_post_textarea("abstract");
		$pageData[$project_languages_row->short_lang]["text"] = $functions->clean_post_textarea("text");
		$pageData[$project_languages_row->short_lang]["show_order"] = $functions->cleanPostInt("show_order");
		$pageData[$project_languages_row->short_lang]["status"] = $functions->cleanPostInt("status");
		$pageData[$project_languages_row->short_lang]["status_control"] = is_numeric($functions->post("status"));
		$pageData[$project_languages_row->short_lang]["site_disi_link"] = $functions->post("site_disi_link") ? 1 : 0;
		$pageData[$project_languages_row->short_lang]["back_link"] = $functions->cleanPost("back_link");
		$pageData[$project_languages_row->short_lang]["yeni_sekme"] = $functions->post("yeni_sekme") ? 1 : 0;

		//istenilen kontroller
		if ($project_languages_row->form_validate == 1) {
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

			/*
			if(empty($pageData[$project_languages_row->short_lang]["text"])){
				$message["reply"][] = $project_languages_row->lang." - İçerik boş olamaz";
			}else{
				if(strlen(strip_tags($pageData[$project_languages_row->short_lang]["text"])) < 10){
					$message["reply"][] = $project_languages_row->lang." - İçerik 10 karekterden az olamaz.";
				}
			}
			*/

			if ($pageData[$project_languages_row->short_lang]["site_disi_link"] == 1 && empty($pageData[$project_languages_row->short_lang]["site_disi_link"])) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen site dışı linki yazınız.";
			}

			if (!empty($pageData[$project_languages_row->short_lang]["site_disi_link"])) {
				if (!filter_var($pageData[$project_languages_row->short_lang]["back_link"], FILTER_VALIDATE_URL)) {
					$message["reply"][] = $project_languages_row->lang . " - Site dışı link url formatında olmalıdır.";
				}
			}

			if ($pageData[$project_languages_row->short_lang]["show_order"] > 5000) {
				$message["reply"][] = $project_languages_row->lang . " - Gösterim sırası 5000 den büyük olamaz.";
			} elseif ($pageData[$project_languages_row->short_lang]["show_order"] == 0) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen gösterim sırasını yazınız.";
			}

			if ($pageData[$project_languages_row->short_lang]["status_control"] == false) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen onay durumunu seçiniz.";
			}
			if ($pageData[$project_languages_row->short_lang]["status_control"]) {
				if (!in_array($pageData[$project_languages_row->short_lang]["status"], array_keys($systemStatus))) {
					$message["reply"][] = $project_languages_row->lang . " - Geçersiz onay durumu.";
				}
			}
		}
	}
	if (empty($message)) {
		//üsteki şartlar sağlandığında resim yükleme işlemlerine geç her seferde resim yüklenmesin
		foreach ($projectLanguages as $project_languages_row) {
			include_once($system->path("includes/System/FileUploader.php"));
			$file = new \Includes\System\FileUploader($constants::fileTypePath);
			$file->global_file_name = "img_" . $project_languages_row->short_lang;
			$file->upload_folder = "slider";
			$file->max_file_size = 5;
			$file->resize = 5;
			$file->width = 1920;
            $file->height = 500;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$pageData[$project_languages_row->short_lang]["img"] = $uploaded["img_name"];
			}
			if ($project_languages_row->form_validate == 1) {
				if ($id > 0) {
					if ($uploaded["result"] == 2) {
						$message["reply"][] = $uploaded["result_message"];
					}
				} else {
					if (isset($uploaded["result_message"])) {
						$message["reply"][] = $uploaded["result_message"];
					}
				}
			}

		}
	}

	if (empty($message)) {
		$lang_id = date("YmdHis");
		foreach ($projectLanguages as $project_languages_row) {
			$db_data = array();
			$db_data["title"] = $pageData[$project_languages_row->short_lang]["title"];
			$db_data["link"] = !empty($pageData[$project_languages_row->short_lang]["link"]) ? $functions->permalink($pageData[$project_languages_row->short_lang]["link"]) : $functions->permalink($pageData[$project_languages_row->short_lang]["title"]);
			$db_data["abstract"] = $pageData[$project_languages_row->short_lang]["abstract"];
			$db_data["text"] = $pageData[$project_languages_row->short_lang]["text"];
			$db_data["show_order"] = $pageData[$project_languages_row->short_lang]["show_order"];
			$db_data["site_disi_link"] = $pageData[$project_languages_row->short_lang]["site_disi_link"];
			$db_data["back_link"] = $pageData[$project_languages_row->short_lang]["back_link"];
			$db_data["yeni_sekme"] = $pageData[$project_languages_row->short_lang]["yeni_sekme"];
			$db_data["status"] = $pageData[$project_languages_row->short_lang]["status"];
			if (isset($pageData[$project_languages_row->short_lang]["img"])) {
				$db_data["img"] = $pageData[$project_languages_row->short_lang]["img"];
			}
			$db_data["user_id"] = $session->get("user_id");
			if ($id > 0) {
				if (array_key_exists($project_languages_row->short_lang, $db_data_lang)) {
					//şuan ki dil db den gelen dataların içinde var bunu güncelle yoksa ekleyeceğiz
					//çünkü biz bu içeriği eklerken 1 dil olduğunu varsayalım 2. dili sisteme ekleyip bu içeriği update edersek 2.dili db ye insert etmesi lazım
					//güncelleme
					$update = $db::update("slider", $db_data, array("id" => $pageData[$project_languages_row->short_lang]["id"]));
				} else {
					//yeni dil insert ediliyor
					//lang işlemleri sadece eklemede gönderilsin
					$db_data["lang"] = $project_languages_row->short_lang;
					$db_data["lang_id"] = $data->lang_id;
					$add = $db::insert("slider", $db_data);
				}
			} else {
				//ekleme
				//lang işlemleri sadece eklemede gönderilsin
				$db_data["lang"] = $project_languages_row->short_lang;
				$db_data["lang_id"] = $lang_id;
				$add = $db::insert("slider", $db_data);
			}
		}
		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		if ($id > 0) {
			if ($update) {
				//log atalım
				$log->logThis($log->logTypes['SLIDER_EDIT_SUCC']);
				$message["success"][] = $lang["content-update"];

				$functions->refresh($system->adminUrl("slider-settings?id=" . $id), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['SLIDER_EDIT_ERR']);
				$message["reply"][] = $lang["content-update-error"];
			}
		} else {
			if ($add) {
				//log atalım
				$log->logThis($log->logTypes['SLIDER_ADD_SUCC']);
				$message["success"][] = $lang["content-insert"];

				$functions->refresh($system->adminUrl("slider-settings"), $refresh_time);
			} else {
				//log atalım
				$log->logThis($log->logTypes['SLIDER_ADD_ERR']);
				$message["reply"][] = $lang["content-insert-error"];
			}
		}
	}
}

include($system->path("includes/System/AdminForm.php"));
$form = new \Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Slider " . (isset($data) ? "Düzenle" : "Ekle");
$sub_title = null;
//butonun gideceği link ve yazısı
$data->pageButtonRedirectLink = "slider";
$data->pageButtonRedirectText = "Sliderlar";
$data->pageButtonIcon = "icon-list";
require $system->adminView('slider-settings');

