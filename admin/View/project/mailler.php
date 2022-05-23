<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo $data->title; ?>
				<?php if (!empty($sub_title)): ?>
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
                        <td><?php echo $functions->textModal($row->subject, 20); ?></td>
                        <td><?php echo $functions->textModal($row->text, 20); ?></td>
                        <td>
                            <span class="<?php echo $constants::mailingSendStatus[$row->completed]["view_class"]; ?>"><?php echo $constants::mailingSendStatus[$row->completed]["view_text"]; ?></span>
                        </td>
                        <td><?php echo (int)$row->completed === 1 ? $functions->dateLong($row->completed_date) : null; ?></td>
                        <td><?php echo $functions->dateLong($row->created_at); ?></td>
                        <td>
                            <span class="<?php echo $constants::systemStatus[$row->status]["view_class"]; ?>"><?php echo $constants::systemStatus[$row->status]["view_text"]; ?></span>
                        </td>
                        <td>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) === true): ?>
                                <button type="button" class="btn btn-outline-success m-1"
                                        onclick="post_edit('<?php echo $system->adminUrl($pageAddRoleKey . "?id=" . $row->id); ?>')">
                                    <i class="fas fa-pencil-alt px-1 px-1"></i></i>Düzenle
                                </button>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::deletePermissionKey) === true): ?>
                                <button type="button" class="btn btn-outline-danger  m-1"
                                        onclick="post_delete('<?php echo $system->adminUrl($data->pageRoleKey . "?delete=" . $row->id); ?>')">
                                    <i class="fas fa-trash px-1 px-1"></i> Sil
                                </button>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::detailPermissionKey) === true): ?>
                                <a href="<?php echo $system->adminUrl("mailing-view?id=" . $row->id); ?>"
                                   class="btn btn-outline-primary"><i class="fas fa-desktop px-1"></i>Detaylar</a>
							<?php endif; ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $("#datatable-1").DataTable({
            /*"language": {
				"url": "<?php echo $system->adminPublicUrl("plugins/datatables/lang/" . $_SESSION["lang"] . ".json"); ?>"
                },*/
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
    });
</script>