<?php
/**
 * Created by PhpStorm.
 * User: ozan_
 * Date: 21.01.2019
 * Time: 23:40
 */
?>
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
                    <li class="pt-2 pr-3"><h3 class="card-title"><?php echo $data->title; ?></h3></li>
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
                                echo $form->input("title",array(
                                    "label" => $admin_text["SLIDER_TITLE"],
                                    "required" => 1,
                                ),$pageData);
                                echo $form->input("link",array(
                                    "label" => $admin_text["SLIDER_LINK"],
                                    "description" => $admin_text["MENU_DESCRIPTION"]
                                ),$pageData);
                                echo $form->checkbox(array(
                                    "option" => array(
                                        array(
                                            "label" => $admin_text["SLIDER_SITE_DISI_LINK"],
                                            "name" => "site_disi_link",
                                            "value" => 1
                                        )
                                    )
                                ),$pageData);
                                ?>
                                <div id="id_site_disi_link_div_<?php echo $project_languages_row->short_lang;?>" style="<?php echo isset($pageData[$project_languages_row->short_lang]) && $pageData[$project_languages_row->short_lang]["site_disi_link"] == 1 ? null:"display: none;"; ?>">
                                <?php
                                echo $form->input("back_link",array(
                                    "label" => $admin_text["SLIDER_DIS_LINK"],
                                    "required" => 1,
                                ),$pageData);
                                echo $form->checkbox(array(
                                    "option" => array(
                                        array(
                                            "label" => $admin_text["SLIDER_DIS_LINK_YENI_SEKME"],
                                            "name" => "yeni_sekme",
                                            "value" => 1
                                        )
                                    )
                                ),$pageData);
                                ?>
                                </div>
                                <?php
                                echo $form->textarea("abstract",array(
                                    "label" => $admin_text["SLIDER_SHORT_TEXT"],
                                    "class" => "ckeditor",
                                ),$pageData);
                                echo $form->textarea("text",array(
                                    "label" => $admin_text["SLIDER_TEXT"],
                                    "required" => 1,
                                    "class" => "ckeditor",
                                ),$pageData);
                                echo $form->file("img",array(
                                    "label" => $admin_text["SLIDER_IMG"],
                                    "file_key" => "slider",
                                ),$pageData);
                                echo $form->input("show_order",array(
                                    "label" => $admin_text["SLIDER_SHOW_ORDER"],
                                    "required" => 1,
                                    "class" => "numeric_field",
                                    "order" => 1,
                                ),$pageData);
                                echo $form->select("status",array(
                                    "label" => $admin_text["SLIDER_STATUS"],
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
<script src="<?php echo $system->adminPublicUrl("plugins/ckeditor/ckeditor.js"); ?>"></script>
<script>
    $(document).ready(function(){
        $("form#pageForm").validationEngine({promptPosition : "bottomLeft", scroll: false});
    });
    var roxyFileman = '<?php echo $system->adminPublicUrl('plugins/fileman/index.html'); ?>';
    <?php
    foreach ($projectLanguages as $project_languages_row){
        ?>
        CKEDITOR.replace('text_<?php echo $project_languages_row->short_lang; ?>',{filebrowserBrowseUrl:roxyFileman,
            filebrowserImageBrowseUrl:roxyFileman+'?type=image',
            removeDialogTabs: 'link:upload;image:upload'});
        $("#id_site_disi_link_<?php echo $project_languages_row->short_lang; ?>").change(function () {
             if($(this).is(':checked')){
                 $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?>").slideDown();
             }else{
                 $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?> input[type='checkbox']").prop("checked",false);
                 $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?> input[type='text']").val("");
                 $("#id_site_disi_link_div_<?php echo $project_languages_row->short_lang; ?>").slideUp();
             }
        });
        <?php
    }
    ?>
</script>
<?php require $system->adminView('static/footer'); ?>