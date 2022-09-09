<div class="container">
    <div class="row">
        <div class="col-12 col-md-8">
            <h2 class="font-weight-bold mt-2 mb-0"><?php echo $functions->textManager("contact_form_top_title"); ?></h2>
            <p class="mb-4"><?php echo $functions->textManager("contact_form_top_abstract"); ?></p>
            <form action="" method="post" id="pageForm">
                <?php echo $functions->csrfToken(); ?>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <?php echo $form->input("name",array(
                            "label" => $functions->textManager("contact_name"),
                            "required" => 1,
                        )); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php echo $form->input("surname",array(
                            "label" => $functions->textManager("contact_surname"),
                            "required" => 1,
                        )); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php echo $form->input("email",array(
                            "label" => $functions->textManager("contact_form_email"),
                            "type" => "email",
                            "required" => 1,
                        )); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php echo $form->input("phone",array(
                            "label" => $functions->textManager("contact_form_telefon"),
                            "type" => "number",
                        )); ?>
                    </div>
                    <div class="col-12">
                        <?php echo $form->input("subject",array(
                            "label" => $functions->textManager("contact_subject"),
                        )); ?>
                    </div>
                    <div class="col-12">
                        <?php echo $form->textarea("message",array(
                            "label" => $functions->textManager("contact_message"),
                            "required" => 1,
                        )); ?>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" id="send-btn" class="btn btn-success"><?php echo $functions->textManager("contact_button"); ?></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-4">
            <h3 class="mt-2"><?php echo $functions->textManager("contact_right_title"); ?></h3>
            <div class="list-group">
                <?php if(isset($settings->adres) && !empty($settings->adres)): ?>
                    <li class="list-group-item"><i class="fas fa-map-marker-alt top-6"></i> <strong><?php echo $functions->textManager("contact_adres"); ?></strong> <?php echo $settings->adres; ?></li>
                <?php endif; ?>
                <?php if(isset($settings->phone) && !empty($settings->phone)): ?>
                    <li class="list-group-item"><i class="fas fa-phone top-6"></i> <strong><?php echo $functions->textManager("contact_telefon"); ?></strong> <a href="tel:<?php echo $settings->phone; ?>"><?php echo $functions->phoneFormat($settings->phone); ?></a></li>
                <?php endif; ?>
                <?php if(isset($settings->whatsapp) && !empty($settings->whatsapp)): ?>
                    <li class="list-group-item"><i class="fab fa-whatsapp top-6"></i> <strong><?php echo $constants::socialMedia["whatsapp"]["title"]; ?></strong> <a href="<?php echo $constants::socialMedia["whatsapp"]["url"].$settings->whatsapp; ?>"><?php echo $functions->phoneFormat($settings->whatsapp); ?></a></li>
                <?php endif; ?>
                <?php if(isset($settings->site_mail) && !empty($settings->site_mail)): ?>
                    <li class="list-group-item"><i class="fas fa-envelope top-6"></i> <strong><?php echo $functions->textManager("contact_email"); ?></strong> <a href="mailto:<?php echo $settings->site_mail ?>"><?php echo $settings->site_mail; ?></a></li>
                <?php endif; ?>
            </div>
            <h3 class="mt-2"><?php echo $functions->textManager("contact_sosyal_medya_baslik"); ?></h3>
            <ul class="pagination border p-3">
                <?php if(isset($settings->whatsapp) && !empty($settings->whatsapp)): ?>
                    <li class="page-item"><a href="<?php echo $constants::socialMedia["whatsapp"]["url"].$settings->whatsapp; ?>" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp text-2"></i></a></li>
                <?php endif; ?>
                <?php if(isset($settings->facebook) && !empty($settings->facebook)): ?>
                    <li class="page-item"><a href="<?php echo $constants::socialMedia["facebook"]["url"].$settings->facebook; ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f text-2"></i></a></li>
                <?php endif; ?>
                <?php if(isset($settings->instagram) && !empty($settings->instagram)): ?>
                    <li class="page-item"><a href="<?php echo $constants::socialMedia["instagram"]["url"].$settings->instagram; ?>" target="_blank" title="Instagram"><i class="fab fa-instagram text-2"></i></a></li>
                <?php endif; ?>
                <?php if(isset($settings->youtube) && !empty($settings->youtube)): ?>
                    <li class="page-item"><a href="<?php echo $constants::socialMedia["youtube"]["url"].$settings->youtube; ?>" target="_blank" title="Youtube"><i class="fab fa-youtube text-2"></i></a></li>
                <?php endif; ?>
                <?php if(isset($settings->twitter) && !empty($settings->twitter)): ?>
                    <li class="page-item"><a href="<?php echo $constants::socialMedia["twitter"]["url"].$settings->twitter; ?>" target="_blank" title="Twitter"><i class="fab fa-twitter text-2"></i></a></li>
                <?php endif; ?>
                <?php if(isset($settings->vk) && !empty($settings->vk)): ?>
                    <li class="page-item mt-2"><a href="<?php echo $constants::socialMedia["vk"]["url"].$settings->vk; ?>" target="_blank" title="Vk"><i class="fab fa-vk text-2"></i></a></li>
                <?php endif; ?>
                <?php if(isset($settings->telegram) && !empty($settings->telegram)): ?>
                    <li class="page-item mt-2"><a href="<?php echo $constants::socialMedia["telegram"]["url"].$settings->telegram; ?>" target="_blank" title="Telegram"><i class="fab fa-telegram text-2"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script>
    var url = "<?php echo $system->url("ajax/contact-api"); ?>";
    $(document).ready(function(){
        //$("form#myForm").validationEngine({promptPosition : "bottomLeft", scroll: false});
        $("form#pageForm").validationEngine('attach', {
            "promptPosition" : "bottomLeft",
            scroll: false,
            onValidationComplete: function(form, status){
                if(status == true){
                    $("#send-btn").attr('disabled', 'disabled');
                    $("#send-btn").text("<?php echo $functions->textManager("contact_button_gonderiliyor"); ?>");
                    var data = $("form#pageForm").serialize();
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                        success: function(response){
                            $("#send-btn").removeAttr('disabled');
                            if (response.success){
                                $("form#pageForm")[0].reset();
                                AlertMessage("success","Mesaj Gönderildi",response.success,"<?php echo $functions->textManager("contact_uyari_buton"); ?>",0);
                                $("#send-btn").text("<?php echo $functions->textManager("contact_button_gonderildi"); ?>");
                            }
                            if(response.reply){
                                AlertMessage("error","Hata",response.reply,"<?php echo $functions->textManager("contact_uyari_buton"); ?>",0);
                            }
                        },
                        dataType: 'json'
                    });

                }
            }
        });
    });
</script>
