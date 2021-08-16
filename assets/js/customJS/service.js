//apiURL = 'http://localhost/axxs/public/index.php/api/';
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
    } else if (sessionStorage.inmateActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.inmateActive + "</div>");
    }
    else if (sessionStorage.emailActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.emailActive + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.inmateActive = '';
    sessionStorage.emailActive = '';

    $("body").on("click", "#serviceCheckHeader", function (e) {
        var serviceCheckValue = $("#serviceCheckHeader").is(':checked') ? 1 : 0;
        if (serviceCheckValue == 1) {
            $('.serviceCheckSMS').prop('checked', true);
            $('.serviceCheckEmail').prop('checked', true);
            $('.serviceCheck').prop('checked', true);
        } else {
            $('.serviceCheckSMS').prop('checked', false);
            $('.serviceCheckEmail').prop('checked', false);
            $('.serviceCheck').prop('checked', false);
        }
        if ($('.serviceCheckSMS').is(':checked')) {
            ShowInmateSMSModal();
        }

        if ($('.serviceCheckEmail').is(':checked')) {
            ShowInmateEmailModal();
        }
    });

    if ($('.service').length) {


        var serviceTypeValue = $("#serviceType").val();

        if (serviceTypeValue == 0) {
            $('#charge').prop('readonly', true);
            $('#charge').val(0);
        } else if(serviceTypeValue == 1) {
            $('#charge').prop('readonly', true);
        } else if(serviceTypeValue == 2){
                $('#chargelabel').html('Charge/Min ($.$$)'+'<i class="requiredInput text-red">*</i>')
        }else {
            $('#charge').prop('readonly', false);
        }
    }

    $('#example122').on('click', '.serviceCheck', function () {
        $("#serviceCheckHeader").prop('checked', ($('.serviceCheck').length == $('.serviceCheck:checked').length));
    })


    $('#serviceType').on('change', function () {
        var serviceTypeValue = $("#serviceType").val();
        if (serviceTypeValue == 0 || serviceTypeValue == 1) {
            $('#charge').prop('readonly', true);
        } else if(serviceTypeValue == 2){
                $('#charge').prop('readonly', false);
                $('#charge').val('0.00');
        } else {
            $('#charge').prop('readonly', false);
            $('#charge').val('0.00');
        }
    });


 $("body").on("click", "#defaultServiceHeader", function (e) {

        var serviceCheckValue = $("#defaultServiceHeader").is(':checked') ? 1 : 0;
        if (serviceCheckValue == 1) {
            $('.serviceCheckSMS').prop('checked', true);
            $('.serviceCheckEmail').prop('checked', true);
            $('.defaultserviceCheck').prop('checked', true);
        } else {
            $('.serviceCheckSMS').prop('checked', false);
            $('.serviceCheckEmail').prop('checked', false);
            $('.defaultserviceCheck').prop('checked', false);
        }
        if ($('.serviceCheckSMS').is(':checked')) {
            ShowInmateSMSModal();
        }

        if ($('.serviceCheckEmail').is(':checked')) {
            ShowInmateEmailModal();
        }

        $("input.serviceCheck").each(function(){

                    var propSet = $("#defaultServiceHeader").prop("checked");
                    if(!propSet) {

                        $(this).closest("tr")
                        .find("input.defaultserviceCheck")
                        .prop("checked",false);
                } else {
                    $(this).closest("tr")
                    .find("input.defaultserviceCheck")
                    .prop("checked",$(this).prop("checked"));
                }
                    
                });
    });


 $('#example122').on('click', '.defaultserviceCheck', function () {
        $("#defaultServiceHeader").prop('checked', ($('.defaultserviceCheck').length == $('.defaultserviceCheck:checked').length));
    })







    $("body").on("click", "#CategoryAddDataSend", function (e) {
        // $('#CategoryAddDataSend').click(function () {
        var form = $('#categoryData');
        var name = $('#first_name').val();
        var _token = $("input[name=_token]").val();
        var icon_url = $('#url').prop('files')[0];


        $.validator.addMethod('filedimension', function (value, element, param) {
            return this.optional(element) || (element.files[0].width <= param) && (element.files[0].height <= param)
        }, 'File dimension must be less than {0}');

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
                name: 'required',
                url: {
                    required: true,
                    extension: "jpg,jpeg,png",
                    filesize: 100000
                }
            },
            messages: {
                name: 'Please enter your category name',
                url: {
                    filesize: " File size must be less than 100 KB",
                    extension: "Please upload .jpg or .png or .jpeg file extension",
                    required: "Please upload image"
                }
            }
        });

        if (form.valid() === true) {
            var form = new FormData();
            form.append('name', name);
            form.append('_token', _token);
            form.append('icon_url', icon_url);
            $.ajax({
                type: 'post',
                url: baseURL + 'registercategory',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'category created successfully';
                        window.location.href = baseURL + 'categories';
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

    $("#base_urlfile").change(function () {
        baseurlfile =$('#base_urlfile').prop('files')[0].name;
        $("textarea[name=base_url]").val('https://theaxxstablet.com/pdf/'+baseurlfile);
    });

    $("body").on("click", "#serviceAddDataSend", function (e) {
        // $('#serviceAddDataSend').click(function () {
        var form = $('#serviceData');
        var _token = $("input[name=_token]").val();
        var type = $("select[name=type]").val();
        var service_category_id = $("select[name=service_category_id]").val();
        var name = $("input[name=name]").val();
        var base_url = $("textarea[name=base_url]").val();
        var base_urlfile = $('#base_urlfile').prop('files')[0];
        var charge = $("input[name=charge]").val();
        var flat_rate = $("input[id=flat-rate]").prop("checked");
        flat_rate ? flat_rate = 1 : flat_rate = 0;
        var flat_rate_charge = $("input[name=flat-rate-charge]").val();
        var icon_urlnew = $('#logo_urlfile').prop('files')[0];
        var auto_logout = $("input[name=auto_logout]").val();
        var msg = $("textarea#popup_msg").val();
        var keyboardEnabled = $("input[id=keyboardEnabled]").prop("checked");
        keyboardEnabled ? keyboardEnabled = 1 : keyboardEnabled = 0;

        $.validator.addMethod('filedimension', function (value, element, param) {
            return this.optional(element) || (element.files[0].width <= param) && (element.files[0].height <= param)
        }, 'File dimension must be less than {0}');

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0}');

        if (icon_urlnew) {
            logo_url = icon_urlnew;
        } else {
            logo_url = '';
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
                name: 'required',
                base_url: 'required',
                logo_urlfile: {
                    required: true,
                    extension: "jpg,jpeg,png",
                    filesize: 100000,
                },
                base_urlfile: {
                    extension: "pdf,docx",
                    filesize: 10000000,
                },
                charge: {
                    number: true,
                    required: true,
                },
            },
            messages: {
                name: 'Please enter your service name',
                base_url: 'Please enter your base url',
                charge: 'Please enter your charge',
                logo_urlfile: {
                    filesize: " File size must be less than 100 KB",
                    extension: "Please upload .jpg or .png or .jpeg file extension",
                    required: "Please upload image"
                },
                base_urlfile: {
                    filesize: " File size must be less than 10 MB",
                    extension: "Please upload .pdf or .docx file extension",
                }
            }
        });

        if (form.valid() === true) {
            var form1 = new FormData();
            form1.append('logo_url', logo_url);
            form1.append('_token', _token);
            form1.append('type', type);
            form1.append('service_category_id', service_category_id);
            form1.append('name', name);
            form1.append('base_url', base_url);
            form1.append('base_urlfile', base_urlfile);
            form1.append('charge', charge);
            form1.append('flat_rate', flat_rate);
            form1.append('flat_rate_charge', flat_rate_charge);
            form1.append('auto_logout', auto_logout);
            form1.append('msg', msg);
            form1.append('keyboardEnabled', keyboardEnabled);
            $.ajax({
                type: 'post',
                url: 'registerservice',
                data: form1,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Service created successfully';
                        window.location.href = baseURL + 'services';
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


    $("body").on("click", "#serviceEditDataSend", function (e) {

        // $('#serviceEditDataSend').click(function () {
        var form = $('#serviceData');
        var _token = $("input[name=_token]").val();
        var type = $("select[name=type]").val();
        var service_category_id = $("select[name=service_category_id]").val();
        var name = $("input[name=name]").val();
        var base_url = $("textarea[name=base_url]").val();
        var logo_url = $("input[name=logo_url]").val();
        var charge = $("input[name=charge]").val();
        var flat_rate = $("#flat-rate").prop("checked");
        flat_rate ? flat_rate = 1 : flat_rate = 0;
        var flat_rate_charge = $("input[name=flat-rate-charge]").val();
        var service_id = $("input[name=service_id]").val();
        var icon_urlnew = $('#logo_urlfile').prop('files')[0];
        var base_urlfile = $('#base_urlfile').prop('files')[0];
        var auto_logout =$("input[name=auto_logout]").val();
        var msg = $("textarea#popup_msg").val();
        var keyboardEnabled = $("#keyboardEnabled").prop("checked");
        keyboardEnabled ? keyboardEnabled = 1 : keyboardEnabled = 0;
        var facility_id = $('#dropdown_selector').val();
        $.validator.addMethod('filedimension', function (value, element, param) {
            return this.optional(element) || (element.files[0].width <= param) && (element.files[0].height <= param)
        }, 'File dimension must be less than {0}');

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0}');

        if (icon_urlnew) {

            logo_url = icon_urlnew;

        } else {
            logo_url = logo_url;
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
                name: 'required',
                base_url: 'required',
                logo_urlfile: {
                    extension: "jpg,jpeg,png",
                    filesize: 100000
                },
                base_urlfile: {
                    extension: "pdf,docx",
                    filesize: 10000000
                },
                charge: {
                    number: true,
                    required: true
                }
            },
            messages: {
                name: 'Please enter your service name',
                base_url: 'Please enter your base url',
                logo_urlfile: {
                    filesize: " File size must be less than 100 KB",
                    extension: "Please upload .jpg or .png or .jpeg file extension"
                },
                base_urlfile: {
                    filesize: " File size must be less than 10 MB",
                    extension: "Please upload .pdf or .docx file extension"
                },
                charge: 'Please enter your charge'
            }
        });
        if (form.valid() === true) {
            var form1 = new FormData();
            form1.append('service_id', service_id);
            form1.append('logo_url', logo_url);
            form1.append('_token', _token);
            form1.append('type', type);
            form1.append('service_category_id', service_category_id);
            form1.append('base_urlfile', base_urlfile);
            form1.append('name', name);
            form1.append('base_url', base_url);
            form1.append('charge', charge);
            form1.append('flat_rate', flat_rate);
            form1.append('flat_rate_charge', flat_rate_charge);
            form1.append('auto_logout', auto_logout);
            form1.append('msg', msg);
            form1.append('keyboardEnabled', keyboardEnabled);
            form1.append('facility_id',facility_id);


            $.ajax({
                type: 'post',
                url: baseURL + 'updateservice',
                data: form1,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result)
                {
                    if (result.Code === 200) {
                        sessionStorage.insert = 'Service updated successfully';
                        window.location.href = baseURL + 'services';
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

    $("body").on("click", "#CategoryEditDataSend", function (e) {
        // $('#CategoryEditDataSend').click(function () {
        var form = $('#categoryEditData');
        var name = $('#edit_first_name').val();
        var _token = $("input[name=_token]").val();
        var id = $("#edit_id").val();
        var icon_urlold = $("#edit_url").val();
        var icon_urlnew = $('#edit_urlnew').prop('files')[0];

        $.validator.addMethod('filedimension', function (value, element, param) {
            return this.optional(element) || (element.files[0].width <= param) && (element.files[0].height <= param)
        }, 'File dimension must be less than {0}');

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0}');

        if (icon_urlnew) {
            var icon_url = icon_urlnew;
        } else {
            icon_url = icon_urlold;
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
                name: 'required',
                icon_urlnew: {
                    extension: "jpg,jpeg,png",
                    filesize: 100000
                },
                icon_url: {
                    required: true
                }
            },
            messages: {
                name: 'Please enter your Category name',
                icon_urlnew: {
                    filesize: " File size must be less than 100 KB",
                    extension: "Please upload .jpg or .png or .jpeg file extension"
                }
            }
        });
        if (form.valid() === true) {
            var form = new FormData();
            form.append('name', name);
            form.append('_token', _token);
            form.append('icon_url', icon_url);
            form.append('id', id);
            $.ajax({
                type: 'post',
                url: baseURL + 'updatecategory',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.insert = 'Category updated successfully';
                        window.location.href = baseURL + 'categories';
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

    $("body").on("click", ".CategoryEdit", function (e) {
        // $('.CategoryEdit').on('click', function () {
        var catid = $(this).attr("catid");
        var catname = $(this).attr("name");
        var caturl = $(this).attr("caturl");
        $('.addimageurl').attr('src', caturl);
        $("#edit_first_name").val(catname);
        $("#edit_url").val(caturl);
        $("#edit_id").val(catid);
        $('#exampleModal1').modal();

    });
    /* Function for delete Category service */
    $("body").on("click", ".CategoryDelete", function (e) {
        categoryID = $(this).attr('id');
        token = $(this).attr('token');
        swal({
            title: "Are you sure?",
            text: "You want to delete Category entry!",
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
                            type: 'get',
                            url: baseURL + 'deletecategory/' + categoryID,
                            dataType: 'json',
                            data: {
                                "id": categoryID,
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Category deleted successfully';
                                    window.location = baseURL + 'categories';
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
                        swal("Cancelled", "Your Category is safe :)", "error");
                    }
                });
    });

    /* Function for delete service */
    $("body").on("click", ".serviceDelete", function (e) {
        serviceID = $(this).attr('id');
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
                            type: 'get',
                            url: baseURL + 'deleteservice/' + serviceID,
                            dataType: 'json',
                            data: {
                                "id": serviceID,
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Service deleted successfully';
                                    window.location = baseURL + 'services';
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
                        swal("Cancelled", "Your service is safe :)", "error");
                    }
                });
    });


    /* Function for inactive service update to active service. */
    $("body").on("click", ".serviceActiveButton", function (e) {
        // $('.serviceActiveButton').click(function () {
        var serviceID = this.id;
        token = $(this).attr('token');
        $.ajax({
            type: 'post',
            data: {service_id: serviceID, _token: token},
            url: baseURL + 'activeservice',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.inmateActive = 'Service activated successfully';
                    window.location.href = 'serviceinactivelist';
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

    /* Function for view and delete Email  */

    $('.viewemail').on('click', function () {
        var email = $(this).attr("textemail");

        $("#fullbodyemail").val(email);

        $('#exampleModal').modal();

    });
    /* Function for delete Email service */
    $('.emaildelete').click(function () {
        emailID = $(this).attr('id');
        swal({
            title: "Are you sure?",
            text: "You want to delete Email entry!",
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
                            type: 'get',
                            url: apiURL + 'deleteemail/' + emailID,
                            dataType: 'json',
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Email deleted successfully';
                                    window.location = baseURL + 'viewallemails';
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
                        swal("Cancelled", "Your email is safe :)", "error");
                    }
                });
    });




    /* Function for delete Email service */
    $("body").on("click", ".serviceCheckEmail", function (e) {
        // $('.serviceCheckEmail').click(function () {
        ShowInmateEmailModal();
    });

    $('.serviceCheckSMS').click(function () {
        ShowInmateSMSModal();
    });

    $('#EmailModal').on('hidden.bs.modal', function () {
        $('.serviceCheckEmail').prop('checked', false);
    });
    $('#SMSModal').on('hidden.bs.modal', function () {
        $('.serviceCheckSMS').prop('checked', false);
    });

    $('#InmateEmailDetails').click(function () {
        var form = $('#InmateEmailData');

        var id = $("#inmateid").val();
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
                email: {
                    required: true,
                    email: true
                },
                password: 'required'
            },
            messages: {
                email: 'Please enter correct email id',
                password: 'Please enter password'

            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL + 'CreateEmail',
                data: $('#InmateEmailData').serialize(),
                dataType: 'json',
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.insert = 'Email details Added successfully';
                        window.location.href = baseURL + 'userservicedetails/' + id;
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

    /* Function for showing services on category list page */
    $("body").on("click", ".ServicesOnCat", function (e) {
        id = $(this).attr('id');
        $.ajax({
            type: 'get',
            url: 'get_cat_services/' + id,
            success: function (result) {
                if (result.Code === 200) {
                    createServiceTable(result.Message);
                    $('#ServiceModal').modal();
                    return false;
                } else if (result.Code === 400) {
                    alert(result.Message);
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

    /* Function for moving service upward and downward */
    $("body").on("click", ".ServicesMove", function (e) {
        id = $(this).attr('id');
        key = $(this).attr('key');
        $.ajax({
            type: 'get',
            url: 'service_' + key + '/' + id,
            success: function (result) {
                if (result.Code === 200) {
                    createServiceTable(result.Message);
                    $('#ServiceModal').modal();
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


      /* Function for inactive pre approved email update to active . */
    $("body").on("click", ".emailActiveButton", function (e) {
        var ID = this.id;
        token = $(this).attr('token');
        $.ajax({
            type: 'post',
            data: {id: ID, _token: token},
            url: baseURL + 'activeemail',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.emailActive = 'Email activated successfully';
                    window.location.href = 'emailinactivelist';
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



});


function ShowInmateEmailModal() {
    if ($('.serviceCheckEmail').is(':checked')) {
        $('#EmailModal').modal();
    }
}

function ShowInmateSMSModal() {
    if ($('.serviceCheckSMS').is(':checked')) {
        $('#SMSModal').modal();
    }
}


function createServiceTable(data) {
    var j = 0;
    $('.tooltip').remove();
    if (data.length > 0) {
        var strTable = '<table id="example" class="table table-bordered table-striped">';
        strTable += "<thead><tr><th>S.No </th><th>Service Name </th><th> Type </th> <th>Action</th></thead>";
        for (var i = 0, len = data.length; i < len; i++) {
            var id = data[i].id;
            strTable += '<tr>';
            strTable += '<td>' + ++j + '</td>';
            strTable += '<td>' + data[i].name + '</td>';
            strTable += '<td>' + data[i].type + '</td>';
            strTable += '<td>';
            if (j !== 1) {
                strTable += '<a href="javascript:;" class="ServicesMove" key="up" id=' + id + ' data-toggle="tooltip" title="Move UP" ><i class="fa fa-arrow-up"></i>&nbsp;&nbsp;&nbsp;</a>';
            }
            if (j !== data.length) {
                strTable += '<a href="javascript:;" class="ServicesMove" key="down" id=' + id + ' data-toggle="tooltip" title="Move Down" ><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;&nbsp;</a>';
            }
            strTable += '</td>';
            strTable += '</tr>';
        }
        strTable += "</table>";
    } else {
        var strTable = '<h4>No Services found under this category.</h4>';
    }
    $('#servicebody').html(strTable);


}
$(function(){
    $('.toggle').on('click', function() {
           var faci_id = $("#dropdown_selector").val();
           if( !faci_id) {
               var auto_logout= $('#auto_logout').val();
          
           if (auto_logout == 0) {
               $('#auto_logout').val(1);
           }else{
               $('#auto_logout').val(0);
           }
           }
           
       });
});
/*function deleteService($service_id){
    alert($service_id);
      $.ajax({
                     type:'POST',
                     url:'/deleteservice',
                     data:'_token = <?php echo csrf_token() ?>',,
                     success:function(data) {
                        $("#msg").html(data.msg);
                     }
                  });
    };*/