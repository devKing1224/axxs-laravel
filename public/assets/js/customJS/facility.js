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
    } else if (sessionStorage.facilityActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.facilityActive + "</div>");
    } else if (sessionStorage.facilityForgetPassword) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.facilityForgetPassword + "</div>");
    }else if (sessionStorage.urlActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.urlActive + "</div>");

    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.facilityActive = '';
    sessionStorage.facilityForgetPassword = '';
     sessionStorage.urlActive = '';


    $('#facilityAddDataSend').click(function () {

        var form = $('#facilityData');
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
                total_inmate: {
                    required: true,
                    number: true
                },
                attachment_charge: {
                    required: true,
                    number: true
                },
                email: {
                    required: true,
                    email: true
                },
                zip: {
                    number: true,
                    minlength: 5,
                    maxlength: 5
                },
                facility_id: {
                    required: true,
                   
                    maxlength: 11,
                },
                facility_name: {
                    required: true,
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                twilio_number: {
                    required: false,
                    maxlength: 15
                },
                username: {
                    required: true
                },
                free_minutes: {
                    required: true
                },
                password: 'required',
                tablet_charge: 'required'
            },
            messages: {
                facility_id: 'Please enter facility id',
                facility_name: 'Please enter facility name',
                total_inmate: 'Please enter total users(numeric only)',
                first_name: 'Please enter facility first name',
                last_name: 'Please enter facility last name',
                username: 'Please enter username',
                password: 'Please enter password',
             
                phone: 'Please enter facility phone',
                email: 'Please enter your email',
                zip: 'Please enter zip',
                twilio_number: 'Please enter valid number',
                tablet_charge: 'Please enter tablate charge',
                free_minutes:  'Please enter free minutes',
                attachment_charge:  'Please enter attachment charges',
            }
        });
        if (form.valid() === true) {
            // if (telInput.intlTelInput("isValidNumber")) {
                $.ajax({
                    type: 'post',
                    url:  'registerfacility',
                    data: $('#facilityData').serialize(),
                    dataType: 'json',
                    success: function (result) {
                        if (result.Code === 201) {
                            sessionStorage.insert = 'Facility created successfully';
                            window.location.href = baseURL + 'facilities';
                            return false;
                        } else if (result.Code === 400) {
                            swal('Error!!', result.Message, 'error');
                            return false;
                        }
                    },
                    error: function (jqXHR, exception) {
                        console.log('jqXHR' + jqXHR);
                        console.log('exception' + exception);
                        swal('Error!!', exception, 'error');
                    }
                });
            // } else {
            //     swal('Error!!', 'Enter a valid Twilio number', 'error');
            // }
        }
    });

    $('#facilityEditDataSend').on('click', function () {

        var form = $('#facilityData');
        
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
                total_inmate: {
                    required: true,
                    number: true
                },
                email: {
                    required: true,
                    email: true
                },
                zip: {
                    number: true,
                    minlength: 5,
                    maxlength: 5
                },
                attachment_charge: {
                    required: true,
                    number: true
                },
                facility_id: {
                    required: true,
                 
                },
                facility_name: {
                    required: true,
                 
                },
                phone: {
                    minlength: 10,
                    maxlength: 15
                },
                username: {
                    required: true
                },
                free_minutes: {
                    required: true
                },
                password: 'required',
                tablet_charge: 'required'

            },
            messages: {
                facility_id: 'Please enter your facility id',
                facility_name: 'Please enter your facility name',
                total_inmate: 'Please enter your total inmate',
                first_name: 'Please enter your first name',
                last_name: 'Please enter your last name',
                username: 'Please enter your user name',
                password: 'Please enter your password',
                charge: 'Please enter your charge',
                phone: 'Please enter your phone',
                email: 'Please enter your email',
                zip: 'Please enter your zip',
                tablet_charge: 'Please enter tablate charge',
                free_minutes: 'Please enter free minutes',
                attachment_charge: 'Please enter attachment charges',
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updatefacility',
                data: $('#facilityData').serialize(),
                dataType: 'json',
                success: function (result) { 
                    if (result.Code === 200) {
                        sessionStorage.update = 'Facility updated successfully';
                        window.location.href = baseURL + 'facilities';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
        }
    });


    /* Function for delete service */
    $("body").on("click", ".facilityDelete", function (e) {
        facilityID = $(this).attr('id');
        var token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete facility entry!",
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
                            url: 'deletefacility/' + facilityID,
                            dataType: 'json',
                            data: {
                                "id": facilityID,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Facility deleted successfully';
                                    window.location = baseURL + 'facilities';
                                    return false;
                                } else if (result.Code === 400) {
                                    swal('Error!!', result.Message, 'error');
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                                swal('Error!!', exception, 'error');
                            }

                        });

                    } else {
                        swal("Cancelled", "Your facility is safe :)", "error");
                    }
                });
    });

    /* Function for inactive service update to active service. */
    // $('.facilityActiveButton').click(function () {
    $("body").on("click", ".facilityActiveButton", function (e) {
        var facilityID = this.id;
         var token = $(this).attr('token');
        $.ajax({
            type: 'post',
             data: {facility_id: facilityID, _token:token},
            url:  'activefacility',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.facilityActive = 'Facility active successfully';
                    window.location.href = 'facilityinactivelist';
                    return false;
                } else if (result.Code === 400) {
                    swal('Error!!', result.Message, 'error');
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
                swal('Error!!', exception, 'error');
            }
        });
    });


    /* Function for facility forget password. */
    $('.facilityForgetPasswordButton').click(function () {
        $.ajax({
            type: 'post',
            data: $('#facilityForgetPasswordData').serialize(),
            url: apiURL + 'changefacilitypassword',
            dataType: 'json',
            success: function (result) {
                console.log(result.Message);
                if (result.Code == 200) {
                    sessionStorage.facilityForgetPassword = 'Password successfully updated';
                    window.location.href = 'facilityforgetpassword';
                    return false;
                } else {
                    var message = '<ul>';
                    $(result.Message).each(function (index, value) {
                        message += '<li>' + value + '</li>';
                    });
                    message += '</ul>';
                    if (result.Code == 400) {
                        swal({html: true, title: 'Error!!', text: message, type: 'error'});
                        return false;
                    } else if (result.Code == 401) {
                        swal({html: true, title: 'Error!!', text: result.Message, type: 'error'});
                        return false;
                    }
                }
            },
            error: function (jqXHR, exception) { 
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
                swal('Error!!', exception, 'error');
            }
        });
    });
    
    $('.sendMaxLimit').click(function () {
        var form = $('#UserPic');
        var id = $('#facility_user_id').val();
        var _token = $("input[name=_token]").val();
        var user_icon = $('#user_icon').prop('files')[0];

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0}');
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
                user_icon: {
                    required: true,
                    extension: "jpg,jpeg,png",
                    filesize: 100000
                }
            },
            messages: {
                user_icon: {
                    filesize: " File size must be less than 100 KB",
                    extension: "Please upload .jpg or .png or .jpeg file extension",
                    required: "Please upload image"
                }
            }
        });
        if (form.valid() === true) {
            var form = new FormData();
            form.append('id', id);
            form.append('_token', _token);
            form.append('user_icon', user_icon);
            $.ajax({
                type: 'post',
                url: 'user_image',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.facilityForgetPassword = 'Image Successfully changed';
                        window.location.href = 'facilityforgetpassword';
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

    $("body").on("click", "#download_report", function (e) {
        var form = $('#Report_Fetch');

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
                report_type: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true
                }
            },
            messages: {
                report_type: 'Please select type of report',
                start_date: 'Please select start date of report',
                end_date: 'Please select end date of report'
            }
        });
         if (form.valid() === true) {
    
            $.ajax({
                type: 'post',
                url: 'monthly_report',
                data: $('#Report_Fetch').serialize(),
            //dataType: 'json',

                success: function (result) {
                    var a = document.createElement("a");
                    a.href = result.file;
                    a.download = result.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    $('#ReportModal').modal('hide');

                },
                error: function (jqXHR, exception, result) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
           
        }
    });

  $("body").on("click", ".facilitymonthlyReport", function (e) {
       var fid = $(this).attr('facility_id');
       $("input[name=facility_id]").val(fid);
       $('#ReportModal').modal();
  });
    $("body").on("change", "#report_type", function (e) {
        var value_selected = $(this).val();
        if (value_selected === 'vendor') {
            $('.vendor_name').prop('required',true);
            $('.vendor_name').val("ALL");
            $('.vendor_detail').show('3000');

        } else {
            $('.vendor_name').prop('required',false);
            $('.vendor_detail').hide('3000');
            $('.vendor_name').val('');
        }
    });

    $(".vendor_name").autocomplete({
        source: "search/autocomplete",
        minLength: 0,
        appendTo: "#browsers",
        select: function (event, ui) {
            $(".vendor_name").val(ui.item.value);
            $(".service_id").val(ui.item.id);

        }
    });

    $(".datepicker_start").datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        setDate: new Date(),
        maxDate: 0,
        onSelect: function (selected) {
            $(".datepicker_end").datepicker("option", "minDate", selected);
        }
    });

    $(".datepicker_end").datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        setDate: new Date(),
        maxDate: 0,
        onSelect: function (selected) {
            $(".datepicker_start").datepicker("option", "maxDate", selected);
        }
    });


  $('#blacklistedEditDataSend').on('click', function () {
        var form = $('#blacklistData');
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
                blacklisted_words: 'required',
    
            },
            messages: {
                blacklisted_words: 'Please enter Blacklisted Word'
                
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updateblacklistword',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.update = 'Blacklisted Word updated successfully';
                        window.location.href = baseURL + 'blacklist_word';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
        }
    });

   $('#blacklistedAddDataSend').click(function () { 

        var form = $('#blacklistData');
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
                blacklisted_words: 'required',
             },
            messages: {
                blacklisted_words: 'Please enter Blacklisted word',
               
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: 'blacklistcreate',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Blacklisted word created successfully';
                        window.location.href = baseURL + 'blacklist_word';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
           
        }
    });

 /* Function for delete service */
    $("body").on("click", ".blacklistedWordDelete", function (e) {
        ID = $(this).attr('id');
        token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete service entry!",
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
                            url:  'deleteblacklistedword/' + ID,
                            dataType: 'json',
                            data: {
                                "id": ID,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Blacklisted Word deleted successfully';
                                    window.location = baseURL + 'blacklist_word';
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
                        swal("Cancelled", "Your Word is safe :)", "error");
                    }
                });
    });



$('#AddEmailDataSend').click(function () {

        var form = $('#blacklistData');
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
                email_phone: 'required',
                 name: 'required',
             },
            messages: {
                email_phone: 'Please enter email id',
                name: 'Please enter name',
               
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: 'addemailid',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'email created successfully';
                        window.location.href = baseURL + 'emaillist';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
           
        }
    });



 /* Function for delete service */
    $("body").on("click", ".preEmailDelete", function (e) {
        ID = $(this).attr('id');
        token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete service entry!",
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
                            url:  'preapprovedemaildelete/' + ID,
                            dataType: 'json',
                            data: {
                                "id": ID,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Email deleted successfully';
                                    window.location = baseURL + 'emaillist';
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
                        swal("Cancelled", "Your Word is safe :)", "error");
                    }
                });
    });


 $('#preEmailEditDataSend').on('click', function () {
        var form = $('#blacklistData');
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
                email_phone: 'required',
                name: 'required',
    
            },
            messages: {
                email_phone: 'Please enter Email ID',
                name: 'Please enter name',
                
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updatepreemail',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.update = 'Email ID updated successfully';
                        window.location.href = baseURL + 'emaillist';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
        }
    });

$('#AddContactDataSend').click(function () {

        var form = $('#contactlistData');
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

                    contact_number: {
                        
                            number: true,
                            minlength: 10,
                            maxlength: 15,
                            required: true,

                        },
                    name:{
                        required: true,

                    },    

                },
            messages: {
                contact_number: 'Please enter contact number',
                name: 'Please enter name',

               
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: 'addcontact',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'contact created successfully';
                        window.location.href = baseURL + 'contactlist';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
           
        }
    });



 /* Function for delete service */
    $("body").on("click", ".preContactDelete", function (e) {
        ID = $(this).attr('id');
        token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete service entry!",
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
                            url:  'contactdelete/' + ID,
                            dataType: 'json',
                            data: {
                                "id": ID,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Contact deleted successfully';
                                    window.location = baseURL + 'contactlist';
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
                        swal("Cancelled", "Your Word is safe :)", "error");
                    }
                });
    });

$('#preContactEditDataSend').on('click', function () {
        var form = $('#contactlistData');
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
                contact_number: 'required',
                name: 'required',
    
            },
            messages: {
                contact_number: 'Please enter contact number',
                  name: 'Please enter name'
                
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updateprecontact',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.update = 'Contact updated successfully';
                        window.location.href = baseURL + 'contactlist';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
        }
    });

      /* Function for inactive pre approved contact update to active . */
      
    $("body").on("click", ".contactActiveButton", function (e) {
        var ID = this.id;
        token = $(this).attr('token');
        $.ajax({
            type: 'post',
            data: {id: ID, _token: token},
            url: baseURL + 'activecontact',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.emailActive = 'Contact activated successfully';
                    window.location.href = 'contactinactivelist';
                    return false;
                } else if (result.Code === 400) {
                    swal('Error!!', result.Message, 'error');
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

//Add M&S allow url

 $('#urlAddDataSend').click(function () {

        var form = $('#urlData');
        var url =    $('#url').val();
        var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
        if (!re.test(url)) { 
            alert("Please enter valid url");
             return false;
        }
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
                url: 'required',
             },
            messages: {
                url: 'Please enter url',
               
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: 'addallowurl',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Url added successfully';
                        window.location.href = baseURL + 'urllist';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
           
        }
    });


$('#urlEditDataSend').on('click', function () {
        var form = $('#urlData');
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
                url: 'required',
    
            },
            messages: {
                url: 'Please enter url'
                
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updateurl',
                data: form.serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.update = 'Url updated successfully';
                        window.location.href = baseURL + 'urllist';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
        }
    });

/* Function for delete service */
    $("body").on("click", ".urlDelete", function (e) {
        ID = $(this).attr('id');
        token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete service entry!",
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
                            url:  'deleteurl/' + ID,
                            dataType: 'json',
                            data: {
                                "id": ID,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Url deleted successfully';
                                    window.location = baseURL + 'urllist';
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
                        swal("Cancelled", "Your Word is safe :)", "error");
                    }
                });
    });


      /* Function for inactive pre approved email update to active . */
    $("body").on("click", ".urlActiveButton", function (e) {
        var ID = this.id;
        token = $(this).attr('token');
        $.ajax({
            type: 'post',
            data: {id: ID, _token: token},
            url: baseURL + 'activeurl',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.urlActive = 'Url activated successfully';
                    window.location.href = 'urllist';
                    return false;
                } else if (result.Code === 400) {
                    swal('Error!!', result.Message, 'error');
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

$('.toggle').on('click', function() {
    var deviceoff = $(this).closest(".toggle").find("input[name=devicetoggle]").attr('id');
    if (deviceoff != undefined) {
        if (deviceoff == 'toggle-two') {
                var tag = 'email';
                var emailcre_value = $('#email_create').val();
                if (emailcre_value == 0) {
                    var Status = 1;
                $('#email_create').val(1);
                } else {
                $('#email_create').val(0);
                var Status = 0;
                }
                var ID = $('#emailCreate_id').val();
        } else if(deviceoff == 'toggle-one'){
                var tag = 'device';
                var deviceoff_value = $('#device_off').val();
                if (deviceoff_value == 0) {
                    var Status = 1;
                $('#device_off').val(1);
                } else {
                $('#device_off').val(0);
                var Status = 0;
                }
                var ID = $('#deviceoff_id').val();
        } else if(deviceoff == 'toggle-three') {
            var tag = 'tabletcharge';
            var tbcharge_value = $('#tb_charge').val();
            if (tbcharge_value == 0) {
                var Status = 1;
            $('#tb_charge').val(1);
            } else {
            $('#tb_charge').val(0);
            var Status = 0;
            }
            var ID = $('#tb_charge_id').val();
        }
        $.ajax({
            type: 'post',
            data: {id: ID, _token: $('meta[name="_token"]').attr('content'), status: Status, tag:tag },
            url: baseURL + 'deviceoff_status',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    toastr.success(result.Status, 'Success');
                    //swal('Success!!', result.Status, 'success');
                    return false;
                } else if (result.Code === 400) {
                    toastr.error(result.Status);
                   /* swal('Error!!', result.Status, 'error');*/
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                toastr.error(result.Status);
                return false;
                alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
        return true;
    }          
    var cpc_funding= $('#cpc_funding').val();
    var cntct_approval= $('#cntct_approval').val();
    var device_status= $('#device_status').val();
    var create_email = $('#create_email').val();
    var tb_charge = $('#tb_charge').val();
    var i =$(this).next().attr('id');
    console.log(i);
    if(i == 'cpc_funding') {
    if (cpc_funding == 0) {
        $('#cpc_funding').val(1);
    }else{
        $('#cpc_funding').val(0);
    }
  } else if(i == 'cntct_approval'){
       if (cntct_approval == 0) {
               $('#cntct_approval').val(1);
           }else{
               $('#cntct_approval').val(0);
           }
  } else if(i == 'device_status') {
        if (device_status == 0) {
               $('#device_status').val(1);
           }else{
               $('#device_status').val(0);
           }
  } else if(i == 'create_email'){
          if (create_email == 0) {
               $('#create_email').val(1);
           }else{
               $('#create_email').val(0);
           }
  } else if(i == 'tb_charge'){
          if (tb_charge == 0) {
               $('#tb_charge').val(1);
           }else{
               $('#tb_charge').val(0);
           }

  } else {
    console.log('Something Went Wrong');
  }
});


});
$('#facility_name').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
});
