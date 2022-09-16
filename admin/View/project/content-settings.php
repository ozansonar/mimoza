<section class="content">
    <div class="card card-danger card-outline card-outline-tabs">
        <div class="card-header">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 pr-3">
                    <h3 class="card-title">
						<?php echo $data->title; ?>
                    </h3>
                </li>
				<?php foreach ($projectLanguages as $project_languages_row) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (int)$project_languages_row->default_lang === 1 ? "active" : null; ?>"
                           id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill"
                           href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab"
                           aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>"
                           aria-selected="<?php echo (int)$project_languages_row->default_lang === 1 ? "true" : "false"; ?>">
							<?php echo $project_languages_row->lang; ?>
                            &nbsp;<i class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <form action="" method="post" id="pageForm" enctype="multipart/form-data">
				<?php echo $functions->csrfToken(); ?>
                <div class="tab-content" id="custom-tabs-two-tabContent">
					<?php foreach ($projectLanguages as $project_languages_row): ?>
                        <div class="tab-pane fade <?php echo (int)$project_languages_row->default_lang === 1 ? "show active" : null; ?>"
                             id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel"
                             aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
							<?php
                            if($data->id > 0){
                                $thisContentUrl = $siteManager->createContentUrl((object)$data->pageData[$project_languages_row->short_lang]);
                                $thisContentLinkNoUrl = $siteManager->createContentLinkNoUrl((object)$data->pageData[$project_languages_row->short_lang]);
                                ?>
                                <div class="alert alert-info">
                                    İçerik Linki: <a href="<?php echo $thisContentUrl; ?>" target="_blank" class="text-white"><?php echo $thisContentUrl; ?></a>
                                </div>
                                <div class="alert alert-warning">
                                    İçerik Linki Kopyalamak İçin: <?php echo $thisContentLinkNoUrl; ?>
                                </div>
                                <?php
                            }
							$form->lang = $project_languages_row->short_lang;
							echo $form->input("title", array(
								"label" => $admin_text["CONTENT_TITLE"],
								"required" => 1,
							), $data->pageData);
							echo $form->textarea("abstract", array(
								"label" => $admin_text["CONTENT_ACSTRACT"],
								"class" => "ckeditor",
							), $data->pageData);
							echo $form->textarea("text", array(
								"label" => $admin_text["CONTENT_TEXT"],
								"required" => 1,
								"class" => "ckeditor",
							), $data->pageData);
							echo $form->file("img", array(
								"label" => $admin_text["CONTENT_FILE"],
								"file_key" => "content",
								"delete_link" => "?id=" . $data->id . "&img_delete=" . $project_languages_row->short_lang
							), $data->pageData);
							echo $form->select("cat_id", array(
								"label" => $admin_text["CONTENT_CAT_SEC"],
								"required" => 1,
								"select_item" => $data->categorySelectArray,
								"multiple_lang_select" => 1
							), $data->pageData);
							echo $form->input("show_order", array(
								"label" => $admin_text["CONTENT_SHOW_ORDER"],
								"required" => 1,
								"class" => "numeric_field",
								"order" => 1,
							), $data->pageData);
							echo $form->inputTags("keywords", array(
								"label" => $admin_text["CONTENT_KEYWORDS"],
								"class" => "w-100",
							), $data->pageData);
							echo $form->textarea("description", array(
								"label" => $admin_text["CONTENT_DESRICTION"],
							), $data->pageData);
							echo $form->checkbox(array(
								"option" => array(
									array(
										"label" => "Anasayfada Gösterilsin",
										"name" => "index_show",
										"value" => 1
									)
								)
							), $data->pageData);
							echo $form->select("status", array(
								"label" => $admin_text["CONTENT_STATUS"],
								"required" => 1,
								"select_item" => $constants::systemStatusVersion,
							), $data->pageData);

							?>
                        </div>
					<?php endforeach ?>
                </div>
            </form>
        </div>
        <div class="card-footer text-right">
			<?php
			echo $form->button("submit", array(
				"text" => "Kaydet",
				"icon" => "fas fa-save",
				"btn_class" => "btn btn-success",
			));
			?>
        </div>
    </div>
</section>
<script src="<?php echo $system->adminPublicUrl("plugins/ckeditor/ckeditor.js"); ?>"></script>
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
    });
	<?php foreach ($projectLanguages as $project_languages_row){
	?>
    var roxyFileman = '<?php echo $system->adminPublicUrl('plugins/fileman/index.html'); ?>';
    CKEDITOR.replace('text_tr', {
        filebrowserBrowseUrl: roxyFileman,
        filebrowserImageBrowseUrl: roxyFileman + '?type=image',
        removeDialogTabs: 'link:upload;image:upload'
    });
	<?php
	} ?>
</script>