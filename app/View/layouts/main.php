<!doctype html>
<html lang="<?php echo ($_SESSION['lang']) ?: 'tr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo ($metaTag->description) ?? $metaTag->description ?>">
    <meta name="keywords" content="<?php echo ($metaTag->keywords) ?? $metaTag->keywords ?>">
    <title><?php echo ($metaTag->title) ? $metaTag->title.' | ' . $settings->project_name : '' ?></title>
	<?php if (!empty($settings->fav_icon) && file_exists($constants::fileTypePath["project_image"]["full_path"] . $settings->fav_icon)): ?>
        <link rel="icon" type="image/png"
              href="<?php echo $constants::fileTypePath["project_image"]["url"] . $settings->fav_icon; ?>"/>
        <link rel="icon" type="image/png"
              href="<?php echo $constants::fileTypePath["project_image"]["url"] . $settings->fav_icon; ?>"/>
	<?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $system->publicUrl('plugins/bootstrap/css/bootstrap.min.css'); ?>">

    <?php if (isset($data->customCss) && !empty($data->customCss)): ?>
        <?php foreach ($data->customCss as $css) : ?>
            <link href="<?php echo $system->publicUrl($css); ?>" rel="stylesheet">
        <?php endforeach ?>
    <?php endif ?>

    <link rel="stylesheet" href="<?php echo $system->publicUrl('dist/css/custom.css?v='.filemtime($system->path("public/proje/dist/css/custom.css")).''); ?>">

    <script src="<?php echo $system->publicUrl("dist/js/jquery-3.6.1.min.js"); ?>"></script>
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container-fluid">
    <div class="container mt-1 d-flex flex-column flex-md-row justify-content-md-between">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if (isset($settings->phone) && !empty($settings->phone)): ?>
                    <li class="page-item">
                        <a href="tel:+90<?php echo $settings->phone ?>" class="page-link">
                            <i class="fas fa-phone-volume text-4 text-color-primary"
                               style="top: 0;"></i>
                            <?php echo $functions->phoneFormat($settings->phone); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (isset($settings->whatsapp) && !empty($settings->whatsapp)): ?>
                    <li class="page-item">
                        <a href="tel:+90<?php echo $settings->whatsapp ?>" class="page-link">
                            <i class="fab fa-whatsapp text-4 text-color-primary" style="top: 0;"></i>
                            <?php echo $functions->phoneFormat($settings->whatsapp); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (isset($settings->site_mail) && !empty($settings->site_mail)): ?>
                    <li class="page-item">
                        <a href="mailto:<?php echo $settings->site_mail ?>" class="page-link">
                            <i class="far fa-envelope text-4 text-color-primary" style="top: 1px;"></i>
                            <?php echo $settings->site_mail ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="col text-end">
            <?php foreach ($projectLanguages as $languages): ?>
                <?php if ($languages->short_lang === $_SESSION["lang"]): continue; endif ?>
                <a href="<?php echo $system->url($languages->short_lang) ?>"
                   class="btn btn-info"><?php echo $languages->lang; ?></a>
            <?php endforeach; ?>
            <?php if ($session->isThereUserSession()): ?>
                <div class="dropdown mt-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $loggedUser->name . " " . $loggedUser->surname; ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item"
                               href="<?php echo $system->url($settings->{'profile_prefix_' . $_SESSION["lang"]}) ?>"><?php echo $functions->textManager("header_profil"); ?></a>
                        </li>
                        <?php if ($session->isThereAdminSession()): ?>
                            <li>
                                <a class=" dropdown-item" href="<?php echo $system->url("admin"); ?>"
                                   target="_blank">
                                    <?php echo $functions->textManager("header_yonetim_paneli"); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="<?php echo $system->url("logout"); ?>">
                                <?php echo $functions->textManager("header_cikis"); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="mt-2">
                    <a href="<?php echo $system->url($settings->{'giris_prefix_' . $_SESSION["lang"]}); ?>" class="btn btn-info">
                        <?php echo $functions->textManager("header_giris_button"); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class=" container">
        <div class="row">
            <div class="col-12 col-md-3">
                <a href="<?php echo $system->url(); ?>">
                    <?php if (!empty($settings->header_logo) && file_exists($constants::fileTypePath["project_image"]["full_path"] . $settings->header_logo)): ?>
                        <img alt="<?php echo $settings->project_name ?>"
                             width="100%" height="auto"
                             src="<?php echo $constants::fileTypePath["project_image"]["url"] . $settings->header_logo ?>">
                    <?php endif; ?>
                </a>
            </div>
            <div class="col-12 col-md-9 d-flex align-items-center justify-content-start">
                <h3><?php echo $settings->project_name ?></h3>
            </div>
        </div>
    </div>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php echo $siteManager->getHeaderNavbar(); ?>
                    </ul>
                    <form class="d-flex" action="<?php echo $system->url($settings->{'search_prefix_' . $_SESSION["lang"]}); ?>">
                        <input class="form-control me-2" type="search" name="q"
                               placeholder="<?php echo $functions->textManager("header_search_placeholder"); ?>"
                               aria-label="<?php echo $functions->textManager("header_search_placeholder"); ?>">
                        <button class="btn btn-outline-success"
                                type="submit"><?php echo $functions->textManager("header_search_button"); ?></button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</div>

<?php include $data->view; ?>

<?php if(isset($message) && (isset($message["reply"]) || isset($message["success"]))): ?>
    <button class="btn btn-modern btn-primary modal-click" data-bs-toggle="modal" data-bs-target="#defaultModal" style="display: none">

    </button>

    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header <?php echo isset($message["success"]) ? "bg-success":"bg-danger"; ?> text-white">
                    <h4 class="modal-title" id="defaultModalLabel"><?php echo $functions->textManager("sistem_popup_title"); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <?php
                    if(isset($message["success"])):
                        foreach ($message["success"] as $success):
                            echo $success."<br>";
                        endforeach;
                    elseif (isset($message["reply"])):
                        foreach ($message["reply"] as $reply):
                            echo $reply."<br>";
                        endforeach;
                    endif;
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn <?php echo isset($message["success"]) ? "btn-success":"btn-danger"; ?>" data-bs-dismiss="modal"><?php echo $functions->textManager("sistem_popup_button"); ?></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $(".modal-click").trigger("click");
        });
    </script>
<?php endif; ?>

<div class="container-fluid bg-dark p-5 text-white mt-2 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 text-center text-md-start">
                <h4><?php echo $functions->textManager("footer_sayfalar"); ?></h4>
                <ul class="p-0 list-style-none">
                    <?php echo $siteManager->getFooterNavbar(); ?>
                </ul>
            </div>
            <div class="col-12 col-md-4"></div>
            <div class="col-12 col-md-4"></div>
            <div class="col-12 text-white text-center"><?php echo $functions->textManager("footer_text"); ?></div>
        </div>
    </div>
</div>

<script src="<?php echo $system->publicUrl("plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
<?php
if(isset($data->customJs) && !empty($data->customJs)){
    foreach ($data->customJs as $c_js){
        ?>
        <script src="<?php echo $system->publicUrl($c_js); ?>" defer></script>
        <?php
    }
}
?>
<script src="<?php echo $system->publicUrl("dist/js/custom.js"); ?>" defer></script>
</body>
</html>
