<?php
/**
 * Created by PhpStorm.
 * User: Ozan PC
 * Date: 30.08.2020
 * Time: 21:41
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
                <form method="post" id="pageForm" enctype="multipart/form-data">
                    <div class="file-loading py-5">
                        <input id="kv-explorer" type="file" multiple>
                    </div>
                </form>
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
    $(document).ready(function () {
        var  token = $("#token").val();
        var  g_id = "<?php echo $id; ?>";
        $("#kv-explorer").fileinput({
            'theme': 'explorer-fas',
            'uploadUrl': 'ajax/gallery-uploader',
            language: 'tr',
            uploadExtraData: {
                token: token,
                gallery_id : g_id
            },
            allowedFileExtensions: ['jpg', 'png', 'gif'],
            maxFileCount: 20,
            maxFileSize: 9910000,
            showUpload: true,
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: [<?php echo $initialPreview; ?>],
            initialPreviewConfig: [<?php echo $initialPreviewConfig; ?>],
            deleteUrl: "ajax/gallery-uploader"

        }).on('fileuploaded', function(event, previewId, index, fileId) {
            console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
        }).on('fileuploaderror', function(event, data, msg) {
            console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
        }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
            console.log('File Batch Uploaded', preview, config, tags, extraData);
        });
        /*
         $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
         alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
         });
         */
    });
</script>
<?php require $adminSystem->adminView('static/footer'); ?>
