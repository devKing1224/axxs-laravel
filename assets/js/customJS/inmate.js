$(document).ready(function () {
    $('#gen_email').click(function(){
    var data = {};
    data.inmate_id =  $('#in_id').val();
    data.user_id = $('#user_id').val();
    data.admin_id = $('#admin_id1').val();
    $.ajax({
        type: 'post',
        url: baseURL + 'generateemail',
        data: {data, "_token": $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            if (data.status == 'success') {
                location.reload();
                return false;
            } else if (data.status == 'error') {
                swal("Email Not Created", 'Something Went Wrong', "warning");
                return false;
            }
        },
        error: function (jqXHR, exception) {
            console.log('jqXHR' + jqXHR);
            console.log('exception' + exception);
        }
    });

    });
    if (sessionStorage.update) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.update + "</div>");
    } else if (sessionStorage.insert) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.insert + "</div>");
    } else if (sessionStorage.delete) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.delete + "</div>");
    } else if (sessionStorage.inmateActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.inmateActive + "</div>");
    } else if (sessionStorage.inmateError) {
        $('#alertDiv').show();
        $('#erroralert').prepend("<div class='msg'>" + sessionStorage.inmateError + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.inmateActive = '';

    $('#inmateAddDataSend').click(function () {
        var deviceid = $('#device_id').val();
        if(deviceid == 'There are no device list'){
            swal("Please add a device for assigning it to this user.","warning");
             return false;
        }
       
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
                inmate_id: {
                    number: true,
                    required: true,
                },
                first_name: 'required',
                last_name: 'required',
                date_of_birth: 'required',
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
                username: {
                    required: true,
                },
                
            },
            messages: {
                date_of_birth: 'Please enter valid date',
                inmate_id: 'Please enter user id',
                first_name: 'Please enter first name',
                last_name: 'Please enter last name',
                phone: 'Please enter primary phone',
                username: 'Please enter Username/PIN',
                address_line_1: 'Please enter first address',
                address_line_2: 'Please enter second address',
                city: 'Please enter city name',
                state: 'Please enter state name',
                zip: 'Please enter zip code'
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: 'registeruser',
                data: $('#inmateData').serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#floatingBarsG').show();
                    $('.sendInmateData').attr('disabled', true);
                },
                success: function (result) {
                    console.log(result);
                    $('#floatingBarsG').hide();
                    $('.sendInmateData').attr('disabled', false);
                    if (result.Code === 201) {
                        sessionStorage.insert = 'User created successfully';
                        window.location.href = baseURL + 'allusers';
                        return false;
                    } else if (result.Code === 400) {
                         swal("User Not Created", result.Message, "warning");
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    $('#floatingBarsG').hide();
                    $('.sendInmateData').attr('disabled', false);
                    sessionStorage.insertError = 'Some problem occured during registration';
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                }
            });
        }
    });

    $('#inmateEditDataSend').on('click', function () {
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
                inmate_id: {
                    number: true,
                    required: true
                },
                first_name: 'required',
                last_name: 'required',
                date_of_birth: 'required',
                zip: {
                    number: true,
                    //required: true,
                    minlength: 5,
                    maxlength: 5
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15
                },
                username: {
                    required: true
                }
            },
            messages: {
                date_of_birth: 'Please enter valid date',
                inmate_id: 'Please enter user id',
                first_name: 'Please enter first name',
                last_name: 'Please enter last name',
                phone: 'Please enter primary phone',
                username: 'Please enter Username/PIN',
                address_line_1: 'Please enter first address',
                address_line_2: 'Please enter second address',
                city: 'Please enter city name',
                state: 'Please enter state name',
                zip: 'Please enter zip code'
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updateuser',
                data: $('#inmateData').serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.update = 'User updated successfully';
                        window.location.href = baseURL + 'allusers';
                        return false;
                    } else if (result.Code === 400) {
                        swal("User Not Updated", result.Message, "warning");
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


    /* Function for delete inmate */
    $("body").on("click", ".inmateID", function (e) {
        var base_url = window.location.origin;
        inmateID = $(this).attr('id');
        token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete user entry!",
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
                            type: 'POST',
                            url: base_url + '/deleteuser/' + inmateID,
                            dataType: 'json',
                             data: {
                                "id": inmateID,
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'User deleted successfully';
                                    window.location = baseURL + 'allusers';
                                    return false;
                                } else if (result.Code === 400) {
                                   swal("User Not Deleted", result.Message, "warning");
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                            }
                        });
                    } else {
                        swal("Cancelled", "Your user is safe :)", "error");
                    }
                });
    });


    /* Function for inmate email details show in view */
    $("body").on("click", ".emailView", function (e) {
        var inmateEmailID = this.value;
        var inmateEmailType = $(this).attr('etype');

        $.ajax({
            type: 'post',
            data: {inmateEmailID: inmateEmailID, inmateEmailType: inmateEmailType},
            url: apiURL + 'getuseremail',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    $('#body').html(result.Data[0].body);
                    $('#imageUploadModal').modal();
                    return false;
                } else if (result.Code === 400) {
                   swal("Error", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });

    $('.Maxlimitview').click(function () {
        var maxtype = $(this).attr("maxtype");
        var inmate_ID = $(this).attr("value");
         var token = $(this).attr('token');
        $('#user_id').val(inmate_ID);
        if (maxtype == 'phone') {
            $('#max_email').prop("type", 'hidden');
            $('.max_email').hide();
        }
        if (maxtype == 'email') {
            $('#max_phone').prop("type", 'hidden');
            $('.max_phone').hide();
        }
        if (maxtype == 'both') {
            $('#max_email').prop("type", 'text');
            $('.max_email').show();
            $('#max_phone').prop("type", 'text');
            $('.max_phone').show();
        }

        $.ajax({
            type: 'post',
            data: {"inmate_ID": inmate_ID, "_token": token},
            url:  'getmaxlimitval',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    $('#max_email').val(result.Data[0].max_email);
                    $('#max_phone').val(result.Data[0].max_phone);
                    return false;
                } else if (result.Code === 400) {
                    $('#max_email').val('0');
                    $('#max_phone').val('0');
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });

    /* Function for saving inmate limit for adding contact details */
    $('.setmaxlimitbtn').click(function () {

        var form = $('#InmateSetMaxForm');
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
                max_phone: 'number',
                max_email: 'number',
            },
            messages: {
                max_phone: 'Please enter valid number',
                max_email: 'Please enter valid number',
            }
        });

        $.ajax({
            type: 'post',
            data: $('#InmateSetMaxForm').serialize(),
            url: baseURL + 'setmaxlimit',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'allusers';
                    return false;
                } else if (result.Code === 400) {
                    swal("Limit not saved", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
    /* Function for inmate report to reste password */
    $("body").on("click", ".reportResetPassword", function (e) {
    // $('.reportResetPassword').click(function () {
        var inmateReportID = this.id;
        
        $('.reportResetPassword').off('click');
        $.ajax({
            type: 'post',
            data: {report_id: inmateReportID},
            url: apiURL + 'resetinmatepassword',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.delete = 'User password reset successfully';
                    window.location = baseURL + 'getloginreportuserlist';
                    return false;
                } else if (result.Code === 400) {
                    swal("User password not updated", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
        $('.reportResetPassword').on('click');
    });

    /* Function for inmate active and inactive data get in UI */
    $('#InmateActiveInactiveCall').on('change', function () {
        var id = this.value;
        var URL = '';
        if (id == 1) {
            window.location.href = 'allusers';
        } else {
            window.location.href = 'userinactivelist';
        }
    });

    /* Function for inactive inmate update to active inmate. */
    $('.inmateActiveButton').click(function () {
        var inmateID = this.id;
         token = $(this).attr('token');
        $.ajax({
            type: 'post',
           data: {inmate_id: inmateID, _token:token},
            url:  'activeuser',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.inmateActive = 'User activated successfully';
                    window.location.href = 'userinactivelist';
                    return false;
                } else if (result.Code === 400) {
                    swal("User Not inactivated", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });




});
/*$('.Changeview').click(function(){
        var inmate_id =$(this).attr('inmate_id');
        token = $(this).attr('token');
        
        $.ajax({
            type: 'post',
            data: {inmate_id: inmate_id, _token:token},

            url:  'changviewstatus',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    return false;
                } else if (result.Code === 400) {
                    
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });

    });*/
    
if ($('.viewnotify').length > 0) {
    var url = window.location.pathname;
    var APP_URL = window.location.origin;
    var token = $('meta[name="csrf-token"]').attr('content');
    var inmate_id = url.substring(url.lastIndexOf('/') + 1);
    $.ajax({
            type: 'post',
            data: {inmate_id: inmate_id, _token:token},

            url:  APP_URL+'/changviewstatus',
            dataType: 'html',
            success: function (result) {
                if (result.Code === 200) {
                    return false;
                } else if (result.Code === 400) {
                    
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
      
}

$('#admin_id').on('change', function (e) {
    var facilityUser_id = $(this).children("option:selected").val();
    var token = $('meta[name="csrf-token"]').attr('content');
    var APP_URL = window.location.origin;
    $('#device_id').find('option').remove().end().append('<option value="" selected>All Devices</option>')
    $.ajax({
            type: 'post',
            data: {facilityUser_id: facilityUser_id, _token:token},
            url:  APP_URL+'/getdevicelist',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    //console.log(result.Data);
                    var data = result.Data;
                    if (data.length < 1) {
                        $('#device_id').append($("<option disabled></option>")
                                            .attr("value",'')
                                            .text('There are no device list'));
                        return false;
                    }
                    $.each(data , function(key,value){
                        $('#device_id').append($("<option ></option>")
                                            .attr("value",value.id)
                                            .text(value.device_name));
                    })
                } else if (result.Code === 400) {
                    
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    
});