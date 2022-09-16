<?php $session->checkUserSession() ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $data->title ?></title>
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
    <link href="<?php echo $system->adminPublicUrl("plugins/ekko-lightbox/ekko-lightbox.css"); ?>" rel="stylesheet">
    <link href="<?php echo $system->adminPublicUrl("plugins/overlayScrollbars/css/OverlayScrollbars.min.css"); ?>"
          rel="stylesheet">
    <link href="<?php echo $system->adminPublicUrl("dist/css/adminlte.min.css"); ?>" rel="stylesheet">
        <link href="
	<?php echo $system->adminPublicUrl("plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"); ?>"
              rel="stylesheet">
	<?php if (!empty($data->css)) : ?>
		<?php foreach ($data->css as $css): ?>
            <link href="<?php echo $system->adminPublicUrl($css); ?>" rel="stylesheet">
		<?php endforeach ?>
	<?php endif ?>

	<?php if (isset($loggedUser) && (int)$loggedUser->theme === 1): ?>
        <link rel="stylesheet" href="<?php echo $system->adminPublicUrl("dist/css/theme-dark.css"); ?>">
	<?php elseif (isset($loggedUser) && (int)$loggedUser->theme === 2): ?>
        <link rel="stylesheet" href="<?php echo $system->adminPublicUrl("dist/css/theme-light.css"); ?>">
	<?php endif; ?>

    <link rel="stylesheet" href="<?php echo $system->adminPublicUrl("dist/css/custom.css"); ?>">
    <script src="<?php echo $system->adminPublicUrl("plugins/jquery/jquery.min.js"); ?>"></script>
</head>
<body class="hold-transition
sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed
<?php echo $system->route(1) === "login"
	? "login-page"
	: "sidebar-mini"; ?>
    <?php echo isset($loggedUser) && (int)$loggedUser->theme === 1 ? "dark-mode" : NULL; ?>">
<?php if ($session->isThereAdminSession() && $system->route(1) !== "login"): ?>
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand <?php echo isset($loggedUser) && (int)$loggedUser->theme === 1 ? "navbar-dark" : "navbar-light"; ?>">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?php echo $system->url(); ?>" class="nav-link">Anasayfa</a>
            </li>
        </ul>
    </nav>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar <?php echo isset($loggedUser) && (int)$loggedUser->theme === 1 ? "sidebar-dark-primary" : "sidebar-light-primary"; ?>  elevation-4">
		<?php if (file_exists($constants::fileTypePath["project_image"]["full_path"] . $settings->header_logo)): ?>
            <!-- Brand Logo -->
            <a href="<?php echo $system->adminUrl(); ?>" class="brand-link">
                <img src="<?php echo $constants::fileTypePath["project_image"]["url"] . $settings->header_logo ?>"
                     alt="Logo"
                     class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">
                    <?php echo $functions->shorten($settings->project_name, 10, 0) ?></span>
            </a>
		<?php endif; ?>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?php echo $userHeaderTopImg; ?>" class="img-circle elevation-2">
                </div>
                <div class="info">
                    <a href="#"
                       class="d-block"><?php echo $loggedUser->name . ' ' . $loggedUser->surname; ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <?php foreach ($menu as $mainUrl => $menuItem): if (!$session->sessionRoleControl($menuItem['url'], 's')) continue; ?>
                        <li class="nav-item <?php echo isset($menuItem['submenu']) ? "has-treeview":null; ?> <?php echo  ($system->route(1) == $menuItem['url']) || ( isset($menuItem['submenu']) && array_search($system->route(1), array_column($menuItem['submenu'], 'url')) !== false) ? ' menu-open ' : null ?>" >
                            <a href="<?php echo $system->adminUrl($menuItem['url']) ?>" class="nav-link <?php echo isset($menuItem['submenu']) ? " ":null;?>" <?php echo isset($menuItem["submenu"]) ? 'active':null;?>>
                                <i class="<?php echo  $menuItem['icon'] ?>"></i>
                                <p>
                                    <?php echo  $menuItem['title'] ?>
                                    <?php if (isset($menuItem['submenu'])): ?>
                                        <i class="right fas fa-angle-left"></i>
                                    <?php endif; ?>
                                </p>
                            </a>
                            <?php if (isset($menuItem['submenu'])): ?>
                                <ul class="nav nav-treeview" data-submenu-title="<?php echo  $menuItem['title'] ?>">
                                    <?php foreach ($menuItem['submenu'] as $k => $submenu): if (!$session->sessionRoleControl($submenu['url'], 's') && !$session->sessionRoleControl($submenu['url'], 'a')) continue; ?>
                                        <li class="nav-item <?php //echo $system->route(1) == $submenu['url'] ? 'active' : null?>">
                                            <a href="<?php echo $system->adminUrl($submenu['url']) ?>" class="nav-link <?php echo $system->route(1) == $submenu['url'] ? 'active' : null?>">
                                                <i class="<?php echo  $submenu['icon']; ?>"></i>
                                                <?php echo  $submenu['title'] ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item">
                        <a href="<?php echo $system->adminUrl("logout"); ?>" class="nav-link">
                            <i class="nav-icon far fa-circle text-danger"></i>
                            <p class="text">Çıkış</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-item -->
        </div>
        <!-- /.sidebar -->
    </aside>
	<?php endif; ?>
	<?php if ($session->isThereAdminSession() && $system->route(1) !== "login"): ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 admin-page-top-settings">
                    <div class="col-sm-6">
                        <h1>
                            <a href="javascript:goBack()"><i class="fas fa-arrow-circle-left"></i></a>
							<?php echo $data->title; ?>
                        </h1>
                    </div>
					<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::listPermissionKey) === true): ?>
                        <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                            <h1>
                                <a href="<?php echo $system->adminUrl($data->pageButtonRedirectLink); ?>">
                                    <i class="<?php echo !empty($data->pageButtonIcon) ? $data->pageButtonIcon : "fas fa-th-list"; ?>"></i>
									<?php echo $data->pageButtonRedirectText; ?>
                                </a>
                            </h1>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </section>
		<?php include_once $data->view ?>
    </div>
    <footer class="main-footer">
        <strong><?php echo $settings->project_name; ?></strong>
    </footer>
</div>
<?php endif; ?>
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
    <!-- /.modal -->
    <script>
        $(document).ready(function () {
            $("#page_alert_modal").modal();
        });
    </script>
<?php endif; ?>

<?php if ($session->isThereAdminSession() && ($session->get('user_rank') && $session->get('user_rank') >= 60)): ?>
    <script>
        function post_delete(url) {
            Swal.fire({
                title: 'Silmek istiyormusunuz ?',
                text: "Silmek istediğinize emin misiniz ?",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Evet SİL !',
                cancelButtonText: 'Vazgeç',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    if (result.value) {
                        window.location = url;
                    }
                }
            });
        }

        function post_edit(url) {
            window.location = url;
        }
    </script>
<?php endif; ?>

<!-- REQUIRED SCRIPTS -->
<script src="<?php echo $system->adminPublicUrl("plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/ekko-lightbox/ekko-lightbox.min.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("dist/js/adminlte.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/sweetalert2/sweetalert2.min.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("dist/js/pages/dashboard2.js"); ?>"></script>

<?php if (!empty($data->js)): ?>
    <!-- Page Custom JS -->
	<?php foreach ($data->js as $js): ?>
        <script src="<?php echo $system->adminPublicUrl($js); ?>"></script>
	<?php endforeach ?>
<?php endif ?>

<script src="<?php echo $system->adminPublicUrl("dist/js/custom.js"); ?>"></script>
</body>
</html>

