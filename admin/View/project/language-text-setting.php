<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan
 * Date: 2.09.2021
 * Time: 22:41
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
                    <?php if($session->sessionRoleControl($data->pageRoleKey,$constants::listPermissionKey) == true && !empty($data->pageButtonRedirectLink)): ?>
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
                                <a class="nav-link <?php echo (int)$project_languages_row->default_lang === 1 ? "active":null; ?>" id="content-tab-<?php echo $project_languages_row->short_lang; ?>" data-toggle="pill" href="#content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tab" aria-controls="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" aria-selected="<?php echo (int)$project_languages_row->default_lang === 1 ? "true":"false"; ?>"><?php echo $project_languages_row->lang; ?>&nbsp;<i class="flag-icon flag-icon-<?php echo $project_languages_row->short_lang; ?>"></i></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
                <div class="card-body">
                    <?php echo $functions->csrfToken(); ?>
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <?php foreach ($projectLanguages as $project_languages_row){
                            ?>
                            <div class="tab-pane fade <?php echo (int)$project_languages_row->default_lang === 1 ? "show active":null; ?>" id="content-dashboard-<?php echo $project_languages_row->short_lang; ?>" role="tabpanel" aria-labelledby="content-tab-<?php echo $project_languages_row->short_lang; ?>">
                                <form action="" method="post" id="pageForm_<?php echo $project_languages_row->short_lang; ?>" enctype="multipart/form-data">
                                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                                    <?php
                                        $form->lang = $project_languages_row->short_lang;
                                        foreach($language_text_manager as $language_text_manager_key=>$language_text_manager_value){
                                            ?>
                                            <div class="border mb-4 p-2">
                                                <h4><?php echo $language_text_manager_value["title"]; ?></h4>
                                                <?php
                                                foreach($language_text_manager_value["form"] as $form_row){
                                                    if(isset($form_row["type"]) && $form_row["type"] == "textarea"){
                                                        $class = "";
                                                        if(isset($form_row["class"])){
                                                            $class = $form_row["class"];
                                                        }
                                                        echo $form->textarea($form_row["name"],array(
                                                            "label" => "--".$form_row["label"],
                                                            //"required" => 1,
                                                            "class" => $class,
                                                        ),$pageData);
                                                    }else{
                                                        echo $form->input($form_row["name"],array(
                                                            "label" => "--".$form_row["label"],
                                                            //"required" => isset($form_row["no_required"]) ? 0:1,
                                                            "class" => "validate[minSize[2]]",
                                                        ),$pageData);
                                                    }

                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    <input type="hidden" name="data_lang" value="<?php echo $project_languages_row->short_lang; ?>">
                                    <div class="col-12 text-right pr-0">
                                        <?php
                                        echo $form->button("submit",array(
                                            "text" => "Kaydet",
                                            "icon" => "fas fa-save",
                                            "btn_class" => "btn btn-success",
                                            "form_name" => "pageForm_".$project_languages_row->short_lang
                                        ));
                                        ?>
                                    </div>
                                </form>
                            </div>
                            <?php
                        } ?>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">

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
            <?php foreach ($projectLanguages as $project_languages_row){
            ?>
            $("form#pageForm_<?php echo $project_languages_row->short_lang;?>").validationEngine({promptPosition : "bottomLeft", scroll: false});
            <?php
            } ?>
        });
    </script>

<?php require $system->adminView('static/footer'); ?>