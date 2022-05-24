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
            <table id="datatable-1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Eklenme Tarihi</th>
                    <th>Resim</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($data->content as $row): ?>
                    <tr>
                        <td>
							<?php echo $functions->textModal($row->title, 20); ?>
                        </td>
                        <td><?php echo $functions->dateShort($row->created_at); ?></td>
                        <td>
							<?php if (!empty($row->img) && file_exists($constants::fileTypePath["page_image"]["full_path"] . $row->img)): ?>
                                <a href="<?php echo $constants::fileTypePath["page_image"]["url"] . $row->img; ?>"
                                   data-toggle="lightbox" data-title="<?php echo $row->title; ?>" class="color-unset">
                                    <i class="fas fa-images"></i>
                                </a>
							<?php endif; ?>
                        </td>
                        <td>
                            <span class="<?php echo $constants::systemStatus[$row->status]["view_class"]; ?>">
                                <?php echo $constants::systemStatus[$row->status]["view_text"]; ?>
                            </span>
                        </td>
                        <td>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) === true): ?>
                                <button type="button" class="btn btn-outline-success m-1"
                                        onclick="post_edit('<?php echo $system->adminUrl("page-settings?id=" . $row->id); ?>')">
                                    <i class="fas fa-pencil-alt px-1"></i>
                                    Düzenle
                                </button>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::deletePermissionKey) === true): ?>
                                <button type="button" class="btn btn-outline-danger m-1"
                                        onclick="post_delete('<?php echo $system->adminUrl("page?delete=" . $row->id); ?>')">
                                    <i class="fas fa-trash px-1"></i>
                                    Sil
                                </button>
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
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
    });
</script>