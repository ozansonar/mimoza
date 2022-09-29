<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="javascript:void(0)" class="h1">Giriş</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg"><?php echo $settings->project_name ?></p>
            <form action="" method="post" id="pageForm">
				<?php
				echo $functions->csrfToken();
				echo $form->input("name", array(
					"label" => "Kullanıcı Adı",
					"input_group" => 1,
					"group_icon" => "fas fa-envelope",
					"required" => 1,
                    "class" => " validate[custom[email]] "
				), $data->pageData);
				echo $form->input("password", array(
					"type" => "password",
					"label" => "Parola",
					"input_group" => 1,
					"group_icon" => "fas fa-lock",
					"required" => 1,
				));
				?>
                <?php if(defined("LIVE_MODE")): ?>
                    <input type="hidden" name="recaptcha_response" id="id_recaptcha_response">
                <?php endif; ?>
                <div class="row">
                    <div class="col-4 offset-8">
						<?php echo $form->button("submit", array(
							"text" => "Giriş",
							"class" => "btn-block",
							"icon" => "fas fa-save"
						)) ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(defined("LIVE_MODE")): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo CAPTCHA_SITE_KEY; ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute("<?php echo CAPTCHA_SITE_KEY; ?>", {action: 'request'})
                .then(function(captcha_key) {
                    var id_recaptcha_response = document.getElementById('id_recaptcha_response');
                    id_recaptcha_response.value = captcha_key;
                });
        });
    </script>
<?php endif; ?>

<script>
    $(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
    });
</script>