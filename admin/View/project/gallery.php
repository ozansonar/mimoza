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
                    <th>Adı</th>
                    <th>Resim</th>
                    <th>Tipi</th>
                    <th>Eklenme Tarihi</th>
                    <th>Durum</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($data->content as $row): ?>
                    <tr>
                        <td><?php echo $functions->textModal($row->name, 20); ?></td>
                        <td>
							<?php if (!empty($row->img) &&
                                file_exists($constants::fileTypePath["gallery"]["full_path"] . $row->img)): ?>
                                <a href="<?php echo $constants::fileTypePath["gallery"]["url"] . $row->img; ?>"
                                   data-toggle="lightbox" data-title="<?php echo $row->name; ?>" class="color-unset">
                                    <i class="fas fa-images"></i>
                                </a>
							<?php endif; ?>
                        </td>
                        <td><?php echo $constants::systemGalleryTypes[$row->type]["view_text"]; ?></td>
                        <td><?php echo $functions->dateShort($row->created_at); ?></td>
                        <td>
                            <span class="<?php echo $constants::systemStatus[$row->status]["view_class"]; ?>">
                                <?php echo $constants::systemStatus[$row->status]["view_text"]; ?>
                            </span>
                        </td>
                        <td>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) === true): ?>
                                <a  onclick="post_edit('<?php echo $system->adminUrl("gallery-settings?id=" . $row->id); ?>')"
                                    href="javascript:void()"
                                    class="btn btn-outline-success m-1">
                                    <i class="fas fa-pencil-alt px-1"></i>
                                    Düzenle
                                </a>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::deletePermissionKey) === true): ?>
                                <button type="button" class="btn btn-outline-danger m-1"
                                        onclick="post_delete('<?php echo $system->adminUrl("gallery?delete=" . $row->id); ?>')">
                                    <i class="fas fa-trash px-1"></i> Sil
                                </button>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl("gallery-image-upload", $constants::addPermissionKey) === true): ?>
                                <a href="<?php echo $system->adminUrl("gallery-image-upload?id=" . $row->id); ?>"
                                   class="btn btn-outline-primary m-1"><i class="fas fa-plus px-1"></i>
                                    Resim Ekle
                                </a>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl("video-upload", $constants::addPermissionKey) === true): ?>
                                <a href="<?php echo $system->adminUrl("gallery-video-upload?id=" . $row->id); ?>"
                                   class="btn btn-outline-warning m-1"><i class="fab fa-youtube px-1"></i>
                                    Video Ekle
                                </a>
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
