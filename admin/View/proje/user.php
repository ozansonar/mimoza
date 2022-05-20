<?php
/**
 *
 * Created by PhpStorm.
 * User: Ozan SONAR ( ozansonar1@gmail.com )
 * Date: 31.05.2020
 * Time: 20:11
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
                        <?php echo $page_title; ?>
                    </h1>
                </div>
                <?php if($session->sessionRoleControl($page_role_key,$constants::listPermissionKey) == true): ?>
                    <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                        <h1>
                            <a href="<?php echo $system->adminUrl($page_button_redirect_link); ?>">
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
                <table id="datatable-1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>E-posta</th>
                        <th>Telefon</th>
                        <th>Resim</th>
                        <th>Eklenme Tarihi</th>
                        <th>Durum</th>
                        <th>Kullanıcı Tipi</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $row->name; ?></td>
                            <td><?php echo $row->surname; ?></td>
                            <td><?php echo $row->email; ?></td>
                            <td><?php echo $row->telefon; ?></td>
                            <td>
                                <?php if(!empty($row->img) && file_exists($constants::fileTypePath["user_image"]["full_path"].$row->img)): ?>
                                    <a href="<?php echo $constants::fileTypePath["user_image"]["url"].$row->img; ?>" data-toggle="lightbox" data-title="<?php echo $row->name." ".$row->surname; ?>" class="color-unset">
                                        <i class="fas fa-images"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $functions->date_short($row->created_at); ?></td>
                            <td><span class="<?php echo $systemStatus[$row->status]["view_class"]; ?>"><?php echo $systemStatus[$row->status]["view_text"]; ?></span></td>
                            <td><?php echo $systemAdminUserType[$row->rank]["view_text"]; ?></td>
                            <td>
                                <?php if($session->sessionRoleControl($page_role_key,$constants::editPermissionKey) == true): ?>
                                    <button type="button" class="btn btn-outline-success m-1" onclick="post_edit('<?php echo $system->adminUrl("user-settings?id=".$row->id); ?>')"><i class="fas fa-pencil-alt px-1"></i></i>Düzenle</button>
                                <?php endif; ?>
                                <?php if($_SESSION["user_id"] != $row->id && $session->sessionRoleControl($page_role_key,$deletePermissionKey) == true): ?>
                                    <button type="button" class="btn btn-outline-danger m-1" onclick="post_delete('<?php echo $system->adminUrl("user?delete=".$row->id); ?>')"><i class="fas fa-trash px-1"></i> Sil</button>
                                <?php endif; ?>
                                <?php if($session->sessionRoleControl("user-tracing",$constants::listPermissionKey) == true): ?>
                                    <a href="<?php echo $system->adminUrl("user-tracing?id=".$row->id); ?>"  class="btn btn-outline-primary"><i class="fas fa-desktop px-1"></i>Kullanıcı Hareketleri</a>
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
