$(document).ready(function () {
    if (sessionStorage.passwordChange) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.passwordChange + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.passwordChange = '';

    /* Function for inactive family update to active family. */
    $('.resetPasseordButton').click(function () {
        var username = $('#username').val();
        $.ajax({
            type: 'post',
            data: {username: username},
            url: apiURL + 'resetinmatepassword',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    var passwordChange = 'Please check your registered email and login with your temp password';
                    swal({
                        title: "Reset Password",
                        text: passwordChange,
                        type: "success"
                    }, function () {
                        window.location.href = baseURL + 'login';
                    });
                    return false;
                } else if (result.Code === 400) {
                    swal("Error!", result.Data, "error");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
});