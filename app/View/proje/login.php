<div class="container-fluid text-color-mavi">
    <div class="container  my-4 font-roboto-bold d-flex align-items-center justify-content-center flex-column default-border py-2">
        <div class="col-12 text-center">
            <h2><?php echo $metaTag->title; ?></h2>
        </div>
        <div class="col-md-6 col-12 ">
            <form action="" method="post" id="LoginForm">
                <?php echo $functions->csrfToken(); ?>
                <?php
                echo $data->form->input("email",array(
                    "label" => $functions->textManager("giris_email"),
                    "required" => 1,
                    "type" => "email",
                    "class" => "validate[custom[email]]",
                ),$data->pageData);
                echo $data->form->input("password",array(
                    "label" => $functions->textManager("giris_sifre"),
                    "required" => 1,
                    "type" => "password",
                    "class" => "validate[email]",
                ));
                ?>
                <?php if(defined("LIVE_MODE")): ?>
                    <input type="hidden" name="recaptcha_response" id="id_recaptcha_response">
                <?php endif; ?>
                <div class="d-grid gap-2">
                    <?php
                    echo $data->form->button("save",array(
                        "text" => $functions->textManager("giris_button"),
                        "value" => 1
                    ));
                    ?>
                    <a href="javascript:void(0);" class="btn btn-info mb-2" id="forgot-password-link">
                        <?php echo $functions->textManager("giris_sifremi_unuttum_button"); ?>
                    </a>
                </div>

            </form>
            <div class="col-12 p-0" id="forgot-password-form" style="display: none">
                <div class="col-12 text-center">
                    <h2><?php echo $functions->textManager("giris_sifremi_unuttum_title"); ?></h2>
                </div>
                <form action="" method="post" id="ForgotPassForm">
                    <?php
                    echo $data->form->input("forgot_email",array(
                        "label" => $functions->textManager("giris_sifremi_unuttum_email"),
                        "required" => 1,
                        "type" => "email",
                        "class" => "validate[custom[email]]",
                    ));
                    ?>
                    <?php if(defined("LIVE_MODE")): ?>
                        <input type="hidden" name="recaptcha_response2" id="id_recaptcha_response2">
                    <?php endif; ?>
                    <input type="hidden" name="ajax_request" value="99">
                    <?php echo $functions->getCsrfToken(); ?>
                    <div class="d-grid gap-2">
                        <?php
                        echo $data->form->button("save",array(
                            "text" => $functions->textManager("giris_sifremi_unuttum_kaydet_button"),
                            "value" => 1
                        ));
                        ?>
                    </div>
                </form>
            </div>
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
        grecaptcha.ready(function() {
            grecaptcha.execute("<?php echo CAPTCHA_SITE_KEY; ?>", {action: 'request'})
                .then(function(captcha_key) {
                    var id_recaptcha_response = document.getElementById('id_recaptcha_response2');
                    id_recaptcha_response.value = captcha_key;
                });
        });
    </script>
<?php endif; ?>

<script>
    $(function () {
        $("form#LoginForm").validationEngine();
        $("form#ForgotPassForm").validationEngine('attach', {
            onValidationComplete: function(form, status){
                if(status == true){
                    var data = new FormData($("#ForgotPassForm")[0]);
                    $("#loader").show();
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo $system->url("ajax/".$settings->{'sifremi_unutum_prefix_' . $_SESSION["lang"]}); ?>",
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response){
                            $("#loader").hide();
                            if (response.success){
                                AlertMessage("success","Link Gönderildi",response.success,"TAMAM",1,response.url,response.timer);
                            }
                            if(response.reply){
                                AlertMessage("error","HATA",response.reply,"TAMAM",0);
                            }
                        },
                        dataType: 'json'
                    });
                }
            }
        });

        //şifremi unuttum kısmı
        $("#forgot-password-link").click(function () {
            $("#forgot-password-form").toggle("slow");
        });
    })
</script>
