<?php
/**
 * Created by PhpStorm.
 * User: Ozan PC
 * Date: 19.09.2020
 * Time: 17:30
 * Email: ozansonar1@gmail.com
 */
?>
<?php require $system->adminView('static/header'); ?>
<!-- Content Wrapper. Contains page content -->
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
                <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::listPermissionKey) == true): ?>
                    <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                        <h1>
                            <a href="<?php echo $system->adminUrl($data->pageButtonRedirectLink); ?>">
                                <i class="<?php echo !empty($data->pageButtonIcon) ? $data->pageButtonIcon:"fas fa-th-list"; ?>"></i>
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

        <?php if(!empty($sub_title)): ?>
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
                    <?php foreach ($projectLanguages as $project_languages_row){
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $project_languages_row->default_lang == 1 ? "active":null; ?>" id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill" href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab" aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" aria-selected="<?php echo $project_languages_row->default_lang == 1 ? "true":"false"; ?>"><?php echo $project_languages_row->lang; ?>&nbsp;<i class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                        </li>
                        <?php
                    } ?>
                </ul>
            </div>
            <div class="card-body">
                <form action="" method="post" id="pageForm" enctype="multipart/form-data">
                    <?php echo $functions->csrfToken(); ?>
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <?php foreach ($projectLanguages as $project_languages_row){
                            ?>
                            <div class="tab-pane fade <?php echo $project_languages_row->default_lang == 1 ? "show active":null; ?>" id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel" aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
                                <?php
                                $form->lang = $project_languages_row->short_lang;
                                echo $form->input("title",array(
                                    "label" => $admin_text["VIDEO_TITLE"],
                                    "required" => 1,
                                ),$pageData);
                                echo $form->input("link",array(
                                    "label" => $admin_text["VIDEO_LINK"],
                                    "required" => 1,
                                ),$pageData);
                                echo $form->input("show_order",array(
                                    "label" => $admin_text["VIDEO_SHOW_ORDER"],
                                    "required" => 1,
                                    "class" => "numeric_field",
                                    "order" => 1,
                                ),$pageData);
                                echo $form->select("status",array(
                                    "label" => $admin_text["VIDEO_STATUS"],
                                    "required" => 1,
                                    "select_item" => $systemStatusVersion,
                                ),$pageData);
                                ?>
                            </div>
                            <?php
                        } ?>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
                <?php
                echo $form->button("submit",array(
                    "text" => "Kaydet",
                    "icon" => "fas fa-save",
                    "btn_class" => "btn btn-success",
                ));
                ?>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->


    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $data->title; ?>
                    <?php if(!empty($sub_title)): ?>
                        <br>
                        <small><?php echo $sub_title; ?></small>
                    <?php endif; ?>
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
                <table  id="datatable-1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>İsim</th>
                        <th>Eklenme Tarihi</th>
                        <th>Video</th>
                        <th>Durum</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($videos_data as $row): ?>
                        <tr>
                            <td><?php echo $functions->textModal($row->title,20); ?></td>
                            <td><?php echo $functions->date_long($row->created_at); ?></td>
                            <td>
                                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#page_modal_title_<?php echo $row->id; ?>">
                                    Göster <i class="fas fa-info-circle ml-2"></i>
                                </button>
                                <!-- Basic modal -->
                                <div id="page_modal_title_<?php echo $row->id; ?>" class="modal fade" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <iframe width="100%" height="350" src="https://www.youtube.com/embed/<?php echo $row->link; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Kapat</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /basic modal -->
                            </td>
                            <td><span class="<?php echo $systemStatus[$row->status]["view_class"]; ?>"><?php echo $systemStatus[$row->status]["view_text"]; ?></span></td>
                            <td>
                                <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::addPermissionKey) == true): ?>
                                    <button type="button" class="btn btn-outline-success m-1" onclick="post_edit('<?php echo $system->adminUrl("gallery-video-upload?id=".$row->gallery_id."&video_id=".$row->id); ?>')"><i class="fas fa-pencil-alt px-1 px-1"></i></i>Düzenle</button>
                                <?php endif; ?>
                                <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::addPermissionKey) == true): ?>
                                    <button type="button" class="btn btn-outline-danger  m-1" onclick="post_delete('<?php echo $system->adminUrl("gallery-video-upload?id=".$row->gallery_id."&delete=".$row->id); ?>')"><i class="fas fa-trash px-1 px-1"></i> Sil</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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


<script>
    $(document).ready(function(){
        $("form#pageForm").validationEngine({promptPosition : "bottomLeft", scroll: false});
    });
</script>
<script>
    $(document).ready(function() {
        $("#datatable-1").DataTable({
            /*"language": {
                "url": "<?php echo $system->adminPublicUrl("plugins/datatables/lang/".$_SESSION["lang"].".json"); ?>"
                },*/
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
    });
</script>
<?php require $system->adminView('static/footer'); ?>