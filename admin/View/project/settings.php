<section class="content">
    <div class="card card-danger card-outline card-outline-tabs">
        <div class="card-header">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 pr-3">
                    <h3 class="card-title"><?php echo $data->title; ?></h3>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        <i class="fas fa-cogs"></i>Site Ayarları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        <i class="fas fa-cogs"></i>&nbsp;
                        Sosyal Medya Ayarları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                        <i class="fas fa-cogs"></i>
                        &nbsp;İletişim Ayarları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#mail-smtp" role="tab">
                        <i class="fas fa-cogs"></i>&nbsp;
                        Mail SMTP Ayarları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#page-banner-image" role="tab">
                        <i class="fas fa-cogs"></i>&nbsp;
                        Sayfa Banner Resmi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#lang_content_prefix" role="tab">
                        <i class="fas fa-cogs"></i>
                        &nbsp;İçerik Linkleri
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="" method="post" id="pageForm" enctype="multipart/form-data">
				<?php echo $functions->csrfToken(); ?>
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    <div class="tab-pane fade show active" id="home">
						<?php
						$form->lang = $data->defaultLanguage->short_lang;
						$form->formNameWithoutLangCode = 1;
						echo $form->input("project_name", array(
							"label" => $admin_text["SETTINGS_PROJECT_NAME"],
							"required" => 1,
							"class" => "validate[required,maxSize[100],minSize[5]]"
						), $data->pageData);
						echo $form->input("title", array(
							"label" => $admin_text["SETTINGS_TITLE"],
							"required" => 1,
							"class" => "validate[required,maxSize[100],minSize[5]]"
						), $data->pageData);
						echo $form->input("logo_text", array(
							"label" => $admin_text["SETTINGS_LOGO_TEXT"],
						), $data->pageData);
						echo $form->file("fav_icon", array(
							"label" => $admin_text["SETTINGS_FAV_ICON"],
							"file_key" => "project_image",
						), $data->pageData);
						echo $form->file("header_logo", array(
							"label" => $admin_text["SETTINGS_LOGO"],
							"file_key" => "project_image",
						), $data->pageData);
						echo $form->file("footer_logo", array(
							"label" => $admin_text["SETTINGS_LOGO_FOOTER"],
							"file_key" => "project_image",
						), $data->pageData);
						echo $form->select("link_sort_lang", array(
							"label" => "Linklerde dil kısaltması olsun ?",
							"required" => 1,
							"select_item" => $constants::systemYesAndNoVersion2,
						), $data->pageData);
						echo $form->inputTags("keywords", array(
							"label" => $admin_text["SETTINGS_KEYWORDS"],
							"class" => "w-100",
						), $data->pageData);
						echo $form->textarea("description", array(
							"label" => $admin_text["SETTINGS_DESCRIPTION"],
						), $data->pageData);
						echo $form->select("theme", array(
							"label" => $admin_text["SETTINGS_THEMA"],
							"required" => 1,
							"select_item" => $data->themes,
						), $data->pageData);
						echo $form->select("site_status", array(
							"label" => $admin_text["SETTINGS_STATUS"],
							"required" => 1,
							"select_item" => $constants::systemSiteModVersion2,
						), $data->pageData);
						?>
                    </div>
                    <div class="tab-pane fade" id="profile">
                        <div class="alert bg-warning alert-styled-left alert-dismissible">
                            Sadece kullanıcı isimlerini yazmanız yeterlidir.
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["google"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["google"]["url"]; ?></span>
                            </div>
                            <input type="text" name="google"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["google"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["facebook"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["facebook"]["url"]; ?></span>
                            </div>
                            <input type="text" name="facebook"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["facebook"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["twitter"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["twitter"]["url"]; ?></span>
                            </div>
                            <input type="text" name="twitter"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["twitter"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["instagram"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["instagram"]["url"]; ?></span>
                            </div>
                            <input type="text" name="instagram"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["instagram"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["youtube"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["youtube"]["url"]; ?></span>
                            </div>
                            <input type="text" name="youtube"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["youtube"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["linkedin"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["linkedin"]["url"]; ?></span>
                            </div>
                            <input type="text" name="linkedin"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["linkedin"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["whatsapp"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["whatsapp"]["url"]; ?></span>
                            </div>
                            <input type="text" name="whatsapp"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["whatsapp"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["vk"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["vk"]["url"]; ?></span>
                            </div>
                            <input type="text" name="vk"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["vk"] ?? null; ?>"
                                   class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <div class="col-12 p-0">
                                <label for=""><?php echo $constants::socialMedia["telegram"]["title"]; ?></label>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $constants::socialMedia["telegram"]["url"]; ?></span>
                            </div>
                            <input type="text" name="telegram"
                                   value="<?php echo $data->pageData[$data->defaultLanguage->short_lang]["telegram"] ?? null; ?>"
                                   class="form-control">
                        </div>

                    </div>
                    <div class="tab-pane fade" id="messages">
                        <div class="alert bg-warning alert-styled-left alert-dismissible">
                            Numaraların başında <span class="font-weight-semibold">0</span> olmadan yazınız.
                        </div>
						<?php
						echo $form->input("site_mail", array(
							"label" => $admin_text["SETTINGS_MAIL"],
							"required" => 1
						), $data->pageData);
						echo $form->input("phone", array(
							"label" => $admin_text["SETTINGS_TELEFONU"],
							"class" => "numeric_field"
						), $data->pageData);
						echo $form->input("fax", array(
							"label" => $admin_text["SETTINGS_FAX"],
							"class" => "numeric_field"
						), $data->pageData);
						echo $form->input("maps", array(
							"label" => $admin_text["SETTINGS_GOOGLE_MAPS"],
						), $data->pageData);
						echo $form->textarea("adres", array(
							"label" => $admin_text["SETTINGS_ADRESS"],
						), $data->pageData);
						?>
                    </div>
                    <div class="tab-pane fade" id="mail-smtp">
                        <div class="form-group bt-switch">
                            <label for="id_mail_send_mode"><?php echo $admin_text["SETTINGS_MAIL_SMTMP_MODE"]; ?></label>
                            <div class="m-b-30">
                                <input type="checkbox" name="mail_send_mode" id="id_mail_send_mode"
                                       data-on-color="success" data-off-color="danger" data-on-text="Aktif"
                                       data-off-text="Pasif"
									<?php if (isset($mail_send_mode) && (int)$mail_send_mode === 1): echo "checked";
                                    elseif (isset($settings->mail_send_mode) && (int)$settings->mail_send_mode === 1): echo "checked";endif; ?>
                                >
                            </div>
                        </div>
                        <div id="mail_mode_on"
                             style="<?php echo $settings->mail_send_mode == 1 ? "" : "display: none;"; ?>">
                            <div class="alert bg-warning alert-styled-left alert-dismissible">
                                Lütfen tüm alanları eksiksiz doldurun yoksa sistem mail gönderimi yapamayacaktır.
                            </div>
							<?php
							echo $form->file("mail_tempate_logo", array(
								"label" => $admin_text["SETTINGS_MAIL_TEMPLATE_LOGO"],
								"file_key" => "project_image",
							), $data->pageData);
							echo $form->input("smtp_host", array(
								"label" => $admin_text["SETTINGS_MAIL_HOST"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("smtp_email", array(
								"label" => $admin_text["SETTINGS_MAIL_SMTP"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("smtp_password", array(
								"label" => $admin_text["SETTINGS_MAIL_SIFRE"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("smtp_port", array(
								"label" => $admin_text["SETTINGS_MAIL_PORT"],
								"required" => 1,
								"class" => "numeric_field"
							), $data->pageData);
							echo $form->select("smtp_secure", array(
								"label" => $admin_text["SETTINGS_MAIL_SECURE"],
								"required" => 1,
								"select_item" => $constants::smtpSecureType,
							), $data->pageData);
							echo $form->input("smtp_send_name_surname", array(
								"label" => $admin_text["SETTINGS_MAIL_SEND_NAME_SURNAME"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("smtp_send_email_adres", array(
								"label" => $admin_text["SETTINGS_MAIL_SEND_EMAIL_ADRES"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("smtp_send_email_reply_adres", array(
								"label" => $admin_text["SETTINGS_MAIL_REPLY_EMAIL_ADRES"],
								"required" => 1,
							), $data->pageData);
							echo $form->select("smtp_mail_send_debug", array(
								"label" => $admin_text["SETTINGS_MAIL_SEND_DEBUG"],
								"required" => 1,
								"select_item" => $constants::smtpSendMode,
							), $data->pageData);
							echo $form->input("smtp_send_debug_adres", array(
								"label" => $admin_text["SETTINGS_MAIL_SEND_DEBUG_ADRESS"],
								"required" => 1,
								"item_hidden" => 1,
								"show_data" => $data->pageData[$data->defaultLanguage->short_lang]["smtp_mail_send_debug"],
								"show_value" => 2,
							), $data->pageData);
							?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="page-banner-image">
						<?php echo $form->file("banner_img", array(
							"label" => $admin_text["SETTINGS_PAGE_BANNER_IMG"],
							"file_key" => "project_image"
						), $data->pageData); ?>
                    </div>
                    <div class="tab-pane fade" id="lang_content_prefix">
						<?php foreach ($constants::systemLinkPrefix as $prefixKey => $systemLinkPrefix) : ?>
                            <div class="mb-3 border p-3">
                                <div class="alert alert-info"
                                     role="alert"><?php echo $systemLinkPrefix["title"]; ?></div>
								<?php foreach ($projectLanguages as $project_languages_row):
									echo $form->input($prefixKey . $project_languages_row->short_lang, array(
										"label" => $project_languages_row->lang,
										"required" => 1
									), $data->pageData);
								endforeach ?>
                            </div>
						<?php endforeach ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-right">
			<?php echo $form->button("submit", array(
				"text" => "Kaydet",
				"icon" => "fas fa-save",
				"btn_class" => "btn btn-success",
			)); ?>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
        $("[name='mail_send_mode']").bootstrapSwitch();
    });

    $('#id_mail_send_mode').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#mail_mode_on").slideDown();
        } else {
            $("#mail_mode_on").slideUp();
        }
    });


    $("#id_smtp_mail_send_debug").change(function () {
        let this_val = $(this).val();
        if (this_val == 1) {
            $("#div_smtp_send_debug_adres").slideUp();
        } else {
            $("#div_smtp_send_debug_adres").slideDown();
            $("#div_smtp_send_debug_adres input[type=text]").val("");
        }
    });
</script>