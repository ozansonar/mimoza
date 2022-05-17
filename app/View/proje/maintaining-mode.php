<div class="container my-2 d-flex align-items-center justify-content-center flex-column" style="height: 100vh">
    <div class="col-8 col-md-4">
        <?php if(!empty($settings->header_logo) && file_exists($constants::fileTypePath["project_image"]["full_path"].$settings->header_logo)): ?>
            <img alt="<?php echo $settings->project_name; ?>" class="img-fluid" src="<?php echo $constants::fileTypePath["project_image"]["url"].$settings->header_logo;  ?>">
        <?php endif; ?>
    </div>
    <h1><?php echo $functions->textManager("site_bakimda_baslik"); ?></h1>
    <?php echo $functions->textManager("site_bakimda_aciklama"); ?>
</div>

