
<div class="container">
    <div class="row p-0 m-0">
        <?php

		if($data->count > 0): ?>
            <div class="alert alert-success mt-3">
                <?php
                    $sonuc_text = $functions->textManager("arama_icerik_sonuc_bulundu");
                    $sonuc_text = str_replace("#aranan#","<b>\"".$data->text."\"</b>",$sonuc_text);
                    $sonuc_text = str_replace("#sonuc#","<b>".$data->count."</b>",$sonuc_text);
                    echo $sonuc_text;
                ?>
            </div>
            <?php if(!empty($data->contentQueryQata)): ?>
                <h2 class="mt-2"><?php echo $functions->textManager("arama_bulunan_content"); ?></h2>
                <?php foreach ($data->contentQueryQata as $row):
                    $content_categories_row = [];
                    $content_categories_row["id"] = $row->cat_id;
                    $content_categories_row["link"] = $row->c_link;
                    $img = false;
                    if(!empty($row->img) && file_exists($constants::fileTypePath["content"]["full_path"].$row->img)){
                        $img = true;
                        $img_link = $constants::fileTypePath["content"]["url"].$row->img;
                    }
                    ?>
                    <div class="col-12 col-md-4 mt-3">
                        <div class="card h-100">
                            <?php if($img): ?>
                                <img src="<?php echo $img_link; ?>" class="card-img-top" alt="<?php echo $metaTag->keywords; ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $functions->shorten($row->title,20); ?></h5>
                                <p class="card-text"><?php echo $functions->shorten($row->abstract,200); ?></p>
                                <a href="<?php echo $siteManager->createContentUrl((object)$row) ?>" class="btn btn-outline-primary"><?php echo $functions->textManager("icerik_detay_buton"); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(!empty($data->pageDataCategories)): ?>
                <h2 class="mt-2"><?php echo $functions->textManager("arama_bulunan_category"); ?></h2>
                <?php foreach ($data->pageDataCategories as $row):
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
                                <h5 class="card-title"><?php echo $functions->shorten($row->title,20); ?></h5>
                                <a href="<?php echo $siteManager->createCategoryUrl((object)$row) ?>" class="btn btn-outline-primary"><?php echo $functions->textManager("icerik_detay_buton"); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(!empty($data->pageQueryData)): ?>
                <h2 class="mt-2"><?php echo $functions->textManager("arama_bulunan_sayfa"); ?></h2>
                <?php foreach ($data->pageQueryData as $row):
                    $img = false;
                    if(!empty($row->img) && file_exists($constants::fileTypePath["page_image"]["full_path"].$row->img)){
                        $img = true;
                        $img_link = $constants::fileTypePath["page_image"]["url"].$row->img;
                    }
                    ?>
                    <div class="col-12 col-md-4 mt-3">
                        <div class="card h-100">
                            <?php if($img): ?>
                                <img src="<?php echo $img_link; ?>" class="card-img-top" alt="<?php echo $metaTag->keywords; ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $functions->shorten($row->title,20); ?></h5>
                                <p class="card-text"><?php echo $functions->shorten($row->abstract,200); ?></p>
                                <a href="<?php echo $system->url($row->link); ?>" class="btn btn-outline-primary"><?php echo $functions->textManager("icerik_detay_buton"); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-warning mt-3">
                <?php echo $functions->textManager("arama_icerik_bulunamadi"); ?>
            </div>
        <?php endif; ?>
    </div>
</div>