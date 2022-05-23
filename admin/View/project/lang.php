<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan
 * Date: 31.08.2021
 * Time: 21:58
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
                    <table class="table table-bordered table-striped" id="datatable-1">
                        <thead>
                        <tr>
                            <th>Dil</th>
                            <th>Kısalma</th>
                            <th>Varsayılan Dil</th>
                            <th>Form Doğrulama</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td>
                                    <?php echo $functions->textModal($row->lang,20); ?>
                                </td>
                                <td><?php echo $row->short_lang; ?></td>
                                <td><?php echo array_key_exists($row->default_lang,$systemYesAndNoText) ? $systemYesAndNoText[$row->default_lang]["form_text"]:null; ?></td>
                                <td><?php echo array_key_exists($row->form_validate,$systemYesAndNoText) ? $systemYesAndNoText[$row->form_validate]["form_text"]:null; ?></td>
                                <td><span class="<?php echo $systemStatus[$row->status]["view_class"]; ?>"><?php echo $systemStatus[$row->status]["view_text"]; ?></span></td>
                                <td>
                                    <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::editPermissionKey) == true): ?>
                                        <button type="button" class="btn btn-outline-success m-1" onclick="post_edit('<?php echo $system->adminUrl("lang-settings?id=".$row->id); ?>')"><i class="fas fa-pencil-alt px-1 px-1"></i></i>Düzenle</button>
                                    <?php endif; ?>
                                    <?php if($session->sessionRoleControl($data->pageRoleKey,$deletePermissionKey) == true): ?>
                                        <button type="button" class="btn btn-outline-danger  m-1" onclick="post_delete('<?php echo $system->adminUrl("lang?delete=".$row->id); ?>')"><i class="fas fa-trash px-1 px-1"></i> Sil</button>
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