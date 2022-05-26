<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <?php echo $data->title; ?>
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
    </div>
</section>
<script>
    $(document).ready(function () {
        let token = $("#token").val();
        let g_id = "<?php echo $id; ?>";
        $("#kv-explorer").fileinput({
            'theme': 'explorer-fas',
            'uploadUrl': 'ajax/gallery-uploader',
            language: 'tr',
            uploadExtraData: {
                token: token,
                gallery_id: g_id
            },
            allowedFileExtensions: ['jpg', 'png', 'gif'],
            maxFileCount: 20,
            maxFileSize: 9910000,
            showUpload: true,
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: [<?php echo $data->initialPreview; ?>],
            initialPreviewConfig: [<?php echo $data->initialPreviewConfig; ?>],
            deleteUrl: "ajax/gallery-uploader"

        }).on('fileuploaded', function (event, previewId, index, fileId) {
            console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
        }).on('fileuploaderror', function (event, data, msg) {
            console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
        }).on('filebatchuploadcomplete', function (event, preview, config, tags, extraData) {
            console.log('File Batch Uploaded', preview, config, tags, extraData);
        });
    });
</script>
