
<div class="container py-3">
    <h1><?php echo $data->page_data->title; ?></h1>
    <?php
    if(!empty($data->page_data->img) && file_exists($fileTypePath["page_image"]["full_path"].$data->page_data->img)){
       ?>
        <img src="<?php echo $img_link = $fileTypePath["page_image"]["url"].$data->page_data->img; ?>" class="img-fluid my-2" alt="<?php echo $metaTag->keywords; ?>">
       <?php
    }
    ?>
    <?php echo str_replace("../",$functions->site_url(),$data->page_data->text); ?>
</div>
