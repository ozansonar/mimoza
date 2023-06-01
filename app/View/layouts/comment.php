<div class="col-12">
    <h2><?php echo $functions->textManager('comment_title'); ?></h2>
    <form action="" id="pageForm">
        <input type="hidden" name="id" value="<?php echo $data->pageData->id; ?>">
        <input type="hidden" name="type" value="1">
        <?php
        echo $functions->csrfToken();
        ?>
        <div class="row">
            <div class="col-12 col-md-4">
                <?php echo $form->input('name',[
                    'label' => $functions->textManager('comment_name'),
                    'required' => 1,
                    'class' => 'validate[minSize[2],maxSize[30]]'
                ]); ?>
            </div>
            <div class="col-12 col-md-4">
                <?php echo $form->input('surname',[
                    'label' => $functions->textManager('comment_surname'),
                    'required' => 1,
                    'class' => 'validate[minSize[2],maxSize[30]]'
                ]); ?>
            </div>
            <div class="col-12 col-md-4">
                <?php echo $form->input('email',[
                    'label' => $functions->textManager('comment_email'),
                    'required' => 1,
                    'class' => 'validate[custom[email]]'
                ]); ?>
            </div>
        </div>
        <?php
        echo $form->textarea('comment',[
            'label' => $functions->textManager('comment_comment'),
            'class' => 'validate[minSize[10]]',
            'required' => 1
        ]);
        echo $form->button('save',[
            'text' => $functions->textManager('comment_save')
        ]);
        ?>
        <?php if(defined("LIVE_MODE")): ?>
            <input type="hidden" name="recaptcha_response" id="id_recaptcha_response">
        <?php endif; ?>
    </form>
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
    $(document).ready(function () {
        $("form#pageForm").validationEngine('attach', {
            "promptPosition" : "bottomLeft",
            scroll: false,
            onValidationComplete: function(form, status){
                if(status == true){
                    $("#id_save").attr('disabled', 'disabled');
                    $("#id_save").text("<?php echo $functions->textManager("comment_btn_gonderiliyor"); ?>");
                    var data = $("form#pageForm").serialize();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $system->url("ajax/comment"); ?>',
                        data: data,
                        success: function(response){
                            $("#id_save").removeAttr('disabled');
                            if (response.success){
                                $("form#pageForm")[0].reset();
                                AlertMessage("success","<?php echo $functions->textManager('comment_gonderildi_modal_title'); ?>",response.success,"<?php echo $functions->textManager("comment_gonderildi_modal_buton"); ?>",0);
                                $("#id_save").text("<?php echo $functions->textManager("comment_btn_gonderildi"); ?>");
                            }
                            if(response.reply){
                                AlertMessage("error","<?php echo $functions->textManager('comment_gonderilemedi_modal_title'); ?>",response.reply,"<?php echo $functions->textManager("comment_gonderilemedi_modal_buton"); ?>",0);
                            }
                        },
                        dataType: 'json'
                    });

                }
            }
        });
    });
</script>