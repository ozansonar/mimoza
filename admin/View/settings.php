<?php
/**
 *
 * Created by PhpStorm.
 * User: Ozan SONAR ( ozansonar1@gmail.com )
 * Date: 23.05.2020
 * Time: 23:14
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
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><i class="fas fa-cogs"></i> &nbsp;&nbsp; Site Ayarları</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Sosyal Medya Ayarları</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="fas fa-cogs"></i>&nbsp;&nbsp;İletişim Ayarları</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#mail-smtp" role="tab"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Mail SMTP Ayarları</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#page-banner-image" role="tab"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Sayfa Banner Resmi</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#lang_content_prefix" role="tab"><i class="fas fa-cogs"></i>&nbsp;&nbsp;İçerik Linkleri</a> </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="pageForm" enctype="multipart/form-data">
                        <?php echo $functions->csrfToken(); ?>
                        <div class="tab-content" id="custom-tabs-two-tabContent">
                            <div class="tab-pane fade show active" id="home">
                                <?php
                                $form->lang = $default_lang->short_lang;
                                $form->formNameWithoutLangCode = 1;
                                echo $form->input("project_name",array(
                                    "label" => $admin_text["SETTINGS_PROJECT_NAME"],
                                    "required" => 1,
                                    "class" => "validate[required,maxSize[100],minSize[5]]"
                                ),$pageData);
                                echo $form->input("title",array(
                                    "label" => $admin_text["SETTINGS_TITLE"],
                                    "required" => 1,
                                    "class" => "validate[required,maxSize[100],minSize[5]]"
                                ),$pageData);
                                echo $form->input("logo_text",array(
                                    "label" => $admin_text["SETTINGS_LOGO_TEXT"],
                                ),$pageData);
                                echo $form->file("fav_icon",array(
                                    "label" => $admin_text["SETTINGS_FAV_ICON"],
                                    "file_key" => "project_image",
                                ),$pageData);
                                echo $form->file("header_logo",array(
                                    "label" => $admin_text["SETTINGS_LOGO"],
                                    "file_key" => "project_image",
                                ),$pageData);
                                echo $form->file("footer_logo",array(
                                    "label" => $admin_text["SETTINGS_LOGO_FOOTER"],
                                    "file_key" => "project_image",
                                ),$pageData);
                                echo $form->select("link_sort_lang",array(
                                    "label" => "Linklerde dil kısaltması olsun ?",
                                    "required" => 1,
                                    "select_item" => $systemYesAndNoVersion2,
                                ),$pageData);
                                echo $form->input_tags("keywords",array(
                                    "label" => $admin_text["SETTINGS_KEYWORDS"],
                                    "class" => "w-100",
                                ),$pageData);
                                echo $form->textarea("description",array(
                                    "label" => $admin_text["SETTINGS_DESCRIPTION"],
                                ),$pageData);
                                echo $form->select("theme",array(
                                    "label" => $admin_text["SETTINGS_THEMA"],
                                    "required" => 1,
                                    "select_item" => $themes,
                                ),$pageData);
                                echo $form->select("site_status",array(
                                    "label" => $admin_text["SETTINGS_STATUS"],
                                    "required" => 1,
                                    "select_item" => $systemSiteModVersion2,
                                ),$pageData);
                                ?>
                            </div>
                            <div class="tab-pane fade" id="profile">
                                <div class="alert bg-warning alert-styled-left alert-dismissible">
                                    Sadece kullanıcı isimlerini yazmanız yeterlidir.
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["google"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["google"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="google" value="<?php echo isset($pageData[$default_lang->short_lang]->google) ? $pageData[$default_lang->short_lang]->google:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["facebook"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["facebook"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="facebook" value="<?php echo isset($pageData[$default_lang->short_lang]->facebook) ? $pageData[$default_lang->short_lang]->facebook:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["twitter"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["twitter"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="twitter" value="<?php echo isset($pageData[$default_lang->short_lang]->twitter) ? $pageData[$default_lang->short_lang]->twitter:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["instagram"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["instagram"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="instagram" value="<?php echo isset($pageData[$default_lang->short_lang]->instagram) ? $pageData[$default_lang->short_lang]->instagram:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["youtube"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["youtube"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="youtube" value="<?php echo isset($pageData[$default_lang->short_lang]->youtube) ? $pageData[$default_lang->short_lang]->youtube:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["linkedin"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["linkedin"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="linkedin" value="<?php echo isset($pageData[$default_lang->short_lang]->linkedin) ? $pageData[$default_lang->short_lang]->linkedin:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["whatsapp"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["whatsapp"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="whatsapp" value="<?php echo isset($pageData[$default_lang->short_lang]->whatsapp) ? $pageData[$default_lang->short_lang]->whatsapp:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["vk"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["vk"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="vk" value="<?php echo isset($pageData[$default_lang->short_lang]->vk) ? $pageData[$default_lang->short_lang]->vk:null; ?>" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="col-12 p-0">
                                        <label for=""><?php echo $socialMedia["telegram"]["title"];  ?></label>
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $socialMedia["telegram"]["url"];  ?></span>
                                    </div>
                                    <input type="text" name="telegram" value="<?php echo isset($pageData[$default_lang->short_lang]->telegram) ? $pageData[$default_lang->short_lang]->telegram:null; ?>" class="form-control">
                                </div>

                            </div>
                            <div class="tab-pane fade" id="messages">
                                <div class="alert bg-warning alert-styled-left alert-dismissible">
                                    Numaraların başında <span class="font-weight-semibold">0</span> olmadan yazınız.
                                </div>
                                <?php
                                echo $form->input("site_mail",array(
                                    "label" => $admin_text["SETTINGS_MAIL"],
                                    "required" => 1
                                ),$pageData);
                                echo $form->input("phone",array(
                                    "label" => $admin_text["SETTINGS_TELEFONU"],
                                    "class" => "numeric_field"
                                ),$pageData);
                                /*
                                echo $form->input("phone_cep",array(
                                    "label" => $admin_text["SETTINGS_CEP_TELEFONU"],
                                    "class" => "numeric_field"
                                ),$pageData);
                                */
                                echo $form->input("fax",array(
                                    "label" => $admin_text["SETTINGS_FAX"],
                                    "class" => "numeric_field"
                                ),$pageData);
                                echo $form->input("maps",array(
                                    "label" => $admin_text["SETTINGS_GOOGLE_MAPS"],
                                ),$pageData);
                                echo $form->textarea("adres",array(
                                    "label" => $admin_text["SETTINGS_ADRESS"],
                                ),$pageData);
                                ?>
                            </div>
                            <div class="tab-pane fade" id="mail-smtp">
                                <div class="form-group bt-switch">
                                    <label for="id_mail_send_mode"><?php echo $admin_text["SETTINGS_MAIL_SMTMP_MODE"]; ?></label>
                                    <div class="m-b-30">
                                        <input type="checkbox" name="mail_send_mode" id="id_mail_send_mode" data-on-color="success" data-off-color="danger" data-on-text="Aktif" data-off-text="Pasif" <?php if(isset($mail_send_mode) && $mail_send_mode == 1):echo "checked";elseif (isset($settings->mail_send_mode) && $settings->mail_send_mode == 1):echo "checked";endif; ?>>
                                    </div>
                                </div>
                                <div id="mail_mode_on" style="<?php echo $settings->mail_send_mode == 1 ? "":"display: none;"; ?>">
                                    <div class="alert bg-warning alert-styled-left alert-dismissible">
                                        Lütfen tüm alanları eksiksiz doldurun yoksa sistem mail gönderimi yapamayacaktır.
                                    </div>
                                    <?php
                                    echo $form->file("mail_tempate_logo",array(
                                        "label" => $admin_text["SETTINGS_MAIL_TEMPLATE_LOGO"],
                                        "file_key" => "project_image",
                                    ),$pageData);
                                    echo $form->input("smtp_host",array(
                                        "label" => $admin_text["SETTINGS_MAIL_HOST"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->input("smtp_email",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SMTP"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->input("smtp_password",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SIFRE"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->input("smtp_port",array(
                                        "label" => $admin_text["SETTINGS_MAIL_PORT"],
                                        "required" => 1,
                                        "class" => "numeric_field"
                                    ),$pageData);
                                    echo $form->select("smtp_secure",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SECURE"],
                                        "required" => 1,
                                        "select_item" => $smtpSecureType,
                                    ),$pageData);
                                    echo $form->input("smtp_send_name_surname",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SEND_NAME_SURNAME"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->input("smtp_send_email_adres",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SEND_EMAIL_ADRES"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->input("smtp_send_email_reply_adres",array(
                                        "label" => $admin_text["SETTINGS_MAIL_REPLY_EMAIL_ADRES"],
                                        "required" => 1,
                                    ),$pageData);
                                    echo $form->select("smtp_mail_send_debug",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SEND_DEBUG"],
                                        "required" => 1,
                                        "select_item" => $smtpSendMode,
                                    ),$pageData);
                                    echo $form->input("smtp_send_debug_adres",array(
                                        "label" => $admin_text["SETTINGS_MAIL_SEND_DEBUG_ADRESS"],
                                        "required" => 1,
                                        "item_hidden" => 1,
                                        "show_data" => $pageData[$default_lang->short_lang]["smtp_mail_send_debug"],
                                        "show_value" => 2,
                                    ),$pageData);
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="page-banner-image">
                                <?php
                                echo $form->file("banner_img",array(
                                    "label" => $admin_text["SETTINGS_PAGE_BANNER_IMG"],
                                    "file_key" => "project_image"
                                ),$pageData);
                                ?>
                            </div>
                            <div class="tab-pane fade" id="lang_content_prefix">
                                 <?php
                                 foreach ($systemLinkPrefix as $prefixKey => $systemLinkPrefix) {
                                     ?>
                                     <div class="alert alert-info" role="alert"><?php echo $systemLinkPrefix["title"]; ?></div>
                                     <?php
                                     foreach ($projectLanguages as $project_languages_row){
                                         echo $form->input($prefixKey.$project_languages_row->short_lang,array(
                                             "label" => $project_languages_row->lang,
                                             "required" => 1
                                         ),$pageData);
                                     }
                                 }
                                 ?>
                            </div>
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
        $("[name='mail_send_mode']").bootstrapSwitch();
    });

    $('#id_mail_send_mode').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state){
            $("#mail_mode_on").slideDown();
        }else{
            $("#mail_mode_on").slideUp();
        }
    });


    $("#id_smtp_mail_send_debug").change(function () {
        var this_val = $(this).val();
        if(this_val == 1){
            $("#div_smtp_send_debug_adres").slideUp();
        }else{
            $("#div_smtp_send_debug_adres").slideDown();
            $("#div_smtp_send_debug_adres input[type=text]").val("");
        }
    });
</script>
<?php require $adminSystem->adminView('static/footer'); ?>