<?php require $system->adminView('static/header'); ?>

    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="javascript:void(0)" class="h1">Giriş</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg"><?php echo $settings->project_name ?></p>
                <form action="" method="post" id="pageForm">
					<?php
					echo $functions->csrfToken();
					echo $page_form->input("name", array(
						"label" => "Kullanıcı Adı",
						"input_group" => 1,
						"group_icon" => "fas fa-envelope",
						"required" => 1
					),$pageData);
					echo $page_form->input("password", array(
						"type" => "password",
						"label" => "Parola",
						"input_group" => 1,
						"group_icon" => "fas fa-lock",
						"required" => 1
					));
					?>
                    <div class="row">
                        <div class="col-4 offset-8">
							<?php echo $page_form->button("submit", array(
								"text" => "Giriş",
								"class" => "btn-block",
								"icon" => "fas fa-save"
							)) ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $("form#PageForm").validationEngine({promptPosition: "bottomLeft", scroll: false});
        });
    </script>
<?php require $system->adminView('static/footer'); ?>