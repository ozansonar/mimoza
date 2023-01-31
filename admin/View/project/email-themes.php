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
            <table class="table table-bordered table-striped" id="datatable-1">
                <thead>
                <tr>
                    <th>Konu</th>
                    <th>E-posta İçeriği</th>
                    <th>E-posta Notu</th>
                    <th>Eklenme Tarihi</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
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
                url: 'ajax/server-side-email-theme',
            },
            columns: [
                { data: 'subject' },
                { data: 'text' },
                { data: 'not_text' },
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