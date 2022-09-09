<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ($data->title) ?: '' ?></title>
	<?php if (!empty($settings->fav_icon) && file_exists($constants::fileTypePath["project_image"]["full_path"] . $settings->fav_icon)): ?>
        <link rel="icon" type="image/png"
              href="<?php echo $constants::fileTypePath["project_image"]["url"] . $settings->fav_icon; ?>"/>
        <link rel="icon" type="image/png"
              href="<?php echo $constants::fileTypePath["project_image"]["url"] . $settings->fav_icon; ?>"/>
	<?php endif; ?>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="<?php echo $system->adminPublicUrl("plugins/fontawesome-free/css/all.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo $system->adminPublicUrl("plugins/flag-icon-css/css/flag-icon.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo $system->adminPublicUrl("dist/css/adminlte.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo $system->adminPublicUrl("plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"); ?>"
          rel="stylesheet">

    <?php if (!empty($data->css)) : ?>
        <?php foreach ($data->css as $css): ?>
            <link href="<?php echo $system->adminPublicUrl($css); ?>" rel="stylesheet">
        <?php endforeach ?>
    <?php endif ?>
    <link rel="stylesheet" href="<?php echo $system->adminPublicUrl("dist/css/custom.css"); ?>">
    <script src="<?php echo $system->adminPublicUrl("plugins/jquery/jquery.min.js"); ?>"></script>
</head>
<body class="hold-transition
sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

<section class="container d-flex vh-100 justify-content-center align-items-center">
	<?php include_once $data->view ?>
</section>

<?php if (isset($message) && is_array($message) && (isset($message["reply"]) || isset($message["success"]))): ?>
    <!-- modal content -->
    <div id="page_alert_modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header <?php echo isset($message["success"]) ? "bg-success" : "bg-danger"; ?>">
                    <h6 class="modal-title"><?php echo isset($message["success"]) ? "İşlem Başarılı" : "Hata"; ?></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>
                        <?php
                        if (!empty($message["reply"])) {
                            foreach ($message["reply"] as $m_reply) {
                                echo $m_reply . "<br>";
                            }
                        }
                        if (!empty($message["success"])) {
                            foreach ($message["success"] as $m_success) {
                                echo $m_success . "<br>";
                            }
                        }
                        ?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn <?php echo isset($message["success"]) ? "bg-success" : "bg-danger"; ?>"
                            data-dismiss="modal">Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <script>
        $(document).ready(function () {
            $("#page_alert_modal").modal();
        });
    </script>
<?php endif; ?>
<?php $sessionError = $session->get('session_error');
if (!empty($sessionError)): ?>
    <!-- modal content -->
    <div id="page_alert_modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-danger ">
                    <h6 class="modal-title">Hata</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo $sessionError; ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn bg-danger"
                            data-dismiss="modal">Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $session->delete('session_error') ?>
    <script>
        $(document).ready(function () {
            $("#page_alert_modal").modal();
        });
    </script>
<?php endif; ?>

<!-- Bootstrap -->
<script src="<?php echo $system->adminPublicUrl("plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/ekko-lightbox/ekko-lightbox.min.js"); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo $system->adminPublicUrl("dist/js/adminlte.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/sweetalert2/sweetalert2.min.js"); ?>"></script>
<!-- Page Custom JS -->
<?php if (!empty($data->js)): ?>
    <!-- Page Custom JS -->
    <?php foreach ($data->js as $js): ?>
        <script src="<?php echo $system->adminPublicUrl($js); ?>"></script>
    <?php endforeach ?>
<?php endif ?>

<script src="<?php echo $system->adminPublicUrl("dist/js/custom.js"); ?>"></script>
</body>
</html>

