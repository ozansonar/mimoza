<?php if(isset($data->slider) && !empty($data->slider)): ?>
<div class="container-fluid px-0">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
			<?php foreach ($data->slider as $slider): ?>
                <button type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide-to="<?php echo $slider["count"]; ?>"
                        class="<?php echo $slider["active"]; ?>"
                        aria-current="true" aria-label="Slide <?php echo $slider["count"]; ?>"></button>
			<?php endforeach; ?>
        </div>
        <div class="carousel-inner">
			<?php foreach ($data->slider as $slider): ?>
                <div class="carousel-item <?php echo $slider["active"]; ?>">
                    <img src="<?php echo $slider["img"]; ?>" class="d-block w-100"
                         alt="<?php echo $settings->keywords; ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo $slider["title"]; ?></h5>
                        <p><?php echo $slider["abstract"]; ?></p>
                        <a href="<?php echo $slider["link"]; ?>" class="btn btn-dark">Detaylar</a>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if(isset($data->content) && !empty($data->content)): ?>
<section class="container mt-4">
    <div class="row">
        <h2><?php echo $functions->textManager("home_content_head") ?></h2>
    </div>
    <div class="row">
		<?php foreach ($data->content as $content) : ?>
            <div class="col-12 col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <a href="<?php echo $content['link'] ?>" class="text-decoration-none">
                        <img src="<?php echo $content['img'] ?>" class="card-img-top index-card-img"
                             alt="<?php echo $content['keywords'] . ' photo' ?>">
                    </a>
                    <div class="card-body">
                        <h4><?php echo $content['title'] ?></h4>
                        <p class="card-text"><?php echo $content['abstract'] ?></p>
                        <a href="<?php echo $content['link'] ?>"
                           class="custom-btn card-text text-primary text-decoration-none">
							<?php echo $functions->textManager('home_content_button_text') ?>
                        </a>
                        <span class="mt-auto text-muted fs-7 w-25 ms-auto px-4"><?php echo $content['date'] ?></span>
                    </div>
                </div>
            </div>
		<?php endforeach ?>
    </div>
</section>
<?php endif; ?>