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
                    <th>İçerik</th>
                    <th>Gönderim Durumu</th>
                    <th>Tamamlanma Tarihi</th>
                    <th>Eklenme Tarihi</th>
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
        let table = $('#datatable-1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'ajax/server-side-mailing',
            },
            columns: [
                { data: 'subject' },
                { data: 'text' },
                { data: 'completed' },
                { data: 'completed_date' },
                { data: 'created_at' },
                { data: 'settings' },
            ],
            order: [[4, 'desc']],
            language: {
                "url": "<?php echo $system->adminPublicUrl("plugins/datatables/lang/" . $_SESSION["lang"] . ".json"); ?>"
            },
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        $('#datatable-1 tbody').on('click', 'button.post_delete', function () {
            Swal.fire({
                title: '<?php echo $admin_text['DELETE_ALERT_TITLE']; ?>',
                text: '<?php echo $admin_text['DELETE_ALERT_TEXT']; ?>',
                icon: '<?php echo $admin_text['DELETE_ALERT_ICON']; ?>',
                showCancelButton: true,
                confirmButtonText: '<?php echo $admin_text['DELETE_ALERT_BTN_YES']; ?>',
                cancelButtonText: '<?php echo $admin_text['DELETE_ALERT_BTN_NO']; ?>',
                confirmButtonColor: '<?php echo $admin_text['DELETE_ALERT_BTN_YES_COLOR']; ?>',
                cancelButtonColor: '<?php echo $admin_text['DELETE_ALERT_BTN_NO_COLOR']; ?>',
            }).then((result) => {
                if (result.value) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: $(this).data("delete-url"),
                            data: {'csrf_token':'<?php echo $session->get('csrf_token') ?>'},
                            success: function (response) {
                                if (response.success) {
                                    table.row($(this).parents('tr')).remove().draw();
                                }
                                if (response.reply) {
                                    AlertMessage("<?php echo $admin_text['DELETE_ALERT_ERR_TYPE']; ?>", "<?php echo $admin_text['DELETE_ALERT_ERR_TITLE']; ?>", response.reply);
                                }
                            },
                            dataType: 'json'
                        });
                    }
                }
            });
        });
    });
</script>