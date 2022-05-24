<section class="content">
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
					<?php foreach ($projectLanguages as $project_languages_row): ?>
                        <div class="tab-pane fade <?php echo (int)$project_languages_row->default_lang === 1 ? "show active" : null; ?>"
                             id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel"
                             aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
							<?php
							$form->lang = $project_languages_row->short_lang;
							echo $form->input("name", array(
								"label" => $admin_text["GALERI_NAME"],
								"required" => 1,
							), $data->pageData);
							echo $form->input("link", array(
								"label" => $admin_text["GALERI_LINK"],
							), $data->pageData);
							echo $form->select("type", array(
								"label" => $admin_text["GALERI_TYPE"],
								"required" => 1,
								"select_item" => $constants::systemGalleryTypesVersion2,
							), $data->pageData);
							?>
                            <div id="top_gallery_div_<?php echo $project_languages_row->short_lang; ?>"
                                 style="<?php echo isset($data->pageData[$project_languages_row->short_lang]) && $data->pageData[$project_languages_row->short_lang]["type"] == 2 ? null : "display: none;"; ?>">
								<?php
								echo $form->select("top_id", array(
									"label" => $admin_text["GALERI_TOP_TYPE"],
									"required" => 1,
									"select_item" => $data->galleryDataArray,
								), $data->pageData);
								?>
                            </div>
							<?php
							echo $form->input("show_order", array(
								"label" => $admin_text["GALERI_ORDER"],
								"required" => 1,
								"class" => "numeric_field",
								"order" => 1,
							), $data->pageData);
							echo $form->file("img", array(
								"label" => $admin_text["GALERI_IMG"],
								"file_key" => "gallery",
							), $data->pageData);
							echo $form->select("status", array(
								"label" => $admin_text["GALERI_STATUS"],
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
	<?php foreach ($projectLanguages as $project_languages_row): ?>
    $("#id_type_<?php echo $project_languages_row->short_lang; ?>").change(function () {
        let this_val = $(this).val();
        if (this_val == 2) {
            $("#top_gallery_div_<?php echo $project_languages_row->short_lang; ?>").slideDown();
        } else {
            $("#top_gallery_div_<?php echo $project_languages_row->short_lang; ?>").slideUp();
            $("#top_gallery_div_<?php echo $project_languages_row->short_lang; ?> option:selected").prop("selected", false);
        }
    });
	<?php endforeach ?>
</script>