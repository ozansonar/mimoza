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
            <form enctype="multipart/form-data" method="post" id="pageForm">
				<?php echo $functions->csrfToken(); ?>
                <div class="form-group">
                    <label for="id_group_name"><?php echo $admin_text["ADMIN_ROLE_ROLE_NAME"]; ?></label>
                    <input type="text" class="form-control validate[required]" name="group_name" id="id_group_name"
                           placeholder="<?php echo $admin_text["ADMIN_ROLE_ROLE_NAME"] ?>"
                           value="<?php echo $data->pageData->group_name ??  NULL ?>"/>
                </div>
                <div class="col-12 p-0"><h3>Yetkiler</h3></div>

                <div class="row">
					<?php foreach ($menu as $mainUrl => $menuItem): ?>
						<?php $rand = rand(1, 99999); ?>
                        <div class="col-12 table-bordered m-2 p-2">
                            <div class="form-group mb-3 mb-md-2">
								<?php if (isset($menuItem["submenu"])): ?>
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" class="<?php echo $rand ?>_top"
                                               id="id_permission_<?php echo $menuItem["url"] . "_s"; ?>"
                                               name="permissions[<?php echo $menuItem["url"]; ?>][<?php echo "s"; ?>]"
                                               value="<?php echo "s"; ?>" <?php echo isset($data->role_array[$menuItem["url"]]) && in_array("s", $data->role_array[$menuItem["url"]]) ? "checked" : null; ?>>
                                        <label for="id_permission_<?php echo $menuItem["url"] . "_s"; ?>">
											<?php echo $menuItem["title"]; ?>
                                        </label>
                                    </div>
								<?php else: ?>
                                    <label class="font-weight-semibold"><?php echo $menuItem["title"]; ?></label>
								<?php endif; ?>
                                <div class="row p-0 m-0">
									<?php if (isset($menuItem['submenu'])): ?>
										<?php foreach ($menuItem['submenu'] as $k => $submenu): ?>
                                            <div class="col-12 mt-2">
												<?php echo $submenu['title']; ?>
												<?php if (isset($submenu["permissions"])): ?>
                                                    <div class="row pl-2 mt-1">
														<?php foreach ($submenu["permissions"] as $per_key => $per_val): ?>
                                                            <div class="icheck-primary d-inline pr-4 <?php echo $per_key != 0 ? "pl-4" : null; ?>">
                                                                <input type="checkbox" class="check-click"
                                                                       data-permission-category="<?php echo $rand; ?>"
                                                                       id="id_permission_<?php echo $submenu["url"] . "_" . $per_key; ?>"
                                                                       name="permissions[<?php echo $submenu["url"]; ?>][<?php echo $per_key; ?>]"
                                                                       value="<?php echo $per_key; ?>" <?php echo isset($data->role_array[$submenu["url"]]) && in_array($per_key, $data->role_array[$submenu["url"]], true) ? "checked" : null; ?>>
                                                                <label for="id_permission_<?php echo $submenu["url"] . "_" . $per_key; ?>">
																	<?php echo $per_val; ?>
                                                                </label>
                                                            </div>
														<?php endforeach; ?>
                                                    </div>
												<?php endif; ?>
                                            </div>
										<?php endforeach; ?>
									<?php else: ?>
										<?php if (isset($menuItem["permissions"])): ?>
                                            <div class="row pl-2 mt-1">
												<?php foreach ($menuItem["permissions"] as $per_key => $per_val): ?>
                                                    <div class="icheck-primary d-inline pr-4">
                                                        <input type="checkbox"
                                                               id="id_permission_<?php echo $menuItem["url"] . "_" . $per_key; ?>"
                                                               name="permissions[<?php echo $menuItem["url"]; ?>][<?php echo $per_key; ?>]"
                                                               value="<?php echo $per_key; ?>" <?php echo isset($data->role_array[$menuItem["url"]]) && in_array($per_key, $data->role_array[$menuItem["url"]], true) ? "checked" : null; ?> >
                                                        <label for="id_permission_<?php echo $menuItem["url"] . "_" . $per_key; ?>">
															<?php echo $per_val; ?>
                                                        </label>
                                                    </div>
												<?php endforeach; ?>
                                            </div>
										<?php endif; ?>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
					<?php foreach ($data->extra as $extra_row): ?>
                        <div class="col-12 table-bordered m-2 p-2">
                            <label class="font-weight-semibold"><?php echo $extra_row["title"]; ?></label>
                            <div class="row p-0 m-0">
								<?php foreach ($extra_row["permissions"] as $permission_extra_key => $permission_extra): ?>
                                    <div class="icheck-primary d-inline pr-4">
                                        <input type="checkbox"
                                               id="id_permission_<?php echo $extra_row["url"] . "_" . $permission_extra_key; ?>"
                                               name="permissions[<?php echo $extra_row["url"]; ?>][<?php echo $permission_extra_key; ?>]"
                                               value="<?php echo $permission_extra_key; ?>" <?php echo isset($data->role_array[$extra_row["url"]]) && in_array($permission_extra_key, $data->role_array[$extra_row["url"]]) ? "checked" : null; ?>>
                                        <label for="id_permission_<?php echo $extra_row["url"] . "_" . $permission_extra_key; ?>">
											<?php echo $permission_extra; ?>
                                        </label>
                                    </div>
								<?php endforeach; ?>
                            </div>
                        </div>
					<?php endforeach; ?>
					<?php
					//eğer ön yüz içinde yetki gerekiyorsa burdan yapabilirsiniz
					if (!empty($data->onyuz_yetkiler)):
						?>
                        <div class="col-12 my-2"><h3>Önyüz Yetkileri</h3></div>
						<?php foreach ($data->onyuz_yetkiler as $on_key => $on_row): ?>
						<?php $rand = rand(1, 99999); ?>
                        <div class="col-12 table-bordered m-2 p-2">
                            <div class="form-group mb-3 mb-md-2">
								<?php if (isset($on_row["submenu"])): ?>
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" class="<?php echo $rand ?>_top"
                                               id="id_permission_<?php echo $on_row["url"] . "_s"; ?>"
                                               name="permissions[<?php echo $on_row["url"]; ?>][<?php echo "s"; ?>]"
                                               value="<?php echo "s"; ?>" <?php echo isset($data->role_array[$on_row["url"]]) && in_array("s", $data->role_array[$on_row["url"]]) ? "checked" : null; ?>>
                                        <label for="id_permission_<?php echo $on_row["url"] . "_s"; ?>">
											<?php echo $on_row["title"]; ?>
                                        </label>
                                    </div>
								<?php else: ?>
                                    <label class="font-weight-semibold"><?php echo $on_row["title"]; ?></label>
								<?php endif; ?>
                                <div class="row p-0 m-0">
									<?php if (isset($on_row['submenu'])): ?>
										<?php foreach ($on_row['submenu'] as $k => $submenu): ?>
                                            <div class="col-12 mt-2">
												<?php echo $submenu['title']; ?>
												<?php if (isset($submenu["permissions"])): ?>
                                                    <div class="row pl-2 mt-1">
														<?php foreach ($submenu["permissions"] as $per_key => $per_val): ?>
                                                            <div class="icheck-primary d-inline pr-4 <?php echo $per_key != 0 ? "pl-4" : null; ?>">
                                                                <input type="checkbox" class="check-click"
                                                                       data-permission-category="<?php echo $rand; ?>"
                                                                       id="id_permission_<?php echo $submenu["url"] . "_" . $per_key; ?>"
                                                                       name="permissions[<?php echo $submenu["url"]; ?>][<?php echo $per_key; ?>]"
                                                                       value="<?php echo $per_key; ?>" <?php echo isset($data->role_array[$submenu["url"]]) && in_array($per_key, $data->role_array[$submenu["url"]]) ? "checked" : null; ?>>
                                                                <label for="id_permission_<?php echo $submenu["url"] . "_" . $per_key; ?>">
																	<?php echo $per_val; ?>
                                                                </label>
                                                            </div>
														<?php endforeach; ?>
                                                    </div>
												<?php endif; ?>
                                            </div>
										<?php endforeach; ?>
									<?php else: ?>
										<?php if (isset($on_row["permissions"])): ?>
                                            <div class="row pl-2 mt-1">
												<?php foreach ($on_row["permissions"] as $per_key => $per_val): ?>
                                                    <div class="icheck-primary d-inline pr-4">
                                                        <input type="checkbox"
                                                               id="id_permission_<?php echo $on_row["url"] . "_" . $per_key; ?>"
                                                               name="permissions[<?php echo $on_row["url"]; ?>][<?php echo $per_key; ?>]"
                                                               value="<?php echo $per_key; ?>" <?php echo isset($data->role_array[$on_row["url"]]) && in_array($per_key, $data->role_array[$on_row["url"]]) ? "checked" : null; ?> >
                                                        <label for="id_permission_<?php echo $on_row["url"] . "_" . $per_key; ?>">
															<?php echo $per_val; ?>
                                                        </label>
                                                    </div>
												<?php endforeach; ?>
                                            </div>
										<?php endif; ?>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
					<?php
					endif;
					?>
                </div>
                <hr>
            </form>
        </div>
        <div class="card-footer text-right">
            <button type="submit" name="submit" form="pageForm" value="1" class="btn btn-success ml-3">
                Kaydet <i class="fas fa-save ml-2"></i>
            </button>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $("form#pageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
    });
    $(".check-click").click(function () {
        var val = parseInt($(this).data("permission-category"));
        var kac_tane = $('*[data-permission-category="' + val + '"]').length;
        var secilmis = 0;
        $.each($('*[data-permission-category="' + val + '"]'), function (key, value) {
            if ($(this).is(':checked')) {
                secilmis++;
            }
        });
        if ($(this).is(':checked')) {
            if (!$("." + val + "_top").is(':checked')) {
                $("." + val + "_top").trigger("click");
            }
        } else {
            if (secilmis == 0) {
                $("." + val + "_top").trigger("click");
            }
        }
    });
</script>