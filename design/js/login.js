function login() {
    $.ajax({
        url: "user/login",
        type: "POST",
        data: "username=" + $('#username').val() + "&password=" + $('#password'),
        dataType: "json",
        success: function (data) {
            if (data === "Error") {
                $('form').append("<div id='error'></div>");
            } else {
                window.location.href += "main.html";
            }
        }
    });
}