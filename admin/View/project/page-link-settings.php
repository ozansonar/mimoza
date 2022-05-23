<?php require $system->adminView('static/header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 admin-page-top-settings">
                <div class="col-sm-6">
                    <h1>
                        <a href="javascript:goBack()"><i class="fas fa-arrow-circle-left"></i></a>
                        <?php echo $data->title; ?>
                    </h1>
                </div>
                <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::listPermissionKey) == true): ?>
                    <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                        <h1>
                            <a href="<?php echo $system->adminUrl($data->pageButtonRedirectLink); ?>">
                                <i class="<?php echo !empty($data->pageButtonIcon) ? $data->pageButtonIcon:"fas fa-th-list"; ?>"></i>
                                <?php echo $data->pageButtonRedirectText; ?>
                            </a>
                        </h1>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <?php if(!empty($sub_title)): ?>
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
                <?php echo $sub_title; ?>
            </div>
        <?php endif; ?>

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $data->title; ?>
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
                <form action="" method="post" id="pageForm" enctype="multipart/form-data">
                    <?php
                    echo $functions->csrfToken();
                    $form->lang = $default_lang->short_lang;
                    $form->formNameWithoutLangCode = 1;
                    echo $form->input("url",array(
                        "label" => $admin_text["PG_SET_LINK"],
                        "required" => 1,
                    ),$pageData);
                    echo $form->select("controller",array(
                        "label" => $admin_text["PG_SET_FILE"],
                        "required" => 1,
                        "select_item" => $pl_controller,
                    ),$pageData);
                    echo $form->select("lang",array(
                        "label" => $admin_text["PG_SET_LANG"],
                        "required" => 1,
                        "select_item" => $pageLang,
                    ),$pageData);
                    echo $form->select("status",array(
                        "label" => $admin_text["PG_SET_STATUS"],
                        "required" => 1,
                        "select_item" => $systemStatusVersion,
                    ),$pageData);
                    ?>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right">
                <?php
                echo $form->button("submit",array(
                    "text" => "Kaydet",
                    "icon" => "fas fa-save",
                    "btn_class" => "btn btn-success",
                ));
                ?>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $("form#pageForm").validationEngine({promptPosition : "bottomLeft", scroll: false});
    });

</script>

<?php require $system->adminView('static/footer'); ?>
