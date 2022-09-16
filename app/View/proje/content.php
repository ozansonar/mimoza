
<div class="container">
    <div class="row m-0 p-0">
        <?php
        if(!empty($data->pageData) > 0) {
            foreach($data->pageData as $page_row){
                $div_col = 12;
                $img = false;
                if(!empty($page_row->img) && file_exists($constants::fileTypePath["content"]["full_path"].$page_row->img)){
                    $img = true;
                    $img_link = $constants::fileTypePath["content"]["url"].$page_row->img;
                    $div_col = 8;
                }
                if ($data->category->show_type == 1) {
                    //içerikler satır satır gözüküyor.
                    ?>
                    <div class="card col-12 mt-3 p-0">
                        <div class="row g-0">
                            <?php if($img): ?>
                                <div class="col-md-4">
                                    <img src="<?php echo $img_link; ?>" class="img-fluid rounded-start" alt="<?php echo !empty($page_row->keywords) ? $page_row->keywords:$metaTag->keywords; ?>">
                                </div>
                            <?php endif; ?>
                            <div class="col-md-<?php echo $div_col; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $functions->shorten($page_row->title,20); ?></h5>
                                    <p class="card-text"><?php echo $functions->shorten($page_row->abstract,200); ?></p>
                                    <p class="card-text"><small class="text-muted"><?php echo $functions->dateLong($page_row->created_at); ?></small></p>
                                    <p><a href="<?php echo $siteManager->createContentUrl($page_row) ?>" class="btn btn-outline-primary"><?php echo $functions->textManager("icerik_detay_buton"); ?></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }elseif($data->category->show_type == 2) {
                    //içerikler satır satır gözüküyor
                    ?>
                    <div class="col-12 col-md-4 mt-3">
                       <div class="card h-100">
                           <?php if($img): ?>
                               <img src="<?php echo $img_link; ?>" class="card-img-top" alt="<?php echo !empty($page_row->keywords) ? $page_row->keywords:$metaTag->keywords; ?>">
                           <?php endif; ?>
                           <div class="card-body">
                               <h5 class="card-title"><?php echo $functions->shorten($page_row->title,20); ?></h5>
                               <p class="card-text"><?php echo $functions->shorten($page_row->abstract,200); ?></p>
                               <p class="card-text"><small class="text-muted"><?php echo $functions->dateLong($page_row->created_at); ?></small></p>
                               <a href="<?php echo $siteManager->createContentUrl($page_row) ?>" class="btn btn-outline-primary"><?php echo $functions->textManager("icerik_detay_buton"); ?></a>
                           </div>
                       </div>
                    </div>
                    <?php
                }
            }
            ?>
            <?php if(isset($data->pagination["paginate"])): ?>
               <div class="col-12 mt-3 pe-0">
                   <ul class="pagination float-end">
                       <?php foreach ($data->pagination["paginate"] as $paginate): ?>
                           <li class="page-item <?php echo isset($paginate["active"]) ? "active":null; ?>"><a class="page-link" href="<?php echo $siteManager->createCategoryUrl($data->category)."?q=".$paginate["value"]; ?>"><?php echo $paginate["text"]; ?></a></li>
                       <?php endforeach; ?>
                   </ul>
               </div>
            <?php endif; ?>
            <?php
        }
        else{
            ?>
            <div class="alert alert-warning mt-3">
                <?php echo $functions->textManager("icerik_bulunamadi"); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>

