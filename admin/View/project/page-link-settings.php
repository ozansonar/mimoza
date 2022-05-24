<section class="content">
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
			$form->lang = $data->defaultLanguage->short_lang;
			$form->formNameWithoutLangCode = 1;
			echo $form->input("url", array(
				"label" => $admin_text["PG_SET_LINK"],
				"required" => 1,
			), $data->pageData);
			echo $form->select("controller", array(
				"label" => $admin_text["PG_SET_FILE"],
				"required" => 1,
				"select_item" => $data->pl_controller,
			), $data->pageData);
			echo $form->select("lang", array(
				"label" => $admin_text["PG_SET_LANG"],
				"required" => 1,
				"select_item" => $data->pageLanguages,
			), $data->pageData);
			echo $form->select("status", array(
				"label" => $admin_text["PG_SET_STATUS"],
				"required" => 1,
				"select_item" => $constants::systemStatusVersion,
			), $data->pageData);
			?>
        </form>
    </div>
    <!-- /.card-body -->
    <div class="card-footer text-right">
		<?php echo $form->button("submit", array(
			"text" => "Kaydet",
			"icon" => "fas fa-save",
			"btn_class" => "btn btn-success",
		)); ?>
    </div>
</section>
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
    });

</script>