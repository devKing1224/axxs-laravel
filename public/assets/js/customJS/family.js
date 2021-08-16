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
    } else if (sessionStorage.familyActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.familyActive + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.familyActive = '';

    $('#familyAddDataSend').click(function () {

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
                first_name: 'required',
                last_name: 'required',
                zip: {
                    number: true,
                    minlength: 5,
                    maxlength: 5,
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                 email: {
                    email: true,
                    required: true,
                   
                },
                username: {
                    required: true,
                },
                password: 'required',
            },
            messages: {
                first_name: 'Please enter your first name',
                last_name: 'Please enter your last name',
                username: 'Please enter your username',
                phone: 'Please enter your primary phone',
                email: 'Please enter your valid email id',
                password: 'Please enter your password',
                zip: 'Please enter your zip',
            }
        });
        if (form.valid() === true) {
            var inmate_id = $('#inmate_id').val();
            $.ajax({
                type: 'post',
                url: baseURL + 'registerfamily',
                data: $('#familyData').serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Family created successfully';
                        window.location.href = baseURL + 'families/' + inmate_id;
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
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

    $('#familyEditDataSend').on('click', function () {

        var form = $('#familyData');
        form.validate({
            errorElement: 'span',
            errorClass: 'form-error',
            highlight: function (element) {

                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            rules: {
                first_name: 'required',
                last_name: 'required',
                zip: {
                    number: true,
                    minlength: 5,
                    maxlength: 5,
                },
                 email: {
                    email: true,
                    required: true,
                   
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
            },
            messages: {
                first_name: 'Please enter your first name',
                last_name: 'Please enter your last name',
                phone: 'Please enter your primary phone',
                email: 'Please enter your valid email id',
                city: 'Please enter your city',
                state: 'Please enter your state',
                zip: 'Please enter your zip',
            }
        });
        if (form.valid() === true) {
            var inmate_id = $('#inmate_id').val();
            $.ajax({
                type: 'post',
                url: baseURL + 'updatefamily',
                data: $('#familyData').serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.update = 'Family updated successfully';
                        window.location.href = baseURL + 'families/' + inmate_id;

                        return false;
                    } else if (result.Code === 400) {
                         swal('Error!!', result.Message, 'error');
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

    /* Function for delete family */
    $("body").on("click", ".familyDelete", function (e) {
        var temp = $(this).attr('id');
        var token = $(this).attr('token');
        var familyID_inmateID = temp.split('+');
        swal({
            title: "Are you sure?",
            text: "You want to delete family member entry!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                       $.ajax({
                            type: 'DELETE',
                            url: baseURL + 'deletefamily/' + familyID_inmateID[0],
                            dataType: 'json',
                            data: {
                                "id": familyID_inmateID[0],
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.insert = 'Family deleted successfully';
                                    window.location = baseURL + 'families/' + familyID_inmateID[1];
                                    return false;
                                } else if (result.Code === 400) {
                                    swal('Error!!', result.Message, 'error');
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                            }
                        });
                    } else {
                        swal("Cancelled", "Your family member entry not deleted :)", "error");
                    }
                });
    });

    /* Function for family active and inactive data get in UI */
    $('#familyActiveInactiveCall').on('change', function () {
        var temp = this.value;
        var familyID_inmateID = temp.split('+');
        if (familyID_inmateID[0] == 1) {
            window.location.href = baseURL + 'families/' + familyID_inmateID[1];
        } else {
            window.location.href = baseURL + 'familiyinactivelist/' + familyID_inmateID[1];
        }
    });

    /* Function for inactive family update to active family. */
    $('.familyActiveButton').click(function () {
        var temp = this.id;
        var token = $(this).attr('token');
        var familyID_inmateID = temp.split('+');
        $.ajax({
            type: 'post',
            data: {family_id: familyID_inmateID[0], _token:token},
            url: baseURL + 'activefamily',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.familyActive = 'Family actived successfully';
                    window.location.href = baseURL + 'familiyinactivelist/' + familyID_inmateID[1];
                    return false;
                } else if (result.Code === 400) {
                     swal('Error!!', result.Message, 'error');
                    return false;
                }
            },
            error: function (jqXHR, exception) {
               
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });


    /* Function for check value before validation */
    $('#check').click(function () {
        var amountValue = $('#amount').val();
        var numberCheck = $.isNumeric(amountValue);
        if (amountValue == null) {
            swal('oops!', 'Please enter amount', 'error');
            return false;
        } else if (numberCheck == false) {
            swal('oops!', 'Please enter valid amount', 'error');
            return false;
        } else {
            return true;
        }
    });

});