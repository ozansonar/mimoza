<?php require $system->view('static/html-start'); ?>
<?php require $system->view('static/header'); ?>
<div class="container">
    <div class="row m-0 p-0">
        <?php
        if(!empty($selectQuery)){
            foreach ($selectQuery as $row){
                $img = false;
                if(!empty($row->img) && file_exists($constants::fileTypePath["content_categories"]["full_path"].$row->img)){
                    $img = true;
                    $img_link = $constants::fileTypePath["content_categories"]["url"].$row->img;
                }
                ?>
                <div class="col-12 col-md-4 mt-3">
                    <div class="card h-100">
                        <?php if($img): ?>
                            <img src="<?php echo $img_link; ?>" class="card-img-top" alt="<?php echo $metaTag->keywords; ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $functions->kisalt($row->title,20); ?></h5>
                            <a href="<?php echo $functions->createContentUrl($row) ?>" class="btn btn-outline-primary"><?php echo $functions->textManager("icerik_detay_buton"); ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }else{
            ?>
            <div class="alert alert-warning mt-3">
                <?php echo $functions->textManager("icerik_bulunamadi"); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php require $system->view('static/footer'); ?>
<?php require $system->view('static/html-end'); ?>