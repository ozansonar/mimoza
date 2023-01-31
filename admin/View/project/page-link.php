<section class="content">
    <div class="alert alert-info alert-dismissible">
        <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
        <b>Ayarlar kısmında bulunan "İçerik Linkleri" ile aynı olan verileri lütfen silmeyin yoksa sisteminiz
            çalışmayacaktır.</b>
    </div>
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
            <table id="datatable-1" class="table table-bordered table-striped">
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
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('#datatable-1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'ajax/server-side-page-link',
            },
            columns: [
                { data: 'url' },
                { data: 'controller' },
                { data: 'lang' },
                { data: 'created_at' },
                { data: 'status' },
                { data: 'settings' },
            ],
            order: [[3, 'desc']],
            language: {
                "url": "<?php echo $system->adminPublicUrl("plugins/datatables/lang/" . $_SESSION["lang"] . ".json"); ?>"
            },
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
    });
</script>