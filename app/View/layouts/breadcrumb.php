<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="my-2">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo $system->url(); ?>" title="<?php echo $functions->textManager('breadcrumb_home'); ?>"><?php echo $functions->textManager('breadcrumb_home'); ?></a></li>
        <?php foreach ($data->breadcrumb as $bredcrumb): ?>
            <?php if(array_key_exists('active',$bredcrumb)): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $bredcrumb['title']; ?></li>
            <?php else: ?>
                <li class="breadcrumb-item"><a href="<?php echo $bredcrumb['url']; ?>" title="<?php echo $bredcrumb['title']; ?>"><?php echo $bredcrumb['title']; ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>