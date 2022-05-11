<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan
 * Date: 16.10.2021
 * Time: 23:38
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


            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $page_title; ?>
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
                    <table class="table table-bordered table-striped" id="datatable-1">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Konu</th>
                            <th>İçerik</th>
                            <th>Gönderim Durumu</th>
                            <th>Tamamlanma Tarihi</th>
                            <th>Eklenme Tarihi</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $row):
                            ?>
                            <tr>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $functions->textModal($row->subject,20); ?></td>
                                <td><?php echo $functions->textModal($row->text,20); ?></td>
                                <td><span class="<?php echo $mailingSendStatus[$row->completed]["view_class"]; ?>"><?php echo $mailingSendStatus[$row->completed]["view_text"]; ?></span></td>
                                <td><?php echo $row->completed == 1 ? $functions->date_long($row->completed_date):null; ?></td>
                                <td><?php echo $functions->date_long($row->created_at); ?></td>
                                <td><span class="<?php echo $systemStatus[$row->status]["view_class"]; ?>"><?php echo $systemStatus[$row->status]["view_text"]; ?></span></td>
                                <td>
                                    <?php if($session->sessionRoleControl($page_role_key,$editPermissionKey) == true): ?>
                                        <button type="button" class="btn btn-outline-success m-1" onclick="post_edit('<?php echo $adminSystem->adminUrl($page_add_role_key."?id=".$row->id); ?>')"><i class="fas fa-pencil-alt px-1 px-1"></i></i>Düzenle</button>
                                    <?php endif; ?>
                                    <?php if($session->sessionRoleControl($page_role_key,$deletePermissionKey) == true): ?>
                                        <button type="button" class="btn btn-outline-danger  m-1" onclick="post_delete('<?php echo $adminSystem->adminUrl($page_role_key."?delete=".$row->id); ?>')"><i class="fas fa-trash px-1 px-1"></i> Sil</button>
                                    <?php endif; ?>
                                    <?php if($session->sessionRoleControl($page_role_key,$detailPermissionKey) == true): ?>
                                        <a href="<?php echo $adminSystem->adminUrl("mailing-view?id=".$row->id); ?>"  class="btn btn-outline-primary"><i class="fas fa-desktop px-1"></i>Detaylar</a>
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
        $(document).ready(function() {

            $("#datatable-1").DataTable({
                /*"language": {
                    "url": "<?php echo $adminSystem->adminPublicUrl("plugins/datatables/lang/".$_SESSION["lang"].".json"); ?>"
                },*/
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php require $adminSystem->adminView('static/footer'); ?>