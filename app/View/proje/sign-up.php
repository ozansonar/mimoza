
<div class="container-fluid text-color-mavi py-2">
    <div class="container py-4 blog-content default-border">
        <div class="col-12 p-0">
            <div class="card mb-3">
                <div class="row g-0">
                    <?php
                        if(!empty($loggedUser->img) && file_exists($constants::fileTypePath["user_image"]["full_path"].$loggedUser->img)){
                            $profile_image = $constants::fileTypePath["user_image"]["url"].$loggedUser->img;
                        }
                    ?>

                    <?php if(isset($loggedUser)): ?>
                        <?php if(isset($profile_image)): ?>
                            <div class="col-md-4">
                                <a href="<?php echo $profile_image ?>" data-fancybox="gallery">
                                    <img src="<?php echo $profile_image; ?>" class="img-fluid" alt="<?php echo $settings->keywords; ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-<?php echo isset($profile_image) ? 8:12; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $loggedUser->name." ".$loggedUser->surname; ?></h5>
                                <p class="card-text">
                                <p><i class="fas fa-envelope"></i> <?php echo $loggedUser->email; ?></p>
                                <?php if(!empty($loggedUser->telefon)):  ?>
                                    <p><i class="fas fa-phone"></i> <?php echo $loggedUser->telefon; ?></p>
                                <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="pageForm">
                <?php
                echo $functions->csrfToken();
                echo $data->form->input("email",array(
                    "label" => "Email",
                    "required" => 1,
                ),$data->pageData);
                echo $data->form->input("name",array(
                    "label" => "İsim",
                    "required" => 1,
                ),$data->pageData);
                echo $data->form->input("surname",array(
                    "label" => "Soyisim",
                    "required" => 1,
                ),$data->pageData);
                echo $data->form->input("telefon",array(
                    "label" => "Cep telefonu",
                    "label_description" => "<span class='badge bg-info'>Numaranızı başında '0' kullanarak yazınız.</span>",
                    "class" => "numeric",
                    "required" => 1,
                ),$data->pageData);
                //aguh şubesine ait olmayan değerler
                echo $data->form->file("img",array(
                    "label" => "Resim",
                ),$data->pageData);
                ?>
                <div class="alert alert-info">
                    Şifreniz en az bir küçük harf içermelidir.<br>
                    Şifreniz en az bir büyük harf içermelidir.<br>
                    Şifreniz en az 10 karakter olmalıdır.<br>
                </div>
                <?php
                echo $data->form->input("password",array(
                    "label" => "Şifre",
                    "type" => "password",
                ));
                ?>
                <?php
                echo $data->form->input("password_again",array(
                    "label" => "Şifre Tekrar",
                    "type" => "password",
                ));
                ?>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-success" value="1" name="submit" id="send-btn">KAYDET</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("form#pageForm").validationEngine({promptPosition : "bottomLeft", scroll: false});
    });

</script>
