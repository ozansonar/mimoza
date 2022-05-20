<?php
/**
 *
 * Created by PhpStorm.
 * User: Ozan SONAR ( ozansonar1@gmail.com )
 * Date: 25.05.2020
 * Time: 18:08
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
                            <?php echo $page_title; ?>
                        </h1>
                    </div>
                    <?php if($session->sessionRoleControl($page_role_key,$constants::listPermissionKey) == true): ?>
                        <div class="col-sm-6 d-md-flex align-items-md-center justify-content-md-end">
                            <h1>
                                <a href="<?php echo $system->adminUrl($page_button_redirect_link); ?>">
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
                    <form  enctype="multipart/form-data" method="post" id="pageForm">
                        <div class="card text-white bg-success">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Mesaj hareketleri</h4></div>
                            <div class="card-body">
                                <p class="card-text"><span class="font-weight-bold"><?php echo $functions->date_long($data->created_at) ?></span> tarihinde <sapn class="font-weight-bold"><?php echo $data->name." ".$data->surname ?></sapn> tarafından gönderildi.</p>
                                <?php if(!empty($readUser)): ?>
                                <p class="card-text"><span class="font-weight-bold"><?php echo $functions->date_long($data->read_date) ?></span> tarihinde <sapn class="font-weight-bold"><?php echo $readUser->name." ".$readUser->surname ?></sapn> tarafından okundu.</p>
                                <?php endif; ?>
                                <?php if(!empty($data->reply_send_user_id)): ?>
                                    <?php $reply_user = $session->getUserInfo($data->reply_send_user_id); ?>
                                    <p class="card-text"><span class="font-weight-bold"><?php echo $functions->date_long($data->reply_send_date) ?></span> tarihinde <sapn class="font-weight-bold"><?php echo $reply_user->name." ".$reply_user->surname ?></sapn> tarafından cevaplandı.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <h3>Gönderen Bilgileri</h3>
                                <?php
                                $form->lang = $default_lang->short_lang;
                                $form->formNameWithoutLangCode = 1;
                                echo $form->input("name",array(
                                    "label" => $admin_text["CONTACT_NAME"],
                                    "required" => 1,
                                    "disabled" => 1,
                                ),$pageData);
                                echo $form->input("surname",array(
                                    "label" => $admin_text["CONTACT_SURNAME"],
                                    "required" => 1,
                                    "disabled" => 1,
                                ),$pageData);
                                echo $form->input("email",array(
                                    "label" => $admin_text["CONTACT_EMAIL"],
                                    "required" => 1,
                                    "disabled" => 1,
                                ),$pageData);
                                echo $form->input("subject",array(
                                    "label" => $admin_text["CONTACT_SUBJECT"],
                                    "required" => 1,
                                    "disabled" => 1,
                                ),$pageData);
                                echo $form->textarea("message",array(
                                    "label" => $admin_text["CONTACT_MESSAGE"],
                                    "required" => 1,
                                    "disabled" => 1,
                                ),$pageData);
                                ?>
                            </div>
                            <div class="col-md-6 col-12">
                                <h3>Mesaja cevap ver</h3>
                                <?php
                                if(!empty($data->reply_send_user_id)){
                                    echo $form->input("reply_subject",array(
                                        "label" => $admin_text["CONTACT_REPLY_SUBJECT"],
                                        "required" => 1,
                                        "disabled" => 1,
                                    ),$pageData);
                                    echo $form->textarea("reply_text",array(
                                        "label" => $admin_text["CONTACT_REPLY_TEXT"],
                                        "required" => 1,
                                        "disabled" => 1,
                                    ),$pageData);
                                }else{
                                    echo $form->input("reply_subject",array(
                                        "label" => $admin_text["CONTACT_REPLY_SUBJECT"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->textarea("reply_text",array(
                                        "label" => $admin_text["CONTACT_REPLY_TEXT"],
                                        "required" => 1,
                                    ),$pageData);
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <?php
                    if(empty($data->reply_send_user_id)):
                        echo $form->button("submit",array(
                            "text" => "Cevapla",
                            "icon" => "fas fa-save",
                            "btn_class" => "btn btn-success",
                        ));
                    endif;
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
<?php require $system->adminView('static/footer'); ?>