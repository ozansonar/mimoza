
<div class="container py-3">
    <?php if(isset($data->breadcrumb) && !empty($data->breadcrumb) && is_array($data->breadcrumb)): ?>
        <?php include($system->path('app/View/layouts/breadcrumb.php')); ?>
    <?php endif; ?>
    <h1 class="mb-3"><?php echo $data->pageData->title; ?></h1>
    <?php if(isset($data->imgLink)  && $data->imgLink): ?>
        <a href="<?php echo $data->imgLink; ?>" class="fancybox-gallery">
            <img src="<?php echo $data->imgLink; ?>" class="img-fluid" alt="<?php echo $metaTag->keywords; ?>" />
        </a>
    <?php endif; ?>
    <?php echo str_replace("../",$system->urlWithoutLanguage(),$data->pageData->text); ?>
    <?php if((int)$data->pageData->comment === 1): ?>
        <?php include($system->path('app/View/layouts/comment.php')); ?>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function(){
        $('.fancybox-gallery').fancybox();
    });
</script>
