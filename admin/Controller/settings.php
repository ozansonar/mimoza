<?php


use Mrt\MimozaCore\FileUploader;
use Mrt\MimozaCore\View;

$pageRoleKey = "settings";
$pageAddRoleKey = "settings";

if ($session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::listPermissionKey);
	$session->permissionDenied();
}

$defaultLanguage = $siteManager->defaultLanguage();
$pageData[$defaultLanguage->short_lang] = (array)$settings;
$log->logThis($log->logTypes['SETTINGS_DETAIL']);
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
	"plugins/form/switch.min.js",
	"plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
	"plugins/ckeditor/ckeditor.js",
];
$file = new FileUploader($constants::fileTypePath);

$themes = [];
foreach (glob(ROOT_PATH . '/app/View/*/') as $folder) {
	$folder = explode('/', rtrim($folder, '/'));
	if (end($folder) === 'errors' || end($folder) === 'layouts') {
		continue;
	}
	$themes[end($folder)] = end($folder);
}

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {
	//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
	if ($session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::listPermissionKey);
		$session->permissionDenied();
	}
	$pageData[$defaultLanguage->short_lang]["keywords"] = $functions->cleanPost("keywords");
	$pageData[$defaultLanguage->short_lang]["description"] = $functions->cleanPost("description");
	$pageData[$defaultLanguage->short_lang]["link_sort_lang"] = $functions->cleanPostInt("link_sort_lang");

	$pageData[$defaultLanguage->short_lang]["site_status_control"] = is_numeric($functions->post("site_status"));
	$pageData[$defaultLanguage->short_lang]["site_status"] = $functions->cleanPostInt("site_status");
	$pageData[$defaultLanguage->short_lang]["post_theme"] = $functions->cleanPost("theme");
	$pageData[$defaultLanguage->short_lang]["title"] = $functions->cleanPost("title");
	$pageData[$defaultLanguage->short_lang]["project_name"] = $functions->cleanPost("project_name");
	$pageData[$defaultLanguage->short_lang]["logo_text"] = $functions->cleanPost("logo_text");

	$pageData[$defaultLanguage->short_lang]["google"] = $functions->cleanPost("google");
	$pageData[$defaultLanguage->short_lang]["facebook"] = $functions->cleanPost("facebook");
	$pageData[$defaultLanguage->short_lang]["twitter"] = $functions->cleanPost("twitter");
	$pageData[$defaultLanguage->short_lang]["instagram"] = $functions->cleanPost("instagram");
	$pageData[$defaultLanguage->short_lang]["youtube"] = $functions->cleanPost("youtube");
	$pageData[$defaultLanguage->short_lang]["linkedin"] = $functions->cleanPost("linkedin");
	$pageData[$defaultLanguage->short_lang]["vk"] = $functions->cleanPost("vk");
	$pageData[$defaultLanguage->short_lang]["telegram"] = $functions->cleanPost("telegram");
	$pageData[$defaultLanguage->short_lang]["whatsapp"] = $functions->cleanPost("whatsapp");

	$pageData[$defaultLanguage->short_lang]["site_mail"] = $functions->cleanPost("site_mail");
	$pageData[$defaultLanguage->short_lang]["phone"] = $functions->cleanPost("phone");
	$pageData[$defaultLanguage->short_lang]["fax"] = $functions->cleanPost("fax");
	$pageData[$defaultLanguage->short_lang]["maps"] = $functions->cleanPost("maps");
	$pageData[$defaultLanguage->short_lang]["adres"] = $functions->cleanPostTextarea("adres");
	$pageData[$defaultLanguage->short_lang]["contact_despription"] = $functions->cleanPostTextarea("contact_despription");


	$pageData[$defaultLanguage->short_lang]["site_status_title"] = $functions->cleanPost("site_status_title");
	$pageData[$defaultLanguage->short_lang]["site_status_text"] = $functions->cleanPostTextarea("site_status_text");

	//smtp
	$pageData[$defaultLanguage->short_lang]["mail_send_mode"] = isset($_POST["mail_send_mode"]) ? 1 : 0;
	$pageData[$defaultLanguage->short_lang]["smtp_host"] = $functions->cleanPost("smtp_host");
	$pageData[$defaultLanguage->short_lang]["smtp_email"] = $functions->cleanPost("smtp_email");
	$pageData[$defaultLanguage->short_lang]["smtp_password"] = $functions->cleanPost("smtp_password");
	$pageData[$defaultLanguage->short_lang]["smtp_port"] = $functions->cleanPost("smtp_port");
	$pageData[$defaultLanguage->short_lang]["smtp_secure"] = $functions->cleanPost("smtp_secure");
	$pageData[$defaultLanguage->short_lang]["smtp_send_name_surname"] = $functions->cleanPost("smtp_send_name_surname");
	$pageData[$defaultLanguage->short_lang]["smtp_send_email_adres"] = $functions->cleanPost("smtp_send_email_adres");
	$pageData[$defaultLanguage->short_lang]["smtp_send_email_reply_adres"] = $functions->cleanPost("smtp_send_email_reply_adres");
	$pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug"] = $functions->cleanPostInt("smtp_mail_send_debug");
	$pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug_control"] = is_numeric($functions->post("smtp_mail_send_debug"));
	$pageData[$defaultLanguage->short_lang]["smtp_send_debug_adres"] = $functions->cleanPost("smtp_send_debug_adres");

	//sayfa bulunamadı
	$pageData[$defaultLanguage->short_lang]["page_not_found_title"] = $functions->cleanPost("page_not_found_title");
	$pageData[$defaultLanguage->short_lang]["page_not_found_text"] = $functions->cleanPostTextarea("page_not_found_text");

	//özel linkler
	foreach ($constants::systemLinkPrefix as $prefixKey => $systemLinkPrefixValue) {
		foreach ($projectLanguages as $project_languages_row) {
			$pageData[$defaultLanguage->short_lang][$prefixKey . $project_languages_row->short_lang] = $functions->cleanPost($prefixKey . $project_languages_row->short_lang);
		}
	}


	$db_data_settings = [];


	$message = [];
	if (empty($pageData[$defaultLanguage->short_lang]["title"])) {
		$message["reply"][] = "Site başlığı boş olamaz.";
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["project_name"])) {
		if (strlen($pageData[$defaultLanguage->short_lang]["project_name"]) < 5) {
			$message["reply"][] = "Proje ismi 5 karakterden az olamaz.";
		}
		if (strlen($pageData[$defaultLanguage->short_lang]["project_name"]) > 100) {
			$message["reply"][] = "Proje İsmi 100 karakterden büyük olamaz.";
		}
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["title"])) {
		if (strlen($pageData[$defaultLanguage->short_lang]["title"]) < 5) {
			$message["reply"][] = "Site başlığı 5 karakterden az olamaz.";
		}
		if (strlen($pageData[$defaultLanguage->short_lang]["title"]) > 100) {
			$message["reply"][] = "Site başlığı 100 karakterden büyük olamaz.";
		}
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["logo_text"])
		&& strlen($pageData[$defaultLanguage->short_lang]["logo_text"]) > 60) {
		$message["reply"][] = "Logo yazısı 60 karakterden fazla olamaz.";
	}

	if (empty($pageData[$defaultLanguage->short_lang]["keywords"])) {
		$message["reply"][] = "Anahtar kelimeler boş olamaz.";
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["keywords"])) {
		if (strlen($pageData[$defaultLanguage->short_lang]["keywords"]) < 10) {
			$message["reply"][] = "Anahtar kelimeler 10 karakterden az olamaz.";
		}
		if (strlen($pageData[$defaultLanguage->short_lang]["keywords"]) > 250) {
			$message["reply"][] = "Anahtar kelimeler 250 karakterden büyük olamaz.";
		}
	}
	if (empty($pageData[$defaultLanguage->short_lang]["description"])) {
		$message["reply"][] = "Site açıklama boş olamaz.";
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["description"])) {
		if (strlen($pageData[$defaultLanguage->short_lang]["description"]) < 10) {
			$message["reply"][] = "Site açıklama 10 karakterden az olamaz.";
		}
		if (strlen($pageData[$defaultLanguage->short_lang]["description"]) > 500) {
			$message["reply"][] = "Site açıklama 500 karakterden büyük olamaz.";
		}
	}
	if (empty($pageData[$defaultLanguage->short_lang]["post_theme"])) {
		$message["reply"][] = "Lütfen site temasını seçin.";
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["post_theme"])
		&& !array_key_exists($pageData[$defaultLanguage->short_lang]["post_theme"], $themes)) {
		$message["reply"][] = "Geçersiz tema seçimi.";
	}

	if (!$pageData[$defaultLanguage->short_lang]["site_status_control"]) {
		$message["reply"][] = "Site durumunu seçiniz.";
	}
	if ($pageData[$defaultLanguage->short_lang]["site_status_control"]
		&& !array_key_exists($pageData[$defaultLanguage->short_lang]["site_status"], $constants::systemSiteMod)) {
		$message["reply"][] = "Geçersiz menü türü.";
	}

	if (!empty($pageData[$defaultLanguage->short_lang]["site_mail"])
		&& !$functions->isEmail($pageData[$defaultLanguage->short_lang]["site_mail"])) {
		$message["reply"][] = "Lütfen geçerli bir e-posta adresi giriniz.";
	}
	if (!empty($pageData[$defaultLanguage->short_lang]["phone"])) {
		if (!is_numeric($pageData[$defaultLanguage->short_lang]["phone"])) {
			$message["reply"][] = "Telefon numarası sadece rakam olabilir.";
		}
		if (strlen($pageData[$defaultLanguage->short_lang]["phone"]) !== 10) {
			$message["reply"][] = "Telefon numaranız 10 karakter olmalıdır.";
		}
	}

	if (!empty($pageData[$defaultLanguage->short_lang]["fax"])) {
		if (!is_numeric($pageData[$defaultLanguage->short_lang]["fax"])) {
			$message["reply"][] = "Fax numarası sadece rakam olabilir.";
		}
		if (strlen($pageData[$defaultLanguage->short_lang]["fax"]) !== 10) {
			$message["reply"][] = "Fax numaranız 10 karakter olmalıdır.";
		}
	}

	if (!array_key_exists($pageData[$defaultLanguage->short_lang]["link_sort_lang"], $constants::systemYesAndNoVersion2)) {
		$message["reply"][] = "Geçersiz dil kısaltması seçimi.";
	}


	if ((int)$pageData[$defaultLanguage->short_lang]["mail_send_mode"] === 1) {
		//mail gönderimi aktif olursa bu ayarlar kesin gerekli
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_host"])) {
			$message["reply"][] = "Smtp host boş olamaz.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_email"])) {
			$message["reply"][] = "Smtp e-posta adresi boş olamaz.";
		}
		if (!empty($pageData[$defaultLanguage->short_lang]["smtp_email"])
			&& !$functions->isEmail($pageData[$defaultLanguage->short_lang]["smtp_email"])) {
			$message["reply"][] = "Lütfen geçerli bir smtp e-posta adresi giriniz.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_password"])) {
			$message["reply"][] = "Smtp e-posta şifresi boş olamaz.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_port"])) {
			$message["reply"][] = "Smtp port boş olamaz.";
		}
		if (!empty($pageData[$defaultLanguage->short_lang]["smtp_port"])
			&& !is_numeric($pageData[$defaultLanguage->short_lang]["smtp_port"])) {
			$message["reply"][] = "Smtp port sadece sayı olmalıdır.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_secure"])) {
			$message["reply"][] = "Smpt secure boş olamaz.";
		}
		if (!empty($pageData[$defaultLanguage->short_lang]["smtp_secure"])
			&& !array_key_exists($pageData[$defaultLanguage->short_lang]["smtp_secure"], $constants::smtpSecureType)) {
			$message["reply"][] = "Lütfen geçerli bir smtp secure seçin.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_send_name_surname"])) {
			$message["reply"][] = "Mail gönderen ad soyad boş olamaz.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_send_email_adres"])) {
			$message["reply"][] = "Gönderen e-posta adresi boş olamaz.";
		}
		if (!empty($pageData[$defaultLanguage->short_lang]["smtp_send_email_adres"])
			&& !$functions->isEmail($pageData[$defaultLanguage->short_lang]["smtp_send_email_adres"])) {
			$message["reply"][] = "Lütfen gönderen e-posta adresini geçerli formatta yazın.";
		}
		if (empty($pageData[$defaultLanguage->short_lang]["smtp_send_email_reply_adres"])) {
			$message["reply"][] = "Cevap e-posta adresi boş olamaz.";
		}
		if (!empty($pageData[$defaultLanguage->short_lang]["smtp_send_email_reply_adres"])
			&& !$functions->isEmail($pageData[$defaultLanguage->short_lang]["smtp_send_email_reply_adres"])) {
			$message["reply"][] = "Lütfen cevap e-posta adresini geçerli formatta yazın.";
		}
		if (!$pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug_control"]) {
			$message["reply"][] = "Lütfen Mail gönderme türünü seçin.";
		}
		if ($pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug_control"]
			&& ((int)$pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug"] !== 1)
			&& ((int)$pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug"] !== 2)) {
			$message["reply"][] = "Lütfen geçerli bir mail gönderme türü seçiniz.";
		}
		if ((int)$pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug"] === 1) {
			if (empty($pageData[$defaultLanguage->short_lang]["smtp_send_debug_adres"])) {
				$message["reply"][] = "Test maillerinin gideceği adres boş olamaz.";
			}
			if (!$functions->isEmail($pageData[$defaultLanguage->short_lang]["smtp_send_debug_adres"])) {
				$message["reply"][] = "Test maillerinin gideceği adres e-posta formatında olmalıdır.";
			}
		}

		if (!empty($pageData[$defaultLanguage->short_lang]["smtp_send_debug_adres"])
			&& !$functions->isEmail($pageData[$defaultLanguage->short_lang]["smtp_email"])) {
			$message["reply"][] = "Lütfen test maillerinin gideceği geçerli bir e-posta adresi giriniz.";
		}
	}

	foreach ($constants::systemLinkPrefix as $prefixKey => $systemLinkPrefixValue2) {
		foreach ($projectLanguages as $project_languages_row) {
			if (empty($pageData[$defaultLanguage->short_lang][$prefixKey . $project_languages_row->short_lang])) {
				$message["reply"][] = $project_languages_row->lang . " - Lütfen " . $systemLinkPrefixValue2["title"] . " alanını boş bırakmayınız.";
			}
		}
	}


	if (empty($message)) {
		if (!empty($_FILES["header_logo"]["name"])) {
			$file->globalFileName = "header_logo";
			$file->uploadFolder = "project_image";
			$file->maxFileSize = 5;
			$file->resize = true;
			$file->width = 250;
			$file->height = 100;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$db_data_settings["header_logo"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = "Logo " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["footer_logo"]["name"])) {
			$file->globalFileName = "footer_logo";
			$file->uploadFolder = "project_image";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$db_data_settings["footer_logo"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = "Footer " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["footer_logo_2"]["name"])) {
			$file->globalFileName = "footer_logo_2";
			$file->uploadFolder = "project_image";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$db_data_settings["footer_logo_2"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = "Footer logo 2 " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["mail_tempate_logo"]["name"])) {
			$file->globalFileName = "mail_tempate_logo";
			$file->uploadFolder = "project_image";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$db_data_settings["mail_tempate_logo"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = "Mail tema logosu " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["banner_img"]["name"])) {
			$file->globalFileName = "banner_img";
			$file->uploadFolder = "project_image";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$db_data_settings["banner_img"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = "Banner resim  " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["fav_icon"]["name"])) {
			$file->globalFileName = "fav_icon";
			$file->uploadFolder = "project_image";
			$file->maxFileSize = 5;
			$file->compressor = true;
			$uploaded = $file->fileUpload();
			if ((int)$uploaded["result"] === 1) {
				$db_data_settings["fav_icon"] = $uploaded["img_name"];
			}
			if ((int)$uploaded["result"] === 2) {
				$message["reply"][] = "Fav icon  " . $uploaded["result_message"];
			}
		}
	}

	if (empty($message)) {
		$db_data_settings["keywords"] = $pageData[$defaultLanguage->short_lang]["keywords"];
		$db_data_settings["link_sort_lang"] = $pageData[$defaultLanguage->short_lang]["link_sort_lang"];
		$db_data_settings["description"] = $pageData[$defaultLanguage->short_lang]["description"];
		$db_data_settings["site_status"] = $pageData[$defaultLanguage->short_lang]["site_status"];
		$db_data_settings["theme"] = $pageData[$defaultLanguage->short_lang]["post_theme"];
		$db_data_settings["title"] = $pageData[$defaultLanguage->short_lang]["title"];
		$db_data_settings["project_name"] = $pageData[$defaultLanguage->short_lang]["project_name"];
		$db_data_settings["logo_text"] = $pageData[$defaultLanguage->short_lang]["logo_text"];

		$db_data_settings["google"] = $pageData[$defaultLanguage->short_lang]["google"];
		$db_data_settings["facebook"] = $pageData[$defaultLanguage->short_lang]["facebook"];
		$db_data_settings["twitter"] = $pageData[$defaultLanguage->short_lang]["twitter"];
		$db_data_settings["instagram"] = $pageData[$defaultLanguage->short_lang]["instagram"];
		$db_data_settings["youtube"] = $pageData[$defaultLanguage->short_lang]["youtube"];
		$db_data_settings["linkedin"] = $pageData[$defaultLanguage->short_lang]["linkedin"];
		$db_data_settings["vk"] = $pageData[$defaultLanguage->short_lang]["vk"];
		$db_data_settings["telegram"] = $pageData[$defaultLanguage->short_lang]["telegram"];
		$db_data_settings["whatsapp"] = $pageData[$defaultLanguage->short_lang]["whatsapp"];

		$db_data_settings["site_mail"] = $pageData[$defaultLanguage->short_lang]["site_mail"];
		$db_data_settings["phone"] = $pageData[$defaultLanguage->short_lang]["phone"];
		$db_data_settings["fax"] = $pageData[$defaultLanguage->short_lang]["fax"];
		$db_data_settings["maps"] = $pageData[$defaultLanguage->short_lang]["maps"];
		$db_data_settings["adres"] = $pageData[$defaultLanguage->short_lang]["adres"];
		$db_data_settings["contact_despription"] = $pageData[$defaultLanguage->short_lang]["contact_despription"];

		$db_data_settings["site_status_title"] = $pageData[$defaultLanguage->short_lang]["site_status_title"];
		$db_data_settings["site_status_text"] = $pageData[$defaultLanguage->short_lang]["site_status_text"];

		//smtp
		$db_data_settings["mail_send_mode"] = $pageData[$defaultLanguage->short_lang]["mail_send_mode"];
		$db_data_settings["smtp_host"] = $pageData[$defaultLanguage->short_lang]["smtp_host"];
		$db_data_settings["smtp_email"] = $pageData[$defaultLanguage->short_lang]["smtp_email"];
		$db_data_settings["smtp_password"] = $pageData[$defaultLanguage->short_lang]["smtp_password"];
		$db_data_settings["smtp_port"] = $pageData[$defaultLanguage->short_lang]["smtp_port"];
		$db_data_settings["smtp_secure"] = $pageData[$defaultLanguage->short_lang]["smtp_secure"];
		$db_data_settings["smtp_send_name_surname"] = $pageData[$defaultLanguage->short_lang]["smtp_send_name_surname"];
		$db_data_settings["smtp_send_email_adres"] = $pageData[$defaultLanguage->short_lang]["smtp_send_email_adres"];
		$db_data_settings["smtp_send_email_reply_adres"] = $pageData[$defaultLanguage->short_lang]["smtp_send_email_reply_adres"];
		$db_data_settings["smtp_mail_send_debug"] = $pageData[$defaultLanguage->short_lang]["smtp_mail_send_debug"];
		$db_data_settings["smtp_send_debug_adres"] = $pageData[$defaultLanguage->short_lang]["smtp_send_debug_adres"];

		//sayfa bulunamadı
		$db_data_settings["page_not_found_title"] = $pageData[$defaultLanguage->short_lang]["page_not_found_title"];
		$db_data_settings["page_not_found_text"] = $pageData[$defaultLanguage->short_lang]["page_not_found_text"];

		//mevcut urlleri çekip array yapalım
		$file_url_array = [];
		$data_file_url = $db::selectQuery("file_url", array(
			"deleted" => 0,
		));
		foreach ($data_file_url as $file_url_row) {
			$file_url_array[$file_url_row->url] = $file_url_row->url;
		}

		foreach ($constants::systemLinkPrefix as $prefixKey => $systemLinkPrefixValue3) {
			foreach ($projectLanguages as $project_languages_row) {
				$db_data_settings[$prefixKey . $project_languages_row->short_lang] = $pageData[$defaultLanguage->short_lang][$prefixKey . $project_languages_row->short_lang];
				//bu değerleri file_url'tablosuna da kaydememiz lazım çünkü controller ordan değişebiliyor burdan diline göre ekleyeceğiz
				if (!array_key_exists($pageData[$defaultLanguage->short_lang][$prefixKey . $project_languages_row->short_lang], $file_url_array)) {
					//bu yeni prefix file_url de yok eklensin
					$db_prefix = [];
					$db_prefix["url"] = $pageData[$defaultLanguage->short_lang][$prefixKey . $project_languages_row->short_lang];
					$db_prefix["controller"] = $systemLinkPrefixValue3["controller"];
					$db_prefix["lang"] = $project_languages_row->short_lang;
					$db_prefix["status"] = 1;
					$db_prefix["user_id"] = $session->get("user_id");
					$db::insert("file_url", $db_prefix);
				}
			}
		}

		//site ayarlarını çekelim
		$settings_query_data = $db::selectQuery("settings");
		$settings_db = [];
		foreach ($settings_query_data as $settings_row) {
			$settings_db[$settings_row->name] = $settings_row;
		}

		$completed = true;
		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		foreach ($db_data_settings as $key => $value) {
			$db_data = [];
			$db_data["name"] = $key;
			$db_data["val"] = $value;
			if (array_key_exists($key, $settings_db)) {
				$update = $db::update("settings", $db_data, array("id" => $settings_db[$key]->id));
				if (!$update) {
					$completed = false;
				}
			} else {
				$insert = $db::insert("settings", $db_data);
				if (!$insert) {
					$completed = false;
				}
			}
		}
		if ($completed === true) {
			$log->logThis($log->logTypes['SETTINGS_UPDATE_SUCC']);
			$message["success"][] = $lang["content-completed"];
			$functions->refresh($system->adminUrl("settings"), $refresh_time);
		} else {
			$log->logThis($log->logTypes['SETTINGS_UPDATE_ERR']);
			$message["reply"][] = $lang["content-completed-error"];
		}
	}
}


View::backend('settings', [
	'title' => 'Genel Ayarlar',
	'pageButtonRedirectLink' => "settings",
	'pageButtonRedirectText' => 'Genel Ayarlar',
	'pageButtonIcon' => "fas fa-th-list",
	'pageData' => $pageData,
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'defaultLanguage' => $defaultLanguage,
	'themes' => $themes,
	'css' => $customCss,
	'js' => $customJs,
]);