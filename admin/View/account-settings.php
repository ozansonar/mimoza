<?php
/**
 *
 * Created by PhpStorm.
 * User: Ozan SONAR ( ozansonar1@gmail.com )
 * Date: 31.05.2020
 * Time: 20:25
 */
?>
<?php require $adminSystem->adminView('static/header'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 admin-page-top-settings">
                    <div class="col-sm-6">
                        <h1>
                            <a href="javascript:goBack()"><i class="fas fa-arrow-circle-left"></i></a>
                            <?php echo $page_title; ?>
                        </h1>
                    </div>
                    <?php if($session->sessionRoleControl($page_role_key,$listPermissionKey) == true): ?>
                        <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                            <h1>
                                <a href="<?php echo $adminSystem->adminUrl($page_button_redirect_link); ?>">
                                    <i class="<?php echo !empty($page_button_icon) ? $page_button_icon:"fas fa-th-list"; ?>"></i>
                                    <?php echo $page_button_redirect_text; ?>
                                </a>
                            </h1>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- /.container-fluid -->
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
                    <h3 class="card-title"><?php echo $page_title; ?>
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
                    $form->lang = $default_lang->short_lang;
                    $form->formNameWithoutLangCode = 1;
                    echo $functions->csrfToken();
                    echo $form->input("email",array(
                        "label" => $admin_text["HESAP_EMAIL"],
                        "required" => 1,
                        "class" => "validate[custom[email]]"
                    ),$page_data);
                    echo $form->input("name",array(
                        "label" => $admin_text["HESAP_NAME"],
                        "required" => 1,
                    ),$page_data);
                    echo $form->input("surname",array(
                        "label" => $admin_text["HESAP_SURNAME"],
                        "required" => 1,
                    ),$page_data);
                    echo $form->file("img",array(
                        "label" => $admin_text["HESAP_RESIM"],
                        "file_key" => "user_image",
                        "delete_link" => "?id=".$page_data[$default_lang->short_lang]["id"]."&img_delete=1"
                    ),$page_data);
                    echo $form->input("password",array(
                        "label" => $admin_text["HESAP_PASSWORD"],
                        "class" => "validate[maxSize[50],minSize[10]]",
                        "type" => "password"
                    ),$page_data);
                    echo $form->input("password_again",array(
                        "label" => $admin_text["HESAP_PASSWORD_AGAIN"],
                        "class" => "validate[maxSize[50],minSize[10]]",
                        "type" => "password"
                    ),$page_data);
                    echo $form->select("theme",array(
                        "label" => $admin_text["ADMIN_THEMA"],
                        "required" => 1,
                        "select_item" => $adminPanelTheme,
                    ),$page_data);
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
        $(document).ready(function(){
            $("form#pageForm").validationEngine({promptPosition : "bottomLeft", scroll: false});
        });
    </script>
<?php require $adminSystem->adminView('static/footer'); ?>