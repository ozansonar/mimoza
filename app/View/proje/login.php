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
