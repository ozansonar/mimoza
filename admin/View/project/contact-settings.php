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
                <div class="card text-white bg-success">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Mesaj hareketleri</h4></div>
                    <div class="card-body">
                        <p class="card-text">
                            <span class="font-weight-bold"><?php echo $functions->dateLong($data->created_at) ?></span>
                            tarihinde
                            <span class="font-weight-bold"><?php echo $data->name . " " . $data->surname ?></span>
                            tarafından gönderildi.
                        </p>
						<?php if (!empty($readUser)): ?>
                            <p class="card-text">
                                <span class="font-weight-bold"><?php echo $functions->dateLong($data->read_date) ?></span>
                                tarihinde
                                <span class="font-weight-bold"><?php echo $readUser->name . " " . $readUser->surname ?></span>
                                tarafından okundu.
                            </p>
						<?php endif; ?>
						<?php if (!empty($data->reply_send_user_id)): ?>
							<?php $reply_user = $session->getUserInfo($data->reply_send_user_id); ?>
                            <p class="card-text">
                                <span class="font-weight-bold"><?php echo $functions->dateLong($data->reply_send_date) ?></span>
                                tarihinde
                                <span class="font-weight-bold"><?php echo $reply_user->name . " " . $reply_user->surname ?></span>
                                tarafından cevaplandı.
                            </p>
						<?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h3>Gönderen Bilgileri</h3>
						<?php
						$form->lang = $data->defaultLanguage->short_lang;
						$form->formNameWithoutLangCode = 1;
						echo $form->input("name", array(
							"label" => $admin_text["CONTACT_NAME"],
							"required" => 1,
							"disabled" => 1,
						), $data->pageData);
						echo $form->input("surname", array(
							"label" => $admin_text["CONTACT_SURNAME"],
							"required" => 1,
							"disabled" => 1,
						), $data->pageData);
						echo $form->input("email", array(
							"label" => $admin_text["CONTACT_EMAIL"],
							"required" => 1,
							"disabled" => 1,
						), $data->pageData);
						echo $form->input("subject", array(
							"label" => $admin_text["CONTACT_SUBJECT"],
							"required" => 1,
							"disabled" => 1,
						), $data->pageData);
						echo $form->textarea("message", array(
							"label" => $admin_text["CONTACT_MESSAGE"],
							"required" => 1,
							"disabled" => 1,
						), $data->pageData);
						?>
                    </div>
                    <div class="col-md-6 col-12">
                        <h3>Mesaja cevap ver</h3>
						<?php
						if (!empty($data->content->reply_send_user_id)) {
							echo $form->input("reply_subject", array(
								"label" => $admin_text["CONTACT_REPLY_SUBJECT"],
								"required" => 1,
								"disabled" => 1,
							), $data->pageData);
							echo $form->textarea("reply_text", array(
								"label" => $admin_text["CONTACT_REPLY_TEXT"],
								"required" => 1,
								"disabled" => 1,
							), $data->pageData);
						} else {
							echo $form->input("reply_subject", array(
								"label" => $admin_text["CONTACT_REPLY_SUBJECT"],
								"required" => 1,
							), $data->pageData);
							echo $form->textarea("reply_text", array(
								"label" => $admin_text["CONTACT_REPLY_TEXT"],
								"required" => 1,
							), $data->pageData);
						}
						?>
                    </div>
                </div>
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