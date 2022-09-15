
<div class="container py-3">
    <h1 class="mb-3"><?php echo $data->pageData->title; ?></h1>
    <?php if(isset($data->imgLink)  && $data->imgLink): ?>
        <a href="<?php echo $data->imgLink; ?>" class="fancybox-gallery">
            <img src="<?php echo $data->imgLink; ?>" class="img-fluid" alt="<?php echo $metaTag->keywords; ?>" />
        </a>
    <?php endif; ?>
    <?php echo str_replace("../",$system->url(),$data->pageData->text); ?>
</div>

<script>
    $(document).ready(function(){
        $('.fancybox-gallery').fancybox();
    });
</script>
