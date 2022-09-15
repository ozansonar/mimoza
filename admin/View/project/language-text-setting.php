<section class="content">
    <div class="card card-danger card-outline card-outline-tabs">
        <div class="card-header">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 pr-3"><h3 class="card-title"><?php echo $data->title; ?></h3></li>
				<?php foreach ($projectLanguages as $project_languages_row): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (int)$project_languages_row->default_lang === 1 ? "active" : null; ?>"
                           id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill"
                           href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab"
                           aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>"
                           aria-selected="<?php echo (int)$project_languages_row->default_lang === 1 ? "true" : "false"; ?>">
							<?php echo $project_languages_row->lang; ?>
                            &nbsp;<i class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                    </li>
				<?php endforeach ?>
            </ul>
        </div>
        <div class="card-body">
			<?php echo $functions->csrfToken(); ?>
            <div class="tab-content" id="custom-tabs-two-tabContent">
				<?php foreach ($projectLanguages as $project_languages_row): ?>
                    <div class="tab-pane fade <?php echo (int)$project_languages_row->default_lang === 1 ? "show active" : null; ?>"
                         id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel"
                         aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
                        <form action="" method="post" id="pageForm_<?php echo $project_languages_row->short_lang; ?>"
                              enctype="multipart/form-data">
                            <?php echo $functions->getCsrfToken(); ?>
							<?php
							$form->lang = $project_languages_row->short_lang;
							foreach ($data->languageTextManager as $data->languageTextManager_key => $data->languageTextManager_value): ?>
                                <div class="border mb-4 p-2">
                                    <h4><?php echo $data->languageTextManager_value["title"]; ?></h4>
									<?php foreach ($data->languageTextManager_value["form"] as $form_row):
										if (isset($form_row["type"]) && $form_row["type"] === "textarea"):
											$class = $form_row["class"] ?? "";
											echo $form->textarea($form_row["name"], array(
												"label" => "--" . $form_row["label"],
												"class" => $class,
											), $data->pageData);
										else :
											echo $form->input($form_row["name"], array(
												"label" => "--" . $form_row["label"],
												"class" => "validate[minSize[2]]",
											), $data->pageData);
										endif;
									endforeach ?>
                                </div>
							<?php endforeach ?>
                            <input type="hidden" name="data_lang"
                                   value="<?php echo $project_languages_row->short_lang; ?>">
                            <div class="col-12 text-right pr-0">
								<?php echo $form->button("submit", array(
									"text" => "Kaydet",
									"icon" => "fas fa-save",
									"btn_class" => "btn btn-success",
									"form_name" => "pageForm_" . $project_languages_row->short_lang
								)); ?>
                            </div>
                        </form>
                    </div>
				<?php endforeach ?>
            </div>
        </div>
</section>
<script>
    $(document).ready(function () {
		<?php foreach ($projectLanguages as $project_languages_row): ?>
            $("form#pageForm_<?php echo $project_languages_row->short_lang;?>").validationEngine({
                promptPosition: "bottomLeft",
                scroll: false
            });
		<?php endforeach ?>
    });
</script>