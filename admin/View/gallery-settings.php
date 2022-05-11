<?php
/**
 * Created by PhpStorm.
 * User: ozan_
 * Date: 21.01.2019
 * Time: 23:40
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
            <div class="card card-danger card-outline card-outline-tabs">
                <div class="card-header">
                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                        <li class="pt-2 pr-3"><h3 class="card-title"><?php echo $page_title; ?></h3></li>
                        <?php foreach ($projectLanguages as $project_languages_row){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $project_languages_row->default_lang == 1 ? "active":null; ?>" id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill" href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab" aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" aria-selected="<?php echo $project_languages_row->default_lang == 1 ? "true":"false"; ?>"><?php echo $project_languages_row->lang; ?>&nbsp;<i class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="pageForm" enctype="multipart/form-data">
                        <?php echo $functions->csrfToken(); ?>
                        <div class="tab-content" id="custom-tabs-two-tabContent">
                            <?php foreach ($projectLanguages as $project_languages_row){
                                ?>
                                <div class="tab-pane fade <?php echo $project_languages_row->default_lang == 1 ? "show active":null; ?>" id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel" aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
                                    <?php
                                    $form->lang = $project_languages_row->short_lang;
                                    echo $form->input("name",array(
                                        "label" => $admin_text["GALERI_NAME"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->input("link",array(
                                        "label" => $admin_text["GALERI_LINK"],
                                    ),$pageData);
                                    echo $form->select("type",array(
                                        "label" => $admin_text["GALERI_TYPE"],
                                        "required" => 1,
                                        "select_item" => $systemGalleryTypesVersion2,
                                    ),$pageData);
                                    ?>
                                    <div id="top_gallery_div_<?php echo $project_languages_row->short_lang; ?>" style="<?php echo isset($pageData[$project_languages_row->short_lang]) && $pageData[$project_languages_row->short_lang]["type"] == 2 ? null:"display: none;"; ?>">
                                        <?php
                                        echo $form->select("top_id",array(
                                            "label" => $admin_text["GALERI_TOP_TYPE"],
                                            "required" => 1,
                                            "select_item" => $gallery_data_array,
                                        ),$pageData);
                                        ?>
                                    </div>
                                    <?php
                                    echo $form->input("show_order",array(
                                        "label" => $admin_text["GALERI_ORDER"],
                                        "required" => 1,
                                        "class" => "numeric_field",
                                        "order" => 1,
                                    ),$pageData);
                                    echo $form->file("img",array(
                                        "label" => $admin_text["GALERI_IMG"],
                                        "file_key" => "gallery",
                                    ),$pageData);
                                    echo $form->select("status",array(
                                        "label" => $admin_text["GALERI_STATUS"],
                                        "required" => 1,
                                        "select_item" => $systemStatusVersion,
                                    ),$pageData);

                                    ?>
                                </div>
                                <?php
                            } ?>
                        </div>
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
        <?php foreach ($projectLanguages as $project_languages_row){
        ?>
        $("#id_type_<?php echo $project_languages_row->short_lang; ?>").change(function () {
            var  this_val = $(this).val();
            if(this_val == 2){
                $("#top_gallery_div_<?php echo $project_languages_row->short_lang; ?>").slideDown();
            }else{
                $("#top_gallery_div_<?php echo $project_languages_row->short_lang; ?>").slideUp();
                $("#top_gallery_div_<?php echo $project_languages_row->short_lang; ?> option:selected").prop("selected", false);
            }
        });
        <?php
        } ?>
    </script>
<?php require $adminSystem->adminView('static/footer'); ?>