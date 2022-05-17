<?php
//sayfanın izin keyi
$page_role_key = "language-text-setting";
$page_add_role_key = "language-text-setting";

//edit ve delete yapsa bile show (s) yetkisi olması lazım onu kontrol edelim
if($session->sessionRoleControl($page_role_key,$listPermissionKey) == false){
    $log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"],"izinsiz erişim isteği user id->".$_SESSION["user_id"]." role key => ".$page_role_key." permissions => ".$listPermissionKey);
    $session->permissionDenied();
}
//log atalım
$log->logThis($log->logTypes['LANGUAGE_TEXT_LIST']);

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
$customJs[] = "plugins/ckeditor/ckeditor.js";

$pageData = [];

$text_manager_db_data = $db::selectQuery("settings");
foreach ($text_manager_db_data as $text_manager_db_row){
    if(empty($text_manager_db_row->lang)){
        continue;
    }
    //$pageData->{$text_manager_db_row->lang} = $pageData[$text_manager_db_row->lang];
    $pageData[$text_manager_db_row->lang][$text_manager_db_row->name] = $text_manager_db_row->val;
}
foreach ($pageData as $pdataKey=>$pdata){
    $pageData[$pdataKey] = (array)$pdata;
}

if(isset($_POST["submit"]) && $_POST["submit"] == 1){
    $data_lang = $functions->cleanPost("data_lang");
    $message = array();
    foreach ($projectLanguages as $project_languages_row){
        $functions->form_lang = $project_languages_row->short_lang; //namelerde dil uzantısı olacak
        //bu form ayrı ayrı akyıt edilecek o yüzden böyle bir şart ekliyoruz
        if($data_lang == $project_languages_row->short_lang){
            foreach($language_text_manager as $language_text_manager_key=>$language_text_manager_value){
                foreach ($language_text_manager_value["form"] as $language_text_manager_form){
                    if(isset($language_text_manager_form["type"]) && $language_text_manager_form["type"] == "textarea"){
                        $pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]] = $functions->clean_post_textarea($language_text_manager_form["name"]);
                    }else{
                        $pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]] = $functions->cleanPost($language_text_manager_form["name"]);
                    }

                    if(empty($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]]) && !isset($language_text_manager_form["no_required"])){
                        //$message["reply"][] = $language_text_manager_value["title"]." - ".$language_text_manager_form["label"]." boş olamaz.";
                    }
                    if(!empty($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]])){
                        if(strlen($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]]) < 2){
                            $message["reply"][] = $language_text_manager_value["title"]." - ".$language_text_manager_form["label"]." 2 karakterden az olamaz.";
                        }
                        if(strlen($pageData[$project_languages_row->short_lang][$language_text_manager_form["name"]]) > 5000){
                            $message["success"][] = $language_text_manager_value["title"]." - ".$language_text_manager_form["label"]. " 5000 karakterden fazla olamaz.";
                        }
                    }
                }

            }
            if(empty($message)){
                //db den güncel değerleri çekip ona göre işşlem yapalım
                $text_manager_db_data = $db::selectQuery("settings",array(
                    "lang" => $data_lang
                ));
                $text_manager_array = array();
                foreach ($text_manager_db_data as $text_manager_db_row){
                    $text_manager_array[$data_lang][$text_manager_db_row->name] = $text_manager_db_row;
                }

                $completed = true;
                $refresh_time = 5;
                $message["refresh_time"] = $refresh_time;
                foreach($language_text_manager as $language_text_manager_key=>$language_text_manager_value){
                    foreach ($language_text_manager_value["form"] as $language_text_manager_form){
                        if(isset($text_manager_array[$data_lang]) && array_key_exists($language_text_manager_form["name"],$text_manager_array[$data_lang])){
                            //eğer key varsa update edeceğiz
                            $db_data = array();
                            $db_data["name"] = $language_text_manager_form["name"];
                            $db_data["val"] = $pageData[$data_lang][$language_text_manager_form["name"]];
                            $db_data["lang"] = $data_lang;
                            $update = $db::update("settings", $db_data, array("id"=>$text_manager_array[$data_lang][$language_text_manager_form["name"]]->id));
                            if(!$update){
                                $completed = false;
                            }
                        }else{
                            //key mevcut değerlerde yok yeni eklenecek
                            $db_data = array();
                            $db_data["name"] = $language_text_manager_form["name"];
                            $db_data["val"] = $pageData[$data_lang][$language_text_manager_form["name"]];
                            $db_data["lang"] = $data_lang;
                            $insert = $db::insert("settings", $db_data);
                            if(!$insert){
                                $completed = false;
                            }
                        }
                    }

                }

                if ($completed == true) {
                    //log atalım
                    $log->logThis($log->logTypes['LANGUAGE_TEXT_UPDATE_SUCC']);

                    $message["success"][] = $lang["content-completed"];
                    $functions->refresh($system->adminUrl("language-text-setting"), $refresh_time);
                } else {
                    //log atalım
                    $log->logThis($log->logTypes['LANGUAGE_TEXT_UPDATE_ERR']);

                    $message["reply"][] = $lang["content-completed-error"];
                }
            }
        }
    }
}
include($system->path("includes/System/AdminForm.php"));
$form = new Includes\System\AdminForm();

//sayfa başlıkları
$page_title = "Dillere Göre Yazı İşlemleri";
$sub_title = null;
//butonun gideceği link ve yazısı
$page_button_redirect_link = null;
$page_button_redirect_text = null;
$page_button_icon = null;

require $system->adminView('language-text-setting');