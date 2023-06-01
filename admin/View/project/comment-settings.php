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
            <form enctype="multipart/form-data" method="post" id="pageForm">
                <h3>Gönderen Bilgileri</h3>
                <?php
                echo $functions->csrfToken();
                $form->formNameWithoutLangCode = 1;
                echo $form->input("name", array(
                    "label" => 'Ad',
                    "required" => 1,
                    'class' => 'validate[minSize[2],maxSize[30]]'
                ), $data->pageData);
                echo $form->input("surname", array(
                    "label" => 'Soyad',
                    "required" => 1,
                    'class' => 'validate[minSize[2],maxSize[30]]'
                ), $data->pageData);
                echo $form->input("email", array(
                    "label" => 'E-posta',
                    "required" => 1,
                    'class' => 'validate[custom[email],maxSize[100]]'
                ), $data->pageData);
                echo $form->textarea("comment", array(
                    "label" => 'Yorum',
                    "required" => 1,
                    "class" => "validate[minSize[10],maxSize[2000]]"
                ), $data->pageData);
                echo $form->select("status", array(
                    "label" => 'Durum',
                    "required" => 1,
                    "select_item" => $constants::systemStatusVersion,
                ), $data->pageData);
                ?>
            </form>
        </div>
        <div class="card-footer text-right">
			<?php if (empty($data->content->reply_send_user_id)) {
				echo $form->button("submit", array(
					"text" => "Cevapla",
					"icon" => "fas fa-save",
					"btn_class" => "btn btn-success",
				));
			} ?>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
    });
</script>