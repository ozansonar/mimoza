
<div class="container py-3">
    <?php if(isset($data->breadcrumb) && !empty($data->breadcrumb) && is_array($data->breadcrumb)): ?>
        <?php include($system->path('app/View/layouts/breadcrumb.php')); ?>
    <?php endif; ?>
    <h1><?php echo $data->page_data->title; ?></h1>
    <?php
    if(!empty($data->page_data->img) && file_exists($constants::fileTypePath["page_image"]["full_path"].$data->page_data->img)){
       ?>
        <a href="<?php echo $img_link = $constants::fileTypePath["page_image"]["url"].$data->page_data->img; ?>" class="fancybox-gallery">
            <img src="<?php echo $img_link = $constants::fileTypePath["page_image"]["url"].$data->page_data->img; ?>" class="img-fluid my-2" alt="<?php echo $metaTag->keywords; ?>" />
        </a>
       <?php
    }
    ?>
    <?php echo str_replace("../",$system->urlWithoutLanguage(),$data->page_data->text); ?>
</div>
<script>
    $(document).ready(function(){
        $('.fancybox-gallery').fancybox();
    });
</script>