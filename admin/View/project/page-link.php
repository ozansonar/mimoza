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
				<?php foreach ($data->content as $row): ?>
                    <tr>
                        <td><?php echo $functions->textModal($row->url); ?></td>
                        <td><?php echo $row->controller; ?></td>
                        <td><?php echo $projectLanguages[$row->lang]->lang ?? null; ?></td>
                        <td><?php echo $functions->dateShort($row->created_at); ?></td>
                        <td>
                            <span class="<?php echo $constants::systemStatus[$row->status]["view_class"]; ?>"><?php echo $constants::systemStatus[$row->status]["view_text"]; ?></span>
                        </td>
                        <td>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) === true): ?>
                                <a onclick="post_edit('<?php echo $system->adminUrl("page-link-settings?id=" . $row->id); ?>')"
                                   href="javascript:void()"
                                   class="btn btn-outline-success m-1">
                                    <i class="fas fa-pencil-alt px-1"></i>
                                    Düzenle
                                </a>
							<?php endif; ?>
							<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::deletePermissionKey) === true): ?>
                                <button type="button" class="btn btn-outline-danger  m-1"
                                        onclick="post_delete('<?php echo $system->adminUrl("page-link?delete=" . $row->id); ?>')">
                                    <i class="fas fa-trash px-1 px-1"></i>
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