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
				$form->lang = $data->defaultLanguage->short_lang;
				$form->formNameWithoutLangCode = 1;
				echo $functions->csrfToken();
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
				echo $form->file("img", array(
					"label" => $admin_text["HESAP_RESIM"],
					"file_key" => "user_image",
					"delete_link" => "?id=" . $data->pageData[$data->defaultLanguage->short_lang]["id"] . "&img_delete=1"
				), $data->pageData);
				echo $form->input("password", array(
					"label" => $admin_text["HESAP_PASSWORD"],
					"class" => "validate[maxSize[50],minSize[10]]",
					"type" => "password"
				), $data->pageData);
				echo $form->input("password_again", array(
					"label" => $admin_text["HESAP_PASSWORD_AGAIN"],
					"class" => "validate[maxSize[50],minSize[10]]",
					"type" => "password"
				), $data->pageData);
				echo $form->select("theme", array(
					"label" => $admin_text["ADMIN_THEMA"],
					"required" => 1,
					"select_item" => $constants::adminPanelTheme,
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
    });
</script>