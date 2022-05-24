<section class="content">
	<?php if (!empty($sub_title)): ?>
        <div class="alert alert-info alert-dismissible">
            <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
			<?php echo $sub_title; ?>
        </div>
	<?php endif; ?>
    <div class="card card-danger card-outline card-outline-tabs">
        <div class="card-header">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 pr-3"><h3 class="card-title"><?php echo $data->title; ?></h3></li>
				<?php foreach ($projectLanguages as $project_languages_row) {
					?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (int)$project_languages_row->default_lang === 1 ? "active" : null; ?>"
                           id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill"
                           href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab"
                           aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>"
                           aria-selected="<?php echo (int)$project_languages_row->default_lang === 1 ? "true" : "false"; ?>"><?php echo $project_languages_row->lang; ?>
                            &nbsp;<i class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                    </li>
					<?php
				} ?>
            </ul>
        </div>
        <div class="card-body">
            <form action="" method="post" id="pageForm" enctype="multipart/form-data">
				<?php echo $functions->csrfToken(); ?>
                <div class="tab-content" id="custom-tabs-two-tabContent">
					<?php foreach ($projectLanguages as $project_languages_row) {
						?>
                        <div class="tab-pane fade <?php echo (int)$project_languages_row->default_lang === 1 ? "show active" : null; ?>"
                             id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel"
                             aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
							<?php
							$form->lang = $project_languages_row->short_lang;
							echo $form->input("title", array(
								"label" => $admin_text["SLIDER_TITLE"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("link", array(
								"label" => $admin_text["SLIDER_LINK"],
								"description" => $admin_text["MENU_DESCRIPTION"]
							), $data->pageData);
							echo $form->checkbox(array(
								"option" => array(
									array(
										"label" => $admin_text["SLIDER_SITE_DISI_LINK"],
										"name" => "site_disi_link",
										"value" => 1
									)
								)
							), $data->pageData);
							?>
                            <div id="id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?>"
                                 style="<?php echo isset($data->pageData[$project_languages_row->short_lang]) && $data->pageData[$project_languages_row->short_lang]["site_disi_link"] == 1 ? null : "display: none;"; ?>">
								<?php
								echo $form->input("back_link", array(
									"label" => $admin_text["SLIDER_DIS_LINK"],
									"required" => 1,
								), $data->pageData);
								echo $form->checkbox(array(
									"option" => array(
										array(
											"label" => $admin_text["SLIDER_DIS_LINK_YENI_SEKME"],
											"name" => "yeni_sekme",
											"value" => 1
										)
									)
								), $data->pageData);
								?>
                            </div>
							<?php
							echo $form->textarea("abstract", array(
								"label" => $admin_text["SLIDER_SHORT_TEXT"],
								"class" => "ckeditor",
							), $data->pageData);
							echo $form->textarea("text", array(
								"label" => $admin_text["SLIDER_TEXT"],
								"required" => 1,
								"class" => "ckeditor",
							), $data->pageData);
							echo $form->file("img", array(
								"label" => $admin_text["SLIDER_IMG"],
								"file_key" => "slider",
							), $data->pageData);
							echo $form->input("show_order", array(
								"label" => $admin_text["SLIDER_SHOW_ORDER"],
								"required" => 1,
								"class" => "numeric_field",
								"order" => 1,
							), $data->pageData);
							echo $form->select("status", array(
								"label" => $admin_text["SLIDER_STATUS"],
								"required" => 1,
								"select_item" => $constants::systemStatusVersion,
							), $data->pageData);

							?>
                        </div>
						<?php
					} ?>
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
<script src="<?php echo $system->adminPublicUrl("plugins/ckeditor/ckeditor.js"); ?>"></script>
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
    });
    let roxyFileman = '<?php echo $system->adminPublicUrl('plugins/fileman/index.html'); ?>';
	<?php
	foreach ($projectLanguages as $project_languages_row){
	?>
    CKEDITOR.replace('text_<?php echo $project_languages_row->short_lang; ?>', {
        filebrowserBrowseUrl: roxyFileman,
        filebrowserImageBrowseUrl: roxyFileman + '?type=image',
        removeDialogTabs: 'link:upload;image:upload'
    });
    $("#id_site_disi_link_<?php echo $project_languages_row->short_lang; ?>").change(function () {
        if ($(this).is(':checked')) {
            $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?>").slideDown();
        } else {
            $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?> input[type='checkbox']").prop("checked", false);
            $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?> input[type='text']").val("");
            $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?>").slideUp();
        }
    });
	<?php
	}
	?>
</script>