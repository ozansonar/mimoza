<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "language-text-setting";
$pageAddRoleKey = "language-text-setting";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if ($session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::listPermissionKey);
	$session->permissionDenied();
}

$log->logThis($log->logTypes['LANGUAGE_TEXT_LIST']);
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
$pageData = [];

$text_manager_db_data = $db::selectQuery("settings");
foreach ($text_manager_db_data as $text_manager_db_row) {
	if (empty($text_manager_db_row->lang)) {
		continue;
	}
	$pageData[$text_manager_db_row->lang][$text_manager_db_row->name] = $text_manager_db_row->val;
}

foreach ($pageData as $pdataKey => $pdata) {
	$pageData[$pdataKey] = (array)$pdata;
}

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {
	$data_lang = $functions->cleanPost("data_lang");
	$message = [];
	foreach ($projectLanguages as $project_languages_row) {
		$functions->formLang = $project_languages_row->short_lang;
		//bu form ayrı ayrı akyıt edilecek o yüzden böyle bir şart ekliyoruz
		if ($data_lang === $project_languages_row->short_lang) {
			foreach ($language_text_manager as $language_text_manager_key => $language_text_manager_value) {
				foreach ($language_text_manager_value["form"] as $language_text_manager_form) {
					if (isset($language_text_manager_form["type"]) && $language_text_manager_form["type"] === "textarea") {
						$pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]] = $functions->cleanPostTextarea($language_text_manager_form["name"]);
					} else {
						$pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]] = $functions->cleanPost($language_text_manager_form["name"]);
					}

					if (!empty($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]])) {
						if (strlen($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]]) < 2) {
							$message["reply"][] = $language_text_manager_value["title"] . " - " . $language_text_manager_form["label"] . " 2 karakterden az olamaz.";
						}
						if (strlen($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]]) > 5000) {
							$message["success"][] = $language_text_manager_value["title"] . " - " . $language_text_manager_form["label"] . " 5000 karakterden fazla olamaz.";
						}
					}
				}

			}
			if (empty($message)) {
				$text_manager_db_data = $db::selectQuery("settings", array(
					"lang" => $data_lang
				));

				$text_manager_array = [];
				foreach ($text_manager_db_data as $text_manager_db_row) {
					$text_manager_array[$data_lang][$text_manager_db_row->name] = $text_manager_db_row;
				}

				$completed = true;
				$refresh_time = 3;
				$message["refresh_time"] = $refresh_time;
				foreach ($language_text_manager as $language_text_manager_key => $language_text_manager_value) {
					foreach ($language_text_manager_value["form"] as $language_text_manager_form) {
						$db_data = [];
						$db_data["name"] = $language_text_manager_form["name"];
						$db_data["val"] = $pageData[$data_lang][$language_text_manager_form["name"]];
						$db_data["lang"] = $data_lang;
						if (isset($text_manager_array[$data_lang]) && array_key_exists($language_text_manager_form["name"], $text_manager_array[$data_lang])) {
							//eğer key varsa update edeceğiz
							$update = $db::update("settings", $db_data, array("id" => $text_manager_array[$data_lang][$language_text_manager_form["name"]]->id));
							if (!$update) {
								$completed = false;
							}
						} else {
							//key mevcut değerlerde yok yeni eklenecek
							$insert = $db::insert("settings", $db_data);
							if (!$insert) {
								$completed = false;
							}
						}
					}

				}

				if ($completed === true) {
					$log->logThis($log->logTypes['LANGUAGE_TEXT_UPDATE_SUCC']);
					$message["success"][] = $lang["content-completed"];
					$functions->refresh($system->adminUrl("language-text-setting"), $refresh_time);
				} else {
					$log->logThis($log->logTypes['LANGUAGE_TEXT_UPDATE_ERR']);
					$message["reply"][] = $lang["content-completed-error"];
				}
			}
		}
	}
}

View::backend("language-text-setting", [
	'title' => "Dillere Göre Yazı İşlemleri",
	'pageButtonRedirectLink' => null,
	'pageButtonRedirectText' => null,
	'pageButtonIcon' => null,
	'pageData' => $pageData,
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'languageTextManager' => $language_text_manager,
	'css' =>$customCss,
	'js' =>$customJs,
]);