<div class="container-fluid text-color-mavi">
    <div class="container  my-4 font-roboto-bold d-flex align-items-center justify-content-center flex-column default-border py-2">
        <div class="col-12 text-center">
            <h2><?php echo $metaTag->title; ?></h2>
        </div>
        <div class="col-md-6 col-12 ">
            <form action="" method="post" id="resetPassword">
                <?php echo $functions->csrfToken(); ?>
                <div class="input-group mt-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                    <input type="password" class="form-control validate[required,minSize[10],maxSize[50]]" id="id_password" name="password"  placeholder="Şifre" onkeyup="checkPasswordStrength();">
                </div>
                <div id="password-strength-status"></div>
                <div class="input-group mt-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                    <input type="password" class="form-control validate[required,equals[id_password],minSize[10],maxSize[50]]]" id="id_password_again" name="password_again" placeholder="Şifre Tekrarı" onkeyup="checkPasswordStrength2();">
                </div>
                <div id="password-again-strength-status"></div>
                <div class="d-grid gap-2">
                    <input type="hidden" name="hash" value="<?php echo $_GET["hash"]; ?>" id="hash">
                    <button type="submit" name="save" class="btn btn-success my-2" value="1" id="send-btn">Şifremi Yenile</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("form#resetPassword").validationEngine();
    });

    var in1 = false;
    var in2 = false;
    function checkPasswordStrength() {
        var number = /([0-9])/;
        var alphabets = /([a-z])/;
        var alphabets2 = /([A-Z])/;
        //var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        $("#send-btn").prop("disabled",true);
        if($('#id_password').val().length<10) {
            $('#password-strength-status').removeClass();
            $('#password-strength-status').addClass('my-1 alert alert-danger');
            $('#password-strength-status').html("Zayıf (en az 10 karakter olmalıdır.)");
            in1=false;
        }else if($('#id_password_again').val().length > 0){
            $('#password-strength-status').addClass('my-1 alert alert-danger');
            $('#password-strength-status').html("Şifre ve tekrarı aynı olmalıdır.");
            in2=false;
        } else {
            if($('#id_password').val().match(number) && $('#id_password').val().match(alphabets) && $('#id_password').val().match(alphabets2) /*&& $('#id_password').val().match(special_characters)*/) {
                $('#password-strength-status').removeClass();
                $('#password-strength-status').addClass('my-1 alert alert-success');
                $('#password-strength-status').html("Güçlü");
                in1=true;
                passwordCheck();
            } else {
                $('#password-strength-status').removeClass();
                $('#password-strength-status').addClass('my-1 alert alert-danger');
                $('#password-strength-status').html("Orta (büyük/küçük harfler ve sayılar içermelidir.)");
                in1=false;
            }
        }
    }
    function checkPasswordStrength2() {
        var number = /([0-9])/;
        var alphabets = /([a-z])/;
        var alphabets2 = /([A-Z])/;
        //var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        $("#send-btn").prop("disabled",true);
        if($('#id_password_again').val().length<10) {
            $('#password-again-strength-status').removeClass();
            $('#password-again-strength-status').addClass('my-1 alert alert-danger');
            $('#password-again-strength-status').html("Zayıf (en az 10 karakter olmalıdır.)");
            in2=false;
        } else {
            if($('#id_password_again').val() != $('#id_password').val()){
                $('#password-again-strength-status').addClass('my-1 alert alert-danger');
                $('#password-again-strength-status').html("Şifre ve tekrarı aynı olmalıdır.");
                in2=false;
            }else if($('#id_password_again').val().match(number) && $('#id_password_again').val().match(alphabets) && $('#id_password_again').val().match(alphabets2) /*&& $('#id_password_again').val().match(special_characters)*/) {
                $('#password-again-strength-status').removeClass();
                $('#password-again-strength-status').addClass('my-1 alert alert-success');
                $('#password-again-strength-status').html("Güçlü");
                in2=true;
                passwordCheck();
            } else {
                $('#password-again-strength-status').removeClass();
                $('#password-again-strength-status').addClass('my-1 alert alert-danger');
                $('#password-again-strength-status').html("Orta (büyük/küçük harfler ve sayılar içermelidir.)");
                in2=false;
            }
        }
    }

    function passwordCheck(){
        console.log($('#id_password_again').val().length);
        console.log($('#id_password').val().length);
        if($('#id_password_again').val().length > 0 && $('#id_password').val().length > 0 && $('#id_password_again').val() != $('#id_password').val()){
            $(".alert-success").hide();
            $("#send-btn").prop("disabled",true);
        }
        if(in1 && in2 && $('#id_password_again').val() == $('#id_password').val()){
            $(".alert-danger").hide();
            $("#send-btn").prop("disabled",false);
        }
    }
</script>

