<?php

//sayfanın izin keyi
$page_role_key = "settings";
$page_add_role_key = "settings";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if ($session->sessionRoleControl($page_role_key, $listPermissionKey) == false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $page_role_key . " permissions => " . $listPermissionKey);
	$session->permissionDenied();
}
$default_lang = $siteManager->defaultLanguage();

$pageData[$default_lang->short_lang] = (array)$settings; 
//log atalım
$log->logThis($log->logTypes['SETTINGS_DETAIL']);

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
$customJs[] = "plugins/form/switch.min.js";
$customJs[] = "plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js";
$customJs[] = "plugins/ckeditor/ckeditor.js";

include($functions->root_url("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

include_once($functions->root_url("includes/System/FileUploader.php"));
$file = new \Includes\System\FileUploader($fileTypePath);

$themes = [];
foreach (glob(ROOT_PATH . '/app/View/*/') as $folder){
    $folder = explode('/', rtrim($folder, '/'));
    $themes[end($folder)] = end($folder);
}

if (isset($_POST["submit"]) && $_POST["submit"] == 1) {
	//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
	if ($session->sessionRoleControl($page_role_key, $listPermissionKey) == false || $session->sessionRoleControl($page_role_key, $editPermissionKey) == false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $page_role_key . " permissions => " . $listPermissionKey);
		$session->permissionDenied();
	}
	$pageData[$default_lang->short_lang]["keywords"] = $functions->clean_post("keywords");
	$pageData[$default_lang->short_lang]["description"] = $functions->clean_post("description");
	$pageData[$default_lang->short_lang]["link_sort_lang"] = $functions->clean_post_int("link_sort_lang");

	$pageData[$default_lang->short_lang]["site_status_control"] = is_numeric($functions->post("site_status"));
	$pageData[$default_lang->short_lang]["site_status"] = $functions->clean_post_int("site_status");
	$pageData[$default_lang->short_lang]["post_theme"] = $functions->clean_post("theme");
	$pageData[$default_lang->short_lang]["title"] = $functions->clean_post("title");
	$pageData[$default_lang->short_lang]["project_name"] = $functions->clean_post("project_name");
	$pageData[$default_lang->short_lang]["logo_text"] = $functions->clean_post("logo_text");

	$pageData[$default_lang->short_lang]["google"] = $functions->clean_post("google");
	$pageData[$default_lang->short_lang]["facebook"] = $functions->clean_post("facebook");
	$pageData[$default_lang->short_lang]["twitter"] = $functions->clean_post("twitter");
	$pageData[$default_lang->short_lang]["instagram"] = $functions->clean_post("instagram");
	$pageData[$default_lang->short_lang]["youtube"] = $functions->clean_post("youtube");
	$pageData[$default_lang->short_lang]["linkedin"] = $functions->clean_post("linkedin");
	$pageData[$default_lang->short_lang]["vk"] = $functions->clean_post("vk");
	$pageData[$default_lang->short_lang]["telegram"] = $functions->clean_post("telegram");
	$pageData[$default_lang->short_lang]["whatsapp"] = $functions->clean_post("whatsapp");

	$pageData[$default_lang->short_lang]["site_mail"] = $functions->clean_post("site_mail");
	$pageData[$default_lang->short_lang]["phone"] = $functions->clean_post("phone");
	$pageData[$default_lang->short_lang]["fax"] = $functions->clean_post("fax");
	$pageData[$default_lang->short_lang]["maps"] = $functions->clean_post("maps");
	$pageData[$default_lang->short_lang]["adres"] = $functions->clean_post_textarea("adres");
	$pageData[$default_lang->short_lang]["contact_despription"] = $functions->clean_post_textarea("contact_despription");


	$pageData[$default_lang->short_lang]["site_status_title"] = $functions->clean_post("site_status_title");
	$pageData[$default_lang->short_lang]["site_status_text"] = $functions->clean_post_textarea("site_status_text");

	//smtp
	$pageData[$default_lang->short_lang]["mail_send_mode"] = isset($_POST["mail_send_mode"]) ? 1 : 0;
	$pageData[$default_lang->short_lang]["smtp_host"] = $functions->clean_post("smtp_host");
	$pageData[$default_lang->short_lang]["smtp_email"] = $functions->clean_post("smtp_email");
	$pageData[$default_lang->short_lang]["smtp_password"] = $functions->clean_post("smtp_password");
	$pageData[$default_lang->short_lang]["smtp_port"] = $functions->clean_post("smtp_port");
	$pageData[$default_lang->short_lang]["smtp_secure"] = $functions->clean_post("smtp_secure");
	$pageData[$default_lang->short_lang]["smtp_send_name_surname"] = $functions->clean_post("smtp_send_name_surname");
	$pageData[$default_lang->short_lang]["smtp_send_email_adres"] = $functions->clean_post("smtp_send_email_adres");
	$pageData[$default_lang->short_lang]["smtp_send_email_reply_adres"] = $functions->clean_post("smtp_send_email_reply_adres");
	$pageData[$default_lang->short_lang]["smtp_mail_send_debug"] = $functions->clean_post_int("smtp_mail_send_debug");
	$pageData[$default_lang->short_lang]["smtp_mail_send_debug_control"] = is_numeric($functions->post("smtp_mail_send_debug"));
	$pageData[$default_lang->short_lang]["smtp_send_debug_adres"] = $functions->clean_post("smtp_send_debug_adres");

	//sayfa bulunamadı
	$pageData[$default_lang->short_lang]["page_not_found_title"] = $functions->clean_post("page_not_found_title");
	$pageData[$default_lang->short_lang]["page_not_found_text"] = $functions->clean_post_textarea("page_not_found_text");

    //özel linkler
    foreach ($systemLinkPrefix as $prefixKey => $systemLinkPrefixValue) {
        foreach ($projectLanguages as $project_languages_row) {
            $pageData[$default_lang->short_lang][$prefixKey.$project_languages_row->short_lang] = $functions->clean_post($prefixKey.$project_languages_row->short_lang);
        }
    }


	$db_data_settings = array();


	$message = array();
	if (empty($pageData[$default_lang->short_lang]["title"])) {
		$message["reply"][] = "Site başlığı boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["project_name"])) {
		if (strlen($pageData[$default_lang->short_lang]["project_name"]) < 5) {
			$message["reply"][] = "Proje ismi 5 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["project_name"]) > 100) {
			$message["reply"][] = "Proje İsmi 100 karakterden büyük olamaz.";
		}
	}
	if (!empty($pageData[$default_lang->short_lang]["title"])) {
		if (strlen($pageData[$default_lang->short_lang]["title"]) < 5) {
			$message["reply"][] = "Site başlığı 5 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["title"]) > 100) {
			$message["reply"][] = "Site başlığı 100 karakterden büyük olamaz.";
		}
	}
	if (!empty($pageData[$default_lang->short_lang]["logo_text"])) {
		if (strlen($pageData[$default_lang->short_lang]["logo_text"]) > 60) {
			$message["reply"][] = "Logo yazısı 60 karakterden fazla olamaz.";
		}
	}

	if (empty($pageData[$default_lang->short_lang]["keywords"])) {
		$message["reply"][] = "Anahtar kelimeler boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["keywords"])) {
		if (strlen($pageData[$default_lang->short_lang]["keywords"]) < 10) {
			$message["reply"][] = "Anahtar kelimeler 10 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["keywords"]) > 250) {
			$message["reply"][] = "Anahtar kelimeler 250 karakterden büyük olamaz.";
		}
	}
	if (empty($pageData[$default_lang->short_lang]["description"])) {
		$message["reply"][] = "Site açıklama boş olamaz.";
	}
	if (!empty($pageData[$default_lang->short_lang]["description"])) {
		if (strlen($pageData[$default_lang->short_lang]["description"]) < 10) {
			$message["reply"][] = "Site açıklama 10 karakterden az olamaz.";
		}
		if (strlen($pageData[$default_lang->short_lang]["description"]) > 500) {
			$message["reply"][] = "Site açıklama 500 karakterden büyük olamaz.";
		}
	}
	if (empty($pageData[$default_lang->short_lang]["post_theme"])) {
		$message["reply"][] = "Lütfen site temasını seçin.";
	}
	if (!empty($pageData[$default_lang->short_lang]["post_theme"]) && !in_array($pageData[$default_lang->short_lang]["post_theme"], array_keys($themes), true)) {
		$message["reply"][] = "Geçersiz tema seçimi.";
	}

	if (!$pageData[$default_lang->short_lang]["site_status_control"]) {
		$message["reply"][] = "Site durumunu seçiniz.";
	}
	if ($pageData[$default_lang->short_lang]["site_status_control"] && !in_array($pageData[$default_lang->short_lang]["site_status"], array_keys($systemSiteMod), true)) {
		$message["reply"][] = "Geçersiz menü türü.";
	}

	if (!empty($pageData[$default_lang->short_lang]["site_mail"])) {
		if (!$functions->is_email($pageData[$default_lang->short_lang]["site_mail"])) {
			$message["reply"][] = "Lütfen geçerli bir e-posta adresi giriniz.";
		}
	}
	if (!empty($pageData[$default_lang->short_lang]["phone"])) {
		if (!is_numeric($pageData[$default_lang->short_lang]["phone"])) {
			$message["reply"][] = "Telefon numarası sadece rakam olabilir.";
		}
		if (strlen($pageData[$default_lang->short_lang]["phone"]) != 10) {
			$message["reply"][] = "Telefon numaranız 10 karakter olmalıdır.";
		}
	}
	/*
	if(!empty($pageData[$default_lang->short_lang]["phone_cep"])){
		if(!is_numeric($pageData[$default_lang->short_lang]["phone_cep"])){
			$message["reply"][] = "Cep telefon numarası sadece rakam olabilir.";
		}
		if(strlen($pageData[$default_lang->short_lang]["phone_cep"]) != 10){
			$message["reply"][] = "Cep telefon numaranız 10 karakter olmalıdır.";
		}
	}
	*/
	if (!empty($pageData[$default_lang->short_lang]["fax"])) {
		if (!is_numeric($pageData[$default_lang->short_lang]["fax"])) {
			$message["reply"][] = "Fax numarası sadece rakam olabilir.";
		}
		if (strlen($pageData[$default_lang->short_lang]["fax"]) != 10) {
			$message["reply"][] = "Fax numaranız 10 karakter olmalıdır.";
		}
	}

	if (!in_array($pageData[$default_lang->short_lang]["link_sort_lang"], array_keys($systemYesAndNoVersion2))) {
		$message["reply"][] = "Geçersiz dil kısaltması seçimi.";
	}


	if ($pageData[$default_lang->short_lang]["mail_send_mode"] == 1) {
		//mail gönderimi aktif olursa bu ayarlar kesin gerekli
		if (empty($pageData[$default_lang->short_lang]["smtp_host"])) {
			$message["reply"][] = "Smtp host boş olamaz.";
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_email"])) {
			$message["reply"][] = "Smtp e-posta adresi boş olamaz.";
		}
		if (!empty($pageData[$default_lang->short_lang]["smtp_email"])) {
			if (!$functions->is_email($pageData[$default_lang->short_lang]["smtp_email"])) {
				$message["reply"][] = "Lütfen geçerli bir smtp e-posta adresi giriniz.";
			}
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_password"])) {
			$message["reply"][] = "Smtp e-posta şifresi boş olamaz.";
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_port"])) {
			$message["reply"][] = "Smtp port boş olamaz.";
		}
		if (!empty($pageData[$default_lang->short_lang]["smtp_port"])) {
			if (!is_numeric($pageData[$default_lang->short_lang]["smtp_port"])) {
				$message["reply"][] = "Smtp port sadece sayı olmalıdır.";
			}
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_secure"])) {
			$message["reply"][] = "Smpt secure boş olamaz.";
		}
		if (!empty($pageData[$default_lang->short_lang]["smtp_secure"])) {
			if (!array_key_exists($pageData[$default_lang->short_lang]["smtp_secure"], $smtpSecureType)) {
				$message["reply"][] = "Lütfen geçerli bir smtp secure seçin.";
			}
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_send_name_surname"])) {
			$message["reply"][] = "Mail gönderen ad soyad boş olamaz.";
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_send_email_adres"])) {
			$message["reply"][] = "Gönderen e-posta adresi boş olamaz.";
		}
		if (!empty($pageData[$default_lang->short_lang]["smtp_send_email_adres"])) {
			if (!$functions->is_email($pageData[$default_lang->short_lang]["smtp_send_email_adres"])) {
				$message["reply"][] = "Lütfen gönderen e-posta adresini geçerli formatta yazın.";
			}
		}
		if (empty($pageData[$default_lang->short_lang]["smtp_send_email_reply_adres"])) {
			$message["reply"][] = "Cevap e-posta adresi boş olamaz.";
		}
		if (!empty($pageData[$default_lang->short_lang]["smtp_send_email_reply_adres"])) {
			if (!$functions->is_email($pageData[$default_lang->short_lang]["smtp_send_email_reply_adres"])) {
				$message["reply"][] = "Lütfen cevap e-posta adresini geçerli formatta yazın.";
			}
		}
		if (!$pageData[$default_lang->short_lang]["smtp_mail_send_debug_control"]) {
			$message["reply"][] = "Lütfen Mail gönderme türünü seçin.";
		}
		if ($pageData[$default_lang->short_lang]["smtp_mail_send_debug_control"]) {
			if (($pageData[$default_lang->short_lang]["smtp_mail_send_debug"] != 1) && ($pageData[$default_lang->short_lang]["smtp_mail_send_debug"] != 2)) {
				$message["reply"][] = "Lütfen geçerli bir mail gönderme türü seçiniz.";
			}
		}
		if ($pageData[$default_lang->short_lang]["smtp_mail_send_debug"] == 1) {
			if (empty($pageData[$default_lang->short_lang]["smtp_send_debug_adres"])) {
				$message["reply"][] = "Test maillerinin gideceği adres boş olamaz.";
			}
			if (!$functions->is_email($pageData[$default_lang->short_lang]["smtp_send_debug_adres"])) {
				$message["reply"][] = "Test maillerinin gideceği adres e-posta formatında olmalıdır.";
			}
		}

		if (!empty($pageData[$default_lang->short_lang]["smtp_send_debug_adres"])) {
			if (!$functions->is_email($pageData[$default_lang->short_lang]["smtp_email"])) {
				$message["reply"][] = "Lütfen test maillerinin gideceği geçerli bir e-posta adresi giriniz.";
			}
		}
	}


    //içerik linkleri
    foreach ($systemLinkPrefix as $prefixKey => $systemLinkPrefixValue2) {
        foreach ($projectLanguages as $project_languages_row) {
            if (empty($pageData[$default_lang->short_lang][$prefixKey.$project_languages_row->short_lang])) {
                $message["reply"][] = $project_languages_row->lang . " - Lütfen ".$systemLinkPrefixValue2["title"]." alanını boş bırakmayınız.";
            }
        }
    }


	if (empty($message)) {
		//diğer herşey tamamsa resim işlemlerini yap
		if (!empty($_FILES["header_logo"]["name"])) {
			$file->global_file_name = "header_logo";
			$file->upload_folder = "project_image";
			$file->max_file_size = 5;
            $file->resize = true;
            $file->width= 250;
            $file->height = 100;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$db_data_settings["header_logo"] = $uploaded["img_name"];
			}
			if ($uploaded["result"] == 2) {
				$message["reply"][] = "Logo " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["footer_logo"]["name"])) {
			$file->global_file_name = "footer_logo";
			$file->upload_folder = "project_image";
			$file->max_file_size = 5;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$db_data_settings["footer_logo"] = $uploaded["img_name"];
			}
			if ($uploaded["result"] == 2) {
				$message["reply"][] = "Footer " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["footer_logo_2"]["name"])) {
			$file->global_file_name = "footer_logo_2";
			$file->upload_folder = "project_image";
			$file->max_file_size = 5;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$db_data_settings["footer_logo_2"] = $uploaded["img_name"];
			}
			if ($uploaded["result"] == 2) {
				$message["reply"][] = "Footer logo 2 " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["mail_tempate_logo"]["name"])) {
			$file->global_file_name = "mail_tempate_logo";
			$file->upload_folder = "project_image";
			$file->max_file_size = 5;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$db_data_settings["mail_tempate_logo"] = $uploaded["img_name"];
			}
			if ($uploaded["result"] == 2) {
				$message["reply"][] = "Mail tema logosu " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["banner_img"]["name"])) {
			$file->global_file_name = "banner_img";
			$file->upload_folder = "project_image";
			$file->max_file_size = 5;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$db_data_settings["banner_img"] = $uploaded["img_name"];
			}
			if ($uploaded["result"] == 2) {
				$message["reply"][] = "Banner resim  " . $uploaded["result_message"];
			}
		}
		if (!empty($_FILES["fav_icon"]["name"])) {
			$file->global_file_name = "fav_icon";
			$file->upload_folder = "project_image";
			$file->max_file_size = 5;
			$file->compressor = true;
			$uploaded = $file->file_upload();
			if ($uploaded["result"] == 1) {
				$db_data_settings["fav_icon"] = $uploaded["img_name"];
			}
			if ($uploaded["result"] == 2) {
				$message["reply"][] = "Fav icon  " . $uploaded["result_message"];
			}
		}
	}

	if (empty($message)) {


		$db_data_settings["keywords"] = $pageData[$default_lang->short_lang]["keywords"];
		$db_data_settings["link_sort_lang"] = $pageData[$default_lang->short_lang]["link_sort_lang"];
		$db_data_settings["description"] = $pageData[$default_lang->short_lang]["description"];
		$db_data_settings["site_status"] = $pageData[$default_lang->short_lang]["site_status"];
		$db_data_settings["theme"] = $pageData[$default_lang->short_lang]["post_theme"];
		$db_data_settings["title"] = $pageData[$default_lang->short_lang]["title"];
		$db_data_settings["project_name"] = $pageData[$default_lang->short_lang]["project_name"];
		$db_data_settings["logo_text"] = $pageData[$default_lang->short_lang]["logo_text"];


		$db_data_settings["google"] = $pageData[$default_lang->short_lang]["google"];
		$db_data_settings["facebook"] = $pageData[$default_lang->short_lang]["facebook"];
		$db_data_settings["twitter"] = $pageData[$default_lang->short_lang]["twitter"];
		$db_data_settings["instagram"] = $pageData[$default_lang->short_lang]["instagram"];
		$db_data_settings["youtube"] = $pageData[$default_lang->short_lang]["youtube"];
		$db_data_settings["linkedin"] = $pageData[$default_lang->short_lang]["linkedin"];
		$db_data_settings["vk"] = $pageData[$default_lang->short_lang]["vk"];
		$db_data_settings["telegram"] = $pageData[$default_lang->short_lang]["telegram"];
		$db_data_settings["whatsapp"] = $pageData[$default_lang->short_lang]["whatsapp"];

		$db_data_settings["site_mail"] = $pageData[$default_lang->short_lang]["site_mail"];
		$db_data_settings["phone"] = $pageData[$default_lang->short_lang]["phone"];
		$db_data_settings["fax"] = $pageData[$default_lang->short_lang]["fax"];
		$db_data_settings["maps"] = $pageData[$default_lang->short_lang]["maps"];
		$db_data_settings["adres"] = $pageData[$default_lang->short_lang]["adres"];
		$db_data_settings["contact_despription"] = $pageData[$default_lang->short_lang]["contact_despription"];


		$db_data_settings["site_status_title"] = $pageData[$default_lang->short_lang]["site_status_title"];
		$db_data_settings["site_status_text"] = $pageData[$default_lang->short_lang]["site_status_text"];

		//smtp
		$db_data_settings["mail_send_mode"] = $pageData[$default_lang->short_lang]["mail_send_mode"];
		$db_data_settings["smtp_host"] = $pageData[$default_lang->short_lang]["smtp_host"];
		$db_data_settings["smtp_email"] = $pageData[$default_lang->short_lang]["smtp_email"];
		$db_data_settings["smtp_password"] = $pageData[$default_lang->short_lang]["smtp_password"];
		$db_data_settings["smtp_port"] = $pageData[$default_lang->short_lang]["smtp_port"];
		$db_data_settings["smtp_secure"] = $pageData[$default_lang->short_lang]["smtp_secure"];
		$db_data_settings["smtp_send_name_surname"] = $pageData[$default_lang->short_lang]["smtp_send_name_surname"];
		$db_data_settings["smtp_send_email_adres"] = $pageData[$default_lang->short_lang]["smtp_send_email_adres"];
		$db_data_settings["smtp_send_email_reply_adres"] = $pageData[$default_lang->short_lang]["smtp_send_email_reply_adres"];
		$db_data_settings["smtp_mail_send_debug"] = $pageData[$default_lang->short_lang]["smtp_mail_send_debug"];
		$db_data_settings["smtp_send_debug_adres"] = $pageData[$default_lang->short_lang]["smtp_send_debug_adres"];

		//sayfa bulunamadı
		$db_data_settings["page_not_found_title"] = $pageData[$default_lang->short_lang]["page_not_found_title"];
		$db_data_settings["page_not_found_text"] = $pageData[$default_lang->short_lang]["page_not_found_text"];

		//mevcut urlleri çekip array yapalım
		$file_url_array = array();
		$data_file_url = $db::selectQuery("file_url", array(
			"deleted" => 0,
		));
		foreach ($data_file_url as $file_url_row) {
			$file_url_array[$file_url_row->url] = $file_url_row->url;
		}

        foreach ($systemLinkPrefix as $prefixKey => $systemLinkPrefixValue3) {
            foreach ($projectLanguages as $project_languages_row) {
                $db_data_settings[$prefixKey.$project_languages_row->short_lang] = $pageData[$default_lang->short_lang][$prefixKey.$project_languages_row->short_lang];
                //bu değerleri file_url'tablosuna da kaydememiz lazım çünkü controller ordan değişebiliyor burdan diline göre ekleyeceğiz
                if (!array_key_exists($pageData[$default_lang->short_lang][$prefixKey.$project_languages_row->short_lang], $file_url_array)) {
                    //bu yeni prefix file_url de yok eklensin
                    $db_prefix = array();
                    $db_prefix["url"] = $pageData[$default_lang->short_lang][$prefixKey.$project_languages_row->short_lang];
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
		$settings_db = array();
		foreach ($settings_query_data as $settings_row) {
			$settings_db[$settings_row->name] = $settings_row;
		}

		$completed = true;
		$refresh_time = 5;
		$message["refresh_time"] = $refresh_time;
		foreach ($db_data_settings as $key => $value) {
			if (array_key_exists($key, $settings_db)) {
				//eğer key varsa update edeceğiz
				$db_data = array();
				$db_data["name"] = $key;
                $db_data["val"] = $value;
				$update = $db::update("settings", $db_data, array("id" => $settings_db[$key]->id));
				if (!$update) {
					$completed = false;
				}
			} else {
				//key mevcut değerlerde yok yeni eklenecek
				$db_data = array();
				$db_data["name"] = $key;
				$db_data["val"] = $value;
				$insert = $db::insert("settings", $db_data);
				if (!$insert) {
					$completed = false;
				}
			}
		}
		if ($completed == true) {
			//log atalım
			$log->logThis($log->logTypes['SETTINGS_UPDATE_SUCC']);

			$message["success"][] = $lang["content-completed"];
			$functions->refresh($adminSystem->adminUrl("settings"), $refresh_time);
		} else {
			//log atalım
			$log->logThis($log->logTypes['SETTINGS_UPDATE_ERR']);

			$message["reply"][] = $lang["content-completed-error"];
		}
	}
}
//sayfa başlıkları
$page_title = "Genel Ayarlar";
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = "settings";
$page_button_redirect_text = $page_title;
$page_button_icon = "icon-list";

require $adminSystem->adminView('settings');
