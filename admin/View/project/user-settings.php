<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
				<?php echo $data->title; ?>
            </h3>
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
            <form action="" method="post" id="pageForm" enctype="multipart/form-data">
				<?php
                echo $functions->csrfToken();
				$form->formNameWithoutLangCode = 1;
				echo $form->input("email", array(
					"label" => $admin_text["HESAP_EMAIL"],
					"required" => 1,
					"class" => "validate[custom[email]]"
				), $data->pageData);
				echo $form->input("name", array(
					"label" => $admin_text["HESAP_NAME"],
					"required" => 1,
				), $data->pageData);
				echo $form->input("surname", array(
					"label" => $admin_text["HESAP_SURNAME"],
					"required" => 1,
				), $data->pageData);
				echo $form->input("telefon", array(
					"label" => $admin_text["HESAP_PHONE"],
					"required" => 1,
				), $data->pageData);
				echo $form->file("img", array(
					"label" => $admin_text["HESAP_RESIM"],
					"file_key" => "user_image",
					"delete_link" => "?id=" . $data->id . "&img_delete=1"
				), $data->pageData);
				echo $form->input("password", array(
					"type" => "password",
					"label" => $admin_text["HESAP_PASSWORD"],
					"class" => "validate[maxSize[50],minSize[10]]"
				), $data->pageData);
				echo $form->input("password_again", array(
					"type" => "password",
					"label" => $admin_text["HESAP_PASSWORD_AGAIN"],
					"class" => "validate[maxSize[50],minSize[10]]"
				), $data->pageData);
				echo $form->select("status", array(
					"label" => $admin_text["HESAP_DURUMU"],
					"required" => 1,
					"select_item" => $constants::systemStatusVersion,
				), $data->pageData);

				?>
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
        $("#id-password").blur(function () {
            if ($(this).val() == "") {
                $("#id-password").removeClass("validate[required,funcCall[checkPass]]");
            } else {
                $("#id-password").addClass("validate[required,funcCall[checkPass]]");
                $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
            }
        });
        $("#id-password-again").blur(function () {
            if ($(this).val() == "") {
                $("#id-password-again").removeClass("validate[required,funcCall[checkPass],equals[id-password]]");
            } else {
                $("#id-password-again").addClass("validate[required,funcCall[checkPass],equals[id-password]]");
                $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
            }
        });
    });
</script>