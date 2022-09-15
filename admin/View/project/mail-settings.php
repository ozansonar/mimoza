<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mailing de kullanılacak resim(ler).</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
                Mailingde resim kullanacaksanız lütfen resmi bu kısımdan yükleyerek kullanının. Resmi yüklediğinde linki
                ekrana yazılacaktır.
            </div>
            <form enctype="multipart/form-data" method="post" id="fileForm">
				<?php
				$form->formNameWithoutLangCode = true;
				$form->lang = "tr";
				echo $functions->csrfToken();
				echo $form->file("file", array(
					"label" => $admin_text["MAILING_IMG"],
					"file_key" => "mailing",
				));
				echo $form->input("image_upload_counter", array(
					"type" => "hidden",
				), array("tr" => array("image_upload_counter" => 0)));
				?>
            </form>
            <div class="col-12 p-0" id="image_upload_result" style="display: none;"></div>
        </div>
        <div class="card-footer text-right">
			<?php echo $form->button("img_upload", array(
				"text" => "Yükle",
				"type" => "button",
				"icon" => "fas fa-save",
				"form_name" => "fileForm",
				"onclick_function" => "fileUpload();",
				"btn_class" => "btn btn-success",
			)); ?>
        </div>
    </div>
</section>
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mailing de gönderilecek ek(ler).</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
                Mailingde ek(ler) göndermek istiyorsanız yükleyebilirisiniz.
            </div>
            <form enctype="multipart/form-data" method="post" id="AttachmentForm">
                <?php echo $functions->getCsrfToken(); ?>
				<?php
				echo $form->file("attachment", array(
					"label" => $admin_text["MAILING_EK"],
					"file_key" => "mailing_attachment",
				));
				?>
            </form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-right">
			<?php echo $form->button("attachment", array(
				"text" => "Yükle",
				"type" => "button",
				"icon" => "fas fa-save",
				"form_name" => "fileForm",
				"onclick_function" => "AttachmentUpload();",
				"btn_class" => "btn btn-success",
			)); ?>
        </div>
    </div>
</section>
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mailing hazırla.</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
                Mailingde ek(ler) göndermek istiyorsanız yükleyebilirisiniz.
            </div>
            <form enctype="multipart/form-data" method="post" id="mailingForm">
                <?php echo $functions->getCsrfToken(); ?>
				<?php
				echo $form->input("subject", array(
					"label" => $admin_text["MAILING_SUBJECT"],
					"required" => 1,
				), $data->pageData);
				echo $form->textarea("text", array(
					"label" => $admin_text["MAILING_TEXT"],
					"class" => "ckeditor",
				), $data->pageData);
				?>
                <div class="col-12" id="attachment_upload_result"
                     style="<?php echo isset($attachment_array) && !empty($attachment_array) ? null : "display: none;"; ?>">
					<?php
					if (isset($attachment_array) && !empty($attachment_array)) {
						foreach ($attachment_array as $attachment_row) {
							$uniq_id = uniqid('', true);
							?>
                            <div class="alert alert-success" id="attachment_<?php echo $uniq_id; ?>" role="alert">
                                Ek -
                                <a href="<?php echo $constants::fileTypePath["mailing_attachment"]["url"] . $attachment_row; ?>"
                                   target="_blank"><?php echo $attachment_row; ?></a>
                                <input type="hidden" name="attecament[]" value="<?php echo $attachment_row; ?>">
                                <button type="button" onclick="AttecamentDelete('<?php echo $uniq_id; ?>')"
                                        class="btn btn-danger">Sil
                                </button>
                            </div>
							<?php
						}
					}
					?>
                </div>
                <div class="col-12">
                    <div class="card w-100 row p-2 d-flex">
                       <div class="card-header">
                           <div class="col-12">
                               <h4>Kullanıcılar</h4>
                           </div>
                       </div>
                        <div class="card-body d-flex justify-content-between">
                            <div class="row">
                                <div class="col-4 form-group icheck-primary d-inline">
                                    <input type="checkbox" class="form-check-input" name="user[]" value="999"
                                           id="id_user_hepsi">
                                    <label class="form-check-label" for="id_user_hepsi">Hepsi</label>
                                </div>
								<?php foreach ($constants::systemAdminUserType as $user_key => $user_value): ?>
									<?php if ((int)$user_key === 999) {
										continue;
									} ?>
                                    <div class="col-4 form-group icheck-primary d-inline">
                                        <input type="checkbox" class="form-check-input user_checkbox"
                                               name="user[<?php echo $user_key; ?>]" value="<?php echo $user_key; ?>"
                                               id="id_user_<?php echo $user_key; ?>">
                                        <label class="form-check-label"
                                               for="id_user_<?php echo $user_key; ?>"><?php echo $user_value["form_text"]; ?></label>
                                    </div>
								<?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info alert-dismissible">
                    <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
                    Deneme maili göndermek istiyorsanız aşağıda ki alana gerekli bilgileri yazınız birden fazla adrese
                    gitmesini istiyorsanızda "Ekle" diyerek ekleyebilirsiniz.
                </div>
                <div class="col-12 border">
                    <div class="col-12 text-right pr-0 py-2">
                        <button type="button" class="btn btn-info" onclick="test_user_add();">Ekle</button>
                    </div>
                    <div class="col-12 p-0 test-user-div">
						<?php if (isset($test_user_array)): ?>
							<?php
							$test_div_count = 1;
							foreach ($test_user_array as $test_user_div):
								?>
                                <div class="border my-1 px-1 test-user-<?php echo $test_div_count; ?>">
                                    <div class="form-group">
                                        <label for="id_<?php echo $test_div_count; ?>_email">E-posta</label>
                                        <input type="email" class="form-control"
                                               name="user_test[<?php echo $test_div_count; ?>][email]"
                                               id="id_<?php echo $test_div_count; ?>_email"
                                               value="<?php echo $test_user_div["email"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="id_<?php echo $test_div_count; ?>_name">Ad</label>
                                        <input type="text" class="form-control"
                                               name="user_test[<?php echo $test_div_count; ?>][name]"
                                               id="id_<?php echo $test_div_count; ?>_name"
                                               value="<?php echo $test_user_div["name"]; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="id_<?php echo $test_div_count; ?>_surname">Soyad</label>
                                        <input type="text" class="form-control"
                                               name="user_test[<?php echo $test_div_count; ?>][surname]"
                                               id="id_<?php echo $test_div_count; ?>_surname"
                                               value="<?php echo $test_user_div["surname"]; ?>">
                                    </div>
									<?php if ($test_div_count > 1): ?>
                                        <div class="form-group text-right">
                                            <button class="btn btn-danger test-user-delete" type="button">SİL</button>
                                        </div>
									<?php endif; ?>
                                </div>
								<?php
								$test_div_count++;
								?>
							<?php endforeach; ?>
						<?php else: ?>
                            <div class="border my-1 p-2 test-user-1">
                                <div class="form-group">
                                    <label for="id_1_email">E-posta</label>
                                    <input type="email" class="form-control" name="user_test[1][email]" id="id_1_email">
                                </div>
                                <div class="form-group">
                                    <label for="id_1_name">Ad</label>
                                    <input type="text" class="form-control" name="user_test[1][name]" id="id_1_name">
                                </div>
                                <div class="form-group">
                                    <label for="id_1_surname">Soyad</label>
                                    <input type="text" class="form-control" name="user_test[1][surname]"
                                           id="id_1_surname">
                                </div>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
                <hr>
				<?php
				echo $form->input("image", array(
					"type" => "hidden",
				), $data->pageData);
				echo $form->input("test_user_counter", array(
					"type" => "hidden",
				), array("tr" => array("test_user_counter" => 1)));
				echo $form->checkbox(array(
					"option" => array(
						array(
							"label" => "Maili gerçek kişilere yapmak istiyorsanız lütfen işaretleyiniz.",
							"name" => "send",
							"value" => 1
						)
					)
				), $data->pageData);
				?>
            </form>
        </div>
        <div class="card-footer text-right">
			<?php echo $form->button("mailing_save", array(
				"text" => "Kaydet",
				"icon" => "fas fa-save",
				"form_name" => "mailingForm",
				"btn_class" => "btn btn-success",
			)); ?>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $("form#mailingForm").validationEngine('attach', {
            "promptPosition": "bottomLeft",
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    for (var PageForm in CKEDITOR.instances)
                        CKEDITOR.instances[PageForm].updateElement();
                    //$("#id_mailing_save").attr('disabled', 'disabled');
                    //$("#id_mailing_save").text("Kaydediliyor...");
                    var data = $("form#mailingForm").serialize();
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo $system->adminUrl("ajax/mailing-save"); ?>",
                        data: data,
                        success: function (response) {
                            //$("#id_mailing_save").removeAttr('disabled');
                            //$("#id_mailing_save").text("Kaydet");
                            if (response.success) {
                                $("form#mailingForm")[0].reset();
                                AlertMessage("success", "Mailing Kaydededildi", response.success, "Tamam", 1, response.url, response.time);
                                //$("#id_mailing_save").attr('disabled', 'disabled');
                                //$("#id_mailing_save").text("Kaydedildi");
                            }
                            if (response.reply) {
                                AlertMessage("error", "Hata", response.reply);
                            }
                        },
                        dataType: 'json'
                    });

                }
            }
        });

        $("#id_user_hepsi").click(function () {
            if ($(this).is(":checked")) {
                $(".user_checkbox").prop("checked", false);
                $(".user_checkbox").attr("disabled", "disabled");
            } else {
                $(".user_checkbox").removeAttr("disabled");
            }
        });
    });

    //resim yükletiyor
    function fileUpload() {
        var data = new FormData($("#fileForm")[0]);
        $("#id_img_upload").attr("disabled", "disabled");
        $("#id_img_upload").text("Yükleniyor...");
        $.ajax({
            type: 'POST',
            url: "ajax/file-upload",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    var count = parseInt($("input[name='image_upload_counter']").val()) + 1;
                    $("input[name='image_upload_counter']").val(count);
                    $("#image_upload_result").append('<div class="alert alert-success" role="alert">' + count + ' - ' + response.img_path + ' <button class="btn btn-warning" onclick="btnCopy(this)" data-img-url="' + response.img_path + '"><i class="fa fa-copy"></i> Kopyala</button></div>');
                    $("#image_upload_result").show();

                    var uploaded_image_name = $("input[name='image']").val();
                    $("input[name='image']").val(uploaded_image_name + "," + response.img_name);

                    toast_message("bg-success", "Tebrikler", response.success);
                }
                if (response.reply) {
                    AlertMessage("error", "HATA", response.reply, "TAMAM", 0);
                }
                $("#id_img_upload").removeAttr("disabled");
                $("#id_img_upload").text("Yükle");
            },
            dataType: 'json'
        });
    }

    //ekleri siler
    function AttecamentDelete(uniq_id) {
        $("#attachment_" + uniq_id).remove();
    }

    //ek yükletiyor
    function AttachmentUpload() {
        var data = new FormData($("#AttachmentForm")[0]);
        $("#id_attachment").attr("disabled", "disabled");
        $("#id_attachment").text("Yükleniyor...");
        $.ajax({
            type: 'POST',
            url: "ajax/attachment-upload",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $("#attachment_upload_result").append('<div class="alert alert-success" id="attachment_' + response.uniq_id + '" role="alert">Ek - <a href="<?php echo $constants::fileTypePath["mailing_attachment"]["url"]; ?>' + response.img_name + '" target="_blank">' + response.img_name + '</a> <input type="hidden" name="attecament[]" value="' + response.img_name + '"> <button type="button" onclick="AttecamentDelete(\'' + response.uniq_id + '\')" class="btn btn-danger">Sil</button></div>');
                    $("#attachment_upload_result").show();

                    toast_message("bg-success", "Tebrikler", response.success);
                }
                if (response.reply) {
                    AlertMessage("error", "HATA", response.reply, "TAMAM", 0);
                }
                $("#id_attachment").removeAttr("disabled");
                $("#id_attachment").text("Yükle");
            },
            dataType: 'json'
        });
    }

    function test_user_add() {
        var count = parseInt($("#id-test-user-counter").val());
        var token = $("#token").val();
        var val = count + 1;
        $.ajax({
            type: 'POST',
            url: "ajax/test-user",
            data: {"count": val, "token": token},
            success: function (response) {
                if (response.success) {
                    $("#id-test-user-counter").val(val);
                    $(".test-user-div").append(response.html);

                    toast_message("bg-success", "Tebrikler", response.success);
                }
                if (response.reply) {
                    AlertMessage("error", "HATA", response.reply, "TAMAM", 0);
                }
            },
            dataType: 'json'
        });
    }

    function btnCopy(element) {
        var text = $(element).data("img-url");
        copyToClipboard(text);
    }

    $('body').on('click', '.test-user-delete', function () {
        $(this).parent().parent().remove();
    });


</script>