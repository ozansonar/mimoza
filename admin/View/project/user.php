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
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>E-posta</th>
                        <th>Telefon</th>
                        <th>Resim</th>
                        <th>Eklenme Tarihi</th>
                        <th>Durum</th>
                        <th>Kullanıcı Tipi</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data->content as $row): ?>
                        <tr>
                            <td><?php echo $row->name; ?></td>
                            <td><?php echo $row->surname; ?></td>
                            <td><?php echo $row->email; ?></td>
                            <td><?php echo $row->telefon; ?></td>
                            <td>
                                <?php if(!empty($row->img) && file_exists($constants::fileTypePath["user_image"]["full_path"].$row->img)): ?>
                                    <a href="<?php echo $constants::fileTypePath["user_image"]["url"].$row->img; ?>"
                                       data-toggle="lightbox" data-title="<?php echo $row->name." ".$row->surname; ?>" class="color-unset">
                                        <i class="fas fa-images"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $functions->dateShort($row->created_at); ?></td>
                            <td>
                                <span class="<?php echo $constants::systemStatus[$row->status]["view_class"]; ?>">
                                    <?php echo $constants::systemStatus[$row->status]["view_text"]; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $constants::systemAdminUserType[$row->rank]["view_text"]; ?>
                            </td>
                            <td>
								<?php if ($session->sessionRoleControl($data->pageRoleKey, $constants::editPermissionKey) === true): ?>
                                    <a onclick="post_edit('<?php echo $system->adminUrl("user-settings?id=".$row->id); ?>')"
                                       href="javascript:void()"
                                       class="btn btn-outline-success m-1">
                                        <i class="fas fa-pencil-alt px-1"></i>
                                        Düzenle
                                    </a>
								<?php endif; ?>
                                <?php if((int)$_SESSION["user_id"] !== (int)$row->id && $session->sessionRoleControl($data->pageRoleKey,$constants::deletePermissionKey) === true): ?>
                                    <button type="button" class="btn btn-outline-danger m-1"
                                            onclick="post_delete('<?php echo $system->adminUrl("user?delete=".$row->id); ?>')">
                                        <i class="fas fa-trash px-1"></i>
                                        Sil
                                    </button>
                                <?php endif; ?>
                                <?php if($session->sessionRoleControl("user-tracing",$constants::listPermissionKey) === true): ?>
                                    <a href="<?php echo $system->adminUrl("user-tracing?id=".$row->id); ?>"
                                       class="btn btn-outline-primary"><i class="fas fa-desktop px-1"></i>
                                        Kullanıcı Hareketleri
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
    $(document).ready(function(){
        $("#datatable-1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#datatable-1_wrapper .col-md-6:eq(0)');
    });
</script>
