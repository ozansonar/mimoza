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