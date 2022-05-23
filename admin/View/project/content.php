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
            <table id="datatable-1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Eklenme Tarihi</th>
                    <th>Resim</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($data as $row): ?>
                    <tr>
                        <td>
							<?php echo $functions->textModal($row->title, 20); ?>
                        </td>
                        <td>
							<?php echo $cats[$row->cat_id]; ?>
                        </td>
                        <td><?php echo $functions->dateShort($row->created_at); ?></td>
                        <td>
							<?php if (!empty($row->img) && file_exists($constants::fileTypePath["content"]["full_path"] . $row->img)): ?>
                                <a href="<?php echo $constants::fileTypePath["content"]["url"] . $row->img; ?>"
                                   data-toggle="lightbox" data-title="<?php echo $row->title; ?>" class="color-unset">
                                    <i class="fas fa-images"></i>
                                </a>
							<?php endif; ?>
                        </td>
                        <td>
                            <span class="<?php echo $constants::systemStatus[$row->status]["view_class"]; ?>"><?php echo $constants::systemStatus[$row->status]["view_text"]; ?></span>
                        </td>
                        <td>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) == true): ?>
                                <button type="button" class="btn btn-outline-success m-1"
                                        onclick="post_edit('<?php echo $system->adminUrl("content-settings?id=" . $row->id); ?>')">
                                    <i class="fas fa-pencil-alt px-1"></i></i>Düzenle
                                </button>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::deletePermissionKey) == true): ?>
                                <button type="button" class="btn btn-outline-danger  m-1"
                                        onclick="post_delete('<?php echo $system->adminUrl("content?delete=" . $row->id); ?>')">
                                    <i class="fas fa-trash px-1"></i> Sil
                                </button>
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