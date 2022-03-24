<?php
/**
 * Created by PhpStorm.
 * User: Ozan PC
 * Date: 2.09.2020
 * Time: 20:56
 * Email: ozansonar1@gmail.com
 */
?>
<?php require $adminSystem->adminView('static/header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 admin-page-top-settings">
                <div class="col-sm-6">
                    <h1>
                        <a href="javascript:goBack()"><i class="fas fa-arrow-circle-left"></i></a>
                        <?php echo $page_title; ?>
                    </h1>
                </div>
                <?php if($session->sessionRoleControl($page_role_key,$listPermissionKey) == true): ?>
                    <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                        <h1>
                            <a href="<?php echo $adminSystem->adminUrl($page_button_redirect_link); ?>">
                                <i class="<?php echo !empty($page_button_icon) ? $page_button_icon:"fas fa-th-list"; ?>"></i>
                                <?php echo $page_button_redirect_text; ?>
                            </a>
                        </h1>
                    </div>
                <?php endif; ?>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">

        <?php if(!empty($sub_title)): ?>
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
                <?php echo $sub_title; ?>
            </div>
        <?php endif; ?>

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $page_title; ?>
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
                <div class="row">
                    <?php if(!empty($log_array)): ?>
                        <div class="col-5 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                <?php $log_count = 1; ?>
                                <?php foreach ($log_array as $log_key=>$log): ?>
                                    <a class="nav-link <?php echo $log_count == 1 ? "active":null; ?>" id="<?php echo $log_key; ?>-tab" data-toggle="pill" href="#tab-<?php echo $log_key; ?>" role="tab" aria-controls="tab-<?php echo $log_key; ?>" aria-selected="<?php echo $log_count == 1 ? "true":"false"; ?>"><?php echo $functions->date_long($log_key); ?></a>
                                    <?php $log_count++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-7 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <?php $log_count = 1; ?>
                                <?php foreach ($log_array as $log_key=>$log): ?>
                                    <div class="tab-pane text-left fade <?php echo $log_count == 1 ? "show active":null; ?>" id="tab-<?php echo $log_key; ?>" role="tabpanel" aria-labelledby="<?php echo $log_key; ?>-tab">


                                        <!-- The time line -->
                                        <div class="timeline">
                                            <!-- timeline time label -->
                                            <div class="time-label">
                                                <span class="bg-red"><?php echo $functions->date_long($log_key); ?></span>
                                            </div>
                                            <!-- /.timeline-label -->
                                            <?php $log_count = 1; ?>
                                            <?php foreach ($log as $log_row): ?>
                                                <!-- timeline item -->
                                                <div>
                                                    <i class="fas fa-clock bg-red"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i> <?php echo $functions->date_long_and_time($log_row->log_datetime); ?></span>
                                                        <h3 class="timeline-header"><b><?php echo $log_count; ?></b> - Bulunduğu Sayfa: <?php echo $log_row->log_key; ?></h3>

                                                        <div class="timeline-body">
                                                            Sayfa linki: <?php echo $log_row->log_page;  ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END timeline item -->
                                                <?php $log_count++; ?>
                                            <?php endforeach; ?>
                                        </div>


                                    </div>
                                    <?php $log_count++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">Kullanıcının her hangi bir haretketi bulunmuyor.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">

            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->

<?php require $adminSystem->adminView('static/footer'); ?>
