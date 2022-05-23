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
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

		<?php if (!empty($sub_title)): ?>
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
				<?php echo $sub_title; ?>
            </div>
		<?php endif; ?>

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $data->title; ?>
                </h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
				<?php echo $data->title; ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
                Footer
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->


    <!-- Main content -->
    <section class="content">

		<?php if (!empty($sub_title)): ?>
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
				<?php echo $sub_title; ?>
            </div>
		<?php endif; ?>

        <!-- Default box -->
        <div class="card card-danger card-outline card-outline-tabs">
            <div class="card-header">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="pt-2 pr-3"><h3 class="card-title"><?php echo $data->title; ?></h3></li>
					<?php foreach ($projectLanguages as $project_languages_row) {
						?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $project_languages_row->default_lang == 1 ? "active" : null; ?>"
                               id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill"
                               href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab"
                               aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>"
                               aria-selected="<?php echo $project_languages_row->default_lang == 1 ? "true" : "false"; ?>"><?php echo $project_languages_row->lang; ?>
                                &nbsp;<i
                                        class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                        </li>
						<?php
					} ?>
                </ul>
            </div>
            <div class="card-body">
                <form action="" method="post" id="pageForm" enctype="multipart/form-data">
					<?php echo $functions->csrfToken(); ?>
                    <div class="tab-content" id="custom-tabs-two-tabContent">
						<?php foreach ($projectLanguages as $project_languages_row) {
							?>
                            <div class="tab-pane fade <?php echo $project_languages_row->default_lang == 1 ? "show active" : null; ?>"
                                 id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>"
                                 role="tabpanel"
                                 aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
								<?php echo $project_languages_row->short_lang; ?>
                            </div>
							<?php
						} ?>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
                <button type="submit" form="pageForm" class="btn btn-info">ada</button>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        $("#datatable-1").DataTable({
            /*"language": {
				"url": "<?php echo $system->adminPublicUrl("plugins/datatables/lang/" . $_SESSION["lang"] . ".json"); ?>"
                },*/
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
    });
</script>