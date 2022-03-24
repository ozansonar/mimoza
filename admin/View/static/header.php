<?php $session->checkUserSession() ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $metaTag->title ?></title>
	<?php if (!empty($settings->fav_icon) && file_exists($fileTypePath["project_image"]["full_path"] . $settings->fav_icon)): ?>
        <link rel="icon" type="image/png"
              href="<?php echo $fileTypePath["project_image"]["url"] . $settings->fav_icon; ?>"/>
        <link rel="icon" type="image/png"
              href="<?php echo $fileTypePath["project_image"]["url"] . $settings->fav_icon; ?>"/>
	<?php endif; ?>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet"
          href="<?php echo $adminSystem->adminPublicUrl("plugins/fontawesome-free/css/all.min.css"); ?>">
    <!-- flag-icon-css -->
    <link rel="stylesheet"
          href="<?php echo $adminSystem->adminPublicUrl("plugins/flag-icon-css/css/flag-icon.min.css"); ?>">
    <link rel="stylesheet"
          href="<?php echo $adminSystem->adminPublicUrl("plugins/ekko-lightbox/ekko-lightbox.css"); ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
          href="<?php echo $adminSystem->adminPublicUrl("plugins/overlayScrollbars/css/OverlayScrollbars.min.css"); ?>">
    <!-- Theme style -->
    <link rel="stylesheet"
          href="<?php echo $adminSystem->adminPublicUrl("dist/css/adminlte.min.css"); ?>">

    <link rel="stylesheet"
          href="<?php echo $adminSystem->adminPublicUrl("plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"); ?>">

    <!-- Page Custom Css -->
	<?php if (!empty($customCss)) : ?>
		<?php foreach ($customCss as $css): ?>
            <link href="<?php echo $adminSystem->adminPublicUrl($css); ?>" rel="stylesheet">
		<?php endforeach ?>
	<?php endif ?>

	<?php if (isset($loggedUser) && (int)$loggedUser->theme === 1): ?>
        <link rel="stylesheet" href="<?php echo $adminSystem->adminPublicUrl("dist/css/theme-dark.css"); ?>">
	<?php elseif (isset($loggedUser) && (int)$loggedUser->theme === 2): ?>
        <link rel="stylesheet" href="<?php echo $adminSystem->adminPublicUrl("dist/css/theme-light.css"); ?>">
	<?php endif; ?>

    <!-- Custom style -->
    <link rel="stylesheet" href="<?php echo $adminSystem->adminPublicUrl("dist/css/custom.css"); ?>">

    <!-- jQuery -->
    <script src="<?php echo $adminSystem->adminPublicUrl("plugins/jquery/jquery.min.js"); ?>"></script>
</head>
<body class="hold-transition <?php echo isset($loggedUser) && (int)$loggedUser->theme === 1 ? "dark-mode" : null; ?> sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed <?php echo $system->route(1) == "login" ? "login-page" : "sidebar-mini"; ?>">
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
                <a href="<?php echo $functions->site_url_lang(); ?>" class="nav-link">Anasayfa</a>
            </li>
        </ul>
    </nav>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar <?php echo isset($loggedUser) && (int)$loggedUser->theme === 1 ? "sidebar-dark-primary" : "sidebar-light-primary"; ?>  elevation-4">
		<?php if (file_exists($fileTypePath["project_image"]["full_path"] . $settings->header_logo)): ?>
            <!-- Brand Logo -->
            <a href="<?php echo $adminSystem->adminUrl(); ?>" class="brand-link">
                <img src="<?php echo $fileTypePath["project_image"]["url"] . $settings->header_logo ?>" alt=""
                     class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?php echo $functions->kisalt($settings->project_name, 10, 0) ?></span>
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
                       class="d-block"><?php echo $loggedUser->name .  ' ' . $loggedUser->surname; ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
					<?php foreach ($menus as $mainUrl => $menu): if (!$session->sessionRoleControl($menu['url'], 's')) :continue; endif; ?>
                        <li class="nav-item <?php echo isset($menu['submenu']) ? "has-treeview" : null; ?> <?php echo ($system->route(1) === $menu['url']) || (isset($menu['submenu']) && in_array($system->route(1), array_column($menu['submenu'], 'url'), true)) ? ' menu-open ' : null ?>">
                            <a href="<?php echo $adminSystem->adminUrl($menu['url']) ?>"
                               class="nav-link <?php echo isset($menu['submenu']) ? " " : null; ?>" <?php echo isset($menu["submenu"]) ? 'active' : null; ?>>
                                <i class="<?php echo $menu['icon'] ?>"></i>
                                <p>
									<?php echo $menu['title'] ?>
									<?php if (isset($menu['submenu'])): ?>
                                        <i class="right fas fa-angle-left"></i>
									<?php endif; ?>
                                </p>
                            </a>
							<?php if (isset($menu['submenu'])): ?>
                                <ul class="nav nav-treeview" data-submenu-title="<?php echo $menu['title'] ?>">
									<?php foreach ($menu['submenu'] as $k => $submenu): if (!$session->sessionRoleControl($submenu['url'], 's') && !$session->sessionRoleControl($submenu['url'], 'a')) continue; ?>
                                        <li class="nav-item <?php //echo $system->route(1) == $submenu['url'] ? 'active' : null?>">
                                            <a href="<?php echo $adminSystem->adminUrl($submenu['url']) ?>"
                                               class="nav-link <?php echo $system->route(1) === $submenu['url'] ? 'active' : null ?>">
                                                <i class="<?php echo $submenu['icon']; ?>"></i>
												<?php echo $submenu['title'] ?>
                                            </a>
                                        </li>
									<?php endforeach; ?>
                                </ul>
							<?php endif; ?>
                        </li>
					<?php endforeach; ?>
                    <li class="nav-item">
                        <a href="<?php echo $adminSystem->adminUrl("logout"); ?>" class="nav-link">
                            <i class="nav-icon far fa-circle text-danger"></i>
                            <p class="text">Çıkış</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
<?php endif; ?>