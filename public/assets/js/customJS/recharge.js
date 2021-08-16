$(document).ready(function () {

    if (sessionStorage.update) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.update + "</div>");
    } else if (sessionStorage.insert) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.insert + "</div>");
    } else if (sessionStorage.delete) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.delete + "</div>");
    } else if (sessionStorage.deviceActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.deviceActive + "</div>");
    } else if (sessionStorage.deviceError) {
        $('#alertDiv').show();
        $('#erroralert').prepend("<div class='msg'>" + sessionStorage.deviceError + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.deviceActive = '';

    $('#RegisterFriend').click(function () {
        var form = $('#familyData');
        form.validate({
            errorElement: 'span',
            errorClass: 'form-error',
            highlight: function (element, errorClass, validClass) {

                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            rules: {
                first_name1: 'required',
                last_name1: 'required',
                email: {
                    required: true,
                    email: true
                },
                zip: {
                    number: true,
                    minlength: 5,
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                }

            },
            messages: {
                first_name1: 'Please enter your first name',
                last_name1: 'Please enter your last name',
                phone: 'Please enter your primary phone',
                email: 'Please enter valid email',
                zip: 'Please enter your zip',
            }
        });
 if (form.valid() === true) {
           
            var x_email = $("input[name='email']").val();
            var x_first_name = $("input[name='first_name1']").val();
            var x_cust_id = $("input[name='inmate_id']").val();
            var x_last_name = $("input[name='last_name1']").val();
            $("input[name='x_cust_id']").val(x_cust_id);
            $("input[name='x_email']").val(x_email);
            $("input[name='x_first_name']").val(x_first_name);
            $("input[name='x_last_name']").val(x_last_name);
            $("input[name='x_last_name']").val(x_last_name);
            $("input[name='x_description']").val('guest');
            $('#inmateAccountRecharge').modal();
        }
    });


    $('#VerifyInmate').click(function () {
        $('#cpc_msg').css('display','none');
        var form = $('#inmateData');
        form.validate({
            errorElement: 'span',
            errorClass: 'form-error',
            highlight: function (element, errorClass, validClass) {

                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            rules: {
                first_name: 'required',
                last_name: 'required',
                user_id: {
                    required: true,
                    number: true
                }
            },
            messages: {
                first_name: 'Please enter First name',
                last_name: 'Please enter last name',
                user_id: {
                    required: 'Please enter user id',
                    number: 'Please enter valid numeric user id'
                }
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL + 'verify_inmate_by_api',
                data: $('#inmateData').serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        $('#inmate_id').val(result.data);
                        startAnimation();
                        function startAnimation() {
                            $("#verifypage").animate({height: 0, width: 0}, "slow");
                            $("#registerpage").animate({height: 100 + '%', width: 100 + '%', }, "slow");
                        }
                        $("#verifypage").hide(300);
                        $("#registerpage").show(1000);
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    } else if (result.Code === 401) {
                        swal('Error!!', 'Invalid details', 'error');
                        return false;
                    }else if (result.Code === 403) {
                        $('#cpc_msg').css('display','block');
                        return false;
                    }

                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                }
            });
        }
    });

    $('#AmountSubmit').click(function () {
        var form = $('#addAmount');
        form.validate({
            errorElement: 'span',
            errorClass: 'form-error',
            highlight: function (element, errorClass, validClass) {

                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            rules: {
                amount: {
                    required: true,
                    number: true
                }
            },
            messages: {
                first_name: 'Please enter First name',
                last_name: 'Please enter last name',
                amount: {
                    required: 'Please enter Amount',
                    number: 'Please enter valid Amount.'
                }
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL + 'inmatepaymentscreenexternal',
                data: $('#addAmount').serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        $("input[name='x_login']").val(result.Data['login']);
                        $("input[name='x_amount']").val(result.Data['amount']);
                        $("input[name='x_fp_sequence']").val(result.Data['sequence']);
                        $("input[name='x_fp_timestamp']").val(result.Data['time']);
                        $("input[name='x_fp_hash']").val(result.Data['hash']);
                       
                        $("input[name='confirm_amount']").val(result.Data['amount']);


                        $('#inmateAccountRecharge').modal('hide');
                        startAnimation();
                        function startAnimation() {
                            $("#registerpage").animate({height: 0, width: 0}, "slow");
                            $("#redirectpage").animate({height: 100 + '%', width: 100 + '%', }, "slow");
                        }
                        $("#registerpage").hide(300);
                        $("#redirectpage").show(1000);
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    } else if (result.Code === 401) {
                        swal('Error!!', 'Invalid details', 'error');
                        return false;
                    }

                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                }
            });
        }
    });
});