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
            <form action="" method="post" id="pageForm">
				<?php echo $functions->csrfToken(); ?>
                <div class="tab-content" id="custom-tabs-two-tabContent">
					<?php foreach ($projectLanguages as $project_languages_row) {
						?>
                        <div class="tab-pane fade <?php echo (int)$project_languages_row->default_lang === 1 ? "show active" : null; ?>"
                             id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel"
                             aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
							<?php
							$form->lang = $project_languages_row->short_lang;
							echo $form->input("name", array(
								"label" => $admin_text["MENU_ISMI"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("link", array(
								"label" => $admin_text["MENU_LINK"]
							), $data->pageData);
							echo $form->select("menu_type", array(
								"label" => $admin_text["MENU_TURU"],
								"required" => 1,
								"select_item" => $constants::systemMenuTypesVersion2
							), $data->pageData);
							?>
                            <div id="top_menuler_list_<?php echo $project_languages_row->short_lang; ?>"
                                 style="<?php echo isset($data->pageData[$project_languages_row->short_lang]) && $data->pageData[$project_languages_row->short_lang]["menu_type"] == 2 ? null : "display: none;"; ?>">
								<?php
								echo $form->select("top_id", array(
									"label" => $admin_text["MENU_TURU_ALT"],
									"required" => 1,
									"select_item" => $data->topMenuArray,
									"multiple_lang_select" => 1
								), $data->pageData);
								?>
                            </div>
							<?php
							echo $form->input("show_order", array(
								"label" => $admin_text["MENU_SIRA"],
								"required" => 1,
								"class" => "numeric_field",
								"order" => 1,
							), $data->pageData);
							echo $form->checkbox(array(
								"option" => array(
									array(
										"label" => $admin_text["MENU_DIS_LINK"],
										"name" => "redirect",
										"value" => 1
									)
								)
							), $data->pageData);
							?>
                            <div id="redirect_div_container_<?php echo $project_languages_row->short_lang; ?>"
                                 style="<?php echo isset($data->pageData[$project_languages_row->short_lang]) && $data->pageData[$project_languages_row->short_lang]["redirect"] == 1 ? null : "display: none;"; ?>">
								<?php
								echo $form->input("redirect_link", array(
									"label" => $admin_text["MENU_REDIRECT"],
									"required" => 1,
								), $data->pageData);
								echo $form->checkbox(array(
									"option" => array(
										array(
											"label" => $admin_text["MENU_REDIRECT_OPEN_TYPE"],
											"name" => "redirect_open_type",
											"value" => 1
										)
									)
								), $data->pageData);
								?>
                            </div>
							<?php
							echo $form->select("show_type", array(
								"label" => $admin_text["MENU_SHOW_TYPE"],
								"required" => 1,
								"select_item" => $constants::menuShowType,
							), $data->pageData);
							echo $form->select("status", array(
								"label" => $admin_text["MENU_STATUS"],
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
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
		<?php
		foreach ($projectLanguages as $project_languages_row){
		?>
        $("#id_redirect_<?php echo $project_languages_row->short_lang; ?>").change(function () {
            if ($(this).is(":checked")) {
                $("#redirect_div_container_<?php echo $project_languages_row->short_lang; ?>").slideDown();

                $("#menu_link_div_<?php echo $project_languages_row->short_lang; ?>").slideUp();
                $("#menu_link_div_<?php echo $project_languages_row->short_lang; ?> input[type='text']").val("");
            } else {
                $("#redirect_div_container_<?php echo $project_languages_row->short_lang; ?>").slideUp();
                $("#redirect_div_container_<?php echo $project_languages_row->short_lang; ?> input[type=text]").val("");
                $("#redirect_div_container_<?php echo $project_languages_row->short_lang; ?> input[type=checkbox]").prop("checked", false);

                $("#menu_link_div_<?php echo $project_languages_row->short_lang; ?>").slideDown();
            }
        });
        $("#id_menu_type_<?php echo $project_languages_row->short_lang; ?>").change(function () {
            var this_val = $(this).val();
            console.log(this_val);
            if (this_val == 2) {
                $("#top_menuler_list_<?php echo $project_languages_row->short_lang; ?>").slideDown();
            } else {
                $("#top_menuler_list_<?php echo $project_languages_row->short_lang; ?>").slideUp();
                $("#top_menuler_list_<?php echo $project_languages_row->short_lang; ?> option:selected").prop("selected", false);
            }
        });
		<?php
		}
		?>
    });
</script>
