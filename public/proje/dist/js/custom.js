function sweetAlert(type,title,text,yonlendir=1,url="",btn_text="TAMAM") {
    Swal.fire({
        icon: type,
        title: title,
        text: text,
        confirmButtonText: btn_text,
    }).then((result) => {
        if (yonlendir == 1) {
            if (result.value) {
                setTimeout(
                    function()
                    {
                        window.location= url;
                    }, 500);
            }else{
                setTimeout(
                    function()
                    {
                        window.location= url;
                    }, 500);
            }
        }

    })
}
function AlertMessage(type, title, message = [], btn_text = "OK", yonlendir, url, time = false) {
    /*var tit;
    $.each(title, function( key, value ) {
        tit = value;
    });
    */
    var msg;
    msg = "<ul>";
    $.each(message, function (key, value) {
        msg += "<li>" + value + "</li>";
    });
    msg += "</ul>";
    if (btn_text == "") {
        btn_text = "TAMAM";
    }
    Swal.fire({
        icon: type,
        title: title,
        html: msg,
        timer: time,
        confirmButtonText: btn_text,
    }).then((result) => {
        if (yonlendir == 1) {
            if (result.value) {
                setTimeout(
                    function () {
                        window.location = url;
                    }, 500);
            } else {
                setTimeout(
                    function () {
                        window.location = url;
                    }, 500);
            }
        }

    })
}
$('.numeric').on('input', function (event) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
$('.text').on('input', function (event) {
    this.value = this.value.replace(/[^a-zA-ZşŞüÜöÖıİğĞçÇ ]/g, '');
});


$("#id_password").keyup(checkPasswords);
$("#id_password_again").keyup(checkPasswords);


$('.password-show-hide').click(function(e){
    var target = e.currentTarget
    $(target).hasClass('show')?hidePassword($(target)):showPassword($(target))
})
function hidePassword(e){
    e.removeClass('show').addClass('hide')
    e.html("<i class=\"fa-solid fa-eye\"></i>")
    e.prev('input').attr('type','password')
}
function showPassword(e){
    e.removeClass('hide').addClass('show')
    e.html("<i class=\"fa-solid fa-eye-slash\"></i>")
    e.prev('input').attr('type','text')
}

function checkPasswordMatch() {
    var password = $("#id_password").val();
    var confirmPassword = $("#id_password_again").val();

    if (password != '' && password != confirmPassword){
        $("#divCheckPasswordMatch").html("<div class=\"alert alert-danger mt-1\">Parolalar aynı olmalı</div>");
        return false;
        // $("#btnSubmit").attr("disabled", "disabled");
    }else{
        $("#divCheckPasswordMatch").html("");
        return true;
        // $("#btnSubmit").removeAttr('disabled');
    }
}

function checkPasswords(){
    if (checkPasswordPattern() &&  checkPasswordMatch()){
        $("#send-btn").removeAttr('disabled');
    }else{
        $("#send-btn").attr('disabled', 'disabled');
    }
}

function checkPasswordPattern() {
    //var regexLetter = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,12}$/; //büyük küçük harf
    var regexLetter = /^(?=.*\d)(?=.*[a-zA-Z]).{12,50}$/; //harf rakam
    goodpassword = regexLetter.exec($("#id_password").val());

    // var regexNumber = /\d/;
    // containsNumber = regexNumber.exec($("#password_1").val());

    if (!goodpassword){
        $('#divCheckPasswordPattern').html("<div class=\"alert alert-danger mt-1\">12-50 haneli olmalı, harf ve sayı içermeli</div>");
        return false;
    }else{
        $('#divCheckPasswordPattern').html("");
        return true;
    }
}