<?php
/**
 *
 * Created by PhpStorm.
 * User: Ozan SONAR ( ozansonar1@gmail.com )
 * Date: 15.08.2020
 * Time: 14:08
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
        <div class="alert alert-info alert-dismissible">
            <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
            <b>Ayarlar kısmında bulunan "İçerik Linkleri" ile aynı olan verileri lütfen silmeyin yoksa sisteminiz çalışmayacaktır.</b>
        </div>
        <?php if(!empty($sub_title)): ?>
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
                <table  id="datatable-1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Link</th>
                        <th>Gideceği Dosya</th>
                        <th>Dil</th>
                        <th>Eklenme Tarihi</th>
                        <th>Durum</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $functions->textModal($row->url); ?></td>
                            <td><?php echo $row->controller; ?></td>
                            <td><?php echo $projectLanguages[$row->lang]->lang ?? null; ?></td>
                            <td><?php echo $functions->date_short($row->created_at); ?></td>
                            <td><span class="<?php echo $systemStatus[$row->status]["view_class"]; ?>"><?php echo $systemStatus[$row->status]["view_text"]; ?></span></td>
                            <td>
                                <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::editPermissionKey) == true): ?>
                                    <button type="button" class="btn btn-outline-success m-1" onclick="post_edit('<?php echo $system->adminUrl("page-link-settings?id=".$row->id); ?>')"><i class="fas fa-pencil-alt px-1 px-1"></i></i>Düzenle</button>
                                <?php endif; ?>
                                <?php if($session->sessionRoleControl($data->pageRoleKey,$deletePermissionKey) == true): ?>
                                    <button type="button" class="btn btn-outline-danger  m-1" onclick="post_delete('<?php echo $system->adminUrl("page-link?delete=".$row->id); ?>')"><i class="fas fa-trash px-1 px-1"></i> Sil</button>
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
