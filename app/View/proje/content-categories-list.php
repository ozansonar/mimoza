 <section id="content">
        <div class="content-wrap">
            <div class="container">
                <div class="row pricing col-mb-30 mb-4">

                    <?php
                    if(isset($data->list) && !empty($data->list)){
                        foreach ($data->list as $row){
                            $img = false;
                            if(!empty($row->img) && file_exists($constants::fileTypePath["content_categories"]["full_path"].$row->img)){
                                $img = true;
                                $img_link = $constants::fileTypePath["content_categories"]["url"].$row->img;
                            }
                            ?>
                            <div class="col-md-6 col-lg-4 mt-3">
                                <div class="pricing-box pricing-simple p-5 bg-light border-top border-project-1 text-center h-100 w-100 hvr-glow">
                                    <?php if($img): ?>
                                        <img src="<?php echo $img_link; ?>" class="card-img-top" alt="<?php echo $metaTag["keywords"]; ?>">
                                    <?php endif; ?>
                                    <div class="pricing-title">
                                        <h3><?php echo $functions->shorten($row->title,100); ?></h3>
                                    </div>
                                    <div class="pricing-action">
                                        <a href="<?php echo $siteManager->createCategoryUrl($row) ?>" class="btn btn-project-1 btn-lg"><?php echo $functions->textManager("icerik_detay_buton"); ?></a>
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
        </div>
    </section>