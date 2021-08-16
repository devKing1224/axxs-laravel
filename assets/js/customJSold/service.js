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
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.inmateActive = '';

    $('#serviceCheckHeader').click(function () {
        var serviceCheckValue = $("#serviceCheckHeader").is(':checked') ? 1 : 0;
        if (serviceCheckValue == 1) {
            $('.serviceCheckEmail').prop('checked', true);
            $('.serviceCheck').prop('checked', true);
        } else {
            $('.serviceCheckEmail').prop('checked', false);
            $('.serviceCheck').prop('checked', false);
        }

        if ($('.serviceCheckEmail').is(':checked')) {
            ShowInmateEmailModal()
        }
    });

    if ($('.service').length) {
        var serviceTypeValue = $("#serviceType").val();
        if (serviceTypeValue == 0) {
            $('#charge').prop('readonly', true);
            $('#charge').val(0);
        } else {
            $('#charge').prop('readonly', false);
        }
    }

    $('#example122').on('click', '.serviceCheck', function () {
        $("#serviceCheckHeader").prop('checked', ($('.serviceCheck').length == $('.serviceCheck:checked').length));
    })

    $('#serviceType').on('change', function () {
        var serviceTypeValue = $("#serviceType").val();
        if (serviceTypeValue == 0) {
            $('#charge').prop('readonly', true);
            $('#charge').val(0);
        } else {
            $('#charge').prop('readonly', false);
            $('#charge').val('');
        }
    });

    $('#CategoryAddDataSend').click(function () {
        var form = $('#categoryData');
        var name = $('#first_name').val();
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
                    filesize: " File size must be less than 100 KB.",
                    extension: "Please upload .jpg or .png or .jpeg file extension.",
                    required: "Please upload image."
                }
            }
        });

        if (form.valid() === true) {
            var form = new FormData();
            form.append('name', name);
            form.append('icon_url', icon_url);
            $.ajax({
                type: 'post',
                url: apiURL + 'registercategory',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'category created successfuly';
                        window.location.href = baseURL + 'listcategory';
                        return false;
                    } else if (result.Code === 400) {
                        alert(result.Message);
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

    $('#serviceAddDataSend').click(function () {
        var form = $('#serviceData');
        var _token = $("input[name=_token]").val();
        var type = $("select[name=type]").val();
        var service_category_id = $("select[name=service_category_id]").val();
        var name = $("input[name=name]").val();
        var base_url = $("textarea[name=base_url]").val();
        var charge = $("input[name=charge]").val();
        var icon_urlnew = $('#logo_urlfile').prop('files')[0];

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
                    filesize: " File size must be less than 100 KB.",
                    extension: "Please upload .jpg or .png or .jpeg file extension.",
                    required: "Please upload image."
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
            form1.append('charge', charge);

            $.ajax({
                type: 'post',
                url: 'registerservice',
                data: form1,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Service created successfuly';
                        window.location.href = baseURL + 'servicelist';
                        return false;
                    } else if (result.Code === 400) {
                        alert(result.Message);
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

    $('#serviceEditDataSend').click(function () {
        var form = $('#serviceData');
        var _token = $("input[name=_token]").val();
        var type = $("select[name=type]").val();
        var service_category_id = $("select[name=service_category_id]").val();
        var name = $("input[name=name]").val();
        var base_url = $("textarea[name=base_url]").val();
        var logo_url = $("input[name=logo_url]").val();
        var charge = $("input[name=charge]").val();
        var service_id = $("input[name=service_id]").val();
        var icon_urlnew = $('#logo_urlfile').prop('files')[0];

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
                    filesize: 100000,
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
                    filesize: " File size must be less than 100 KB.",
                    extension: "Please upload .jpg or .png or .jpeg file extension."
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
            form1.append('name', name);
            form1.append('base_url', base_url);
            form1.append('charge', charge);

            $.ajax({
                type: 'post',
                url: apiURL + 'updateservice',
                data: form1,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result)
                {
                    if (result.Code === 200) {
                        sessionStorage.insert = 'Service updated successfuly';
                        window.location.href = baseURL + 'servicelist';
                        return false;
                    } else if (result.Code === 400) {
                        alert(result.Message);
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

    $('#CategoryEditDataSend').click(function () {
        var form = $('#categoryEditData');
        var name = $('#edit_first_name').val();
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
                    required: true,
                },
            },
            messages: {
                name: 'Please enter your Category name',
                icon_urlnew: {
                    filesize: " File size must be less than 100 KB.",
                    extension: "Please upload .jpg or .png or .jpeg file extension."
                }
            }
        });
        if (form.valid() === true) {
            var form = new FormData();
            form.append('name', name);
            form.append('icon_url', icon_url);
            form.append('id', id);
            $.ajax({
                type: 'post',
                url: apiURL + 'updatecategory',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.Code === 200) {
                        sessionStorage.insert = 'Category updated successfuly';
                        window.location.href = baseURL + 'listcategory';
                        return false;
                    } else if (result.Code === 400) {
                        swal({
                            title: result.Message,
                            type: "error",
                            showCancelButton: true,
                            showConfirmButton: false,
                            cancelButtonText: "OK",
                            closeOnCancel: false
                        });
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


    $('.CategoryEdit').on('click', function () {
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
        swal({
            title: "Are you sure?",
            text: "You want to delete Category entry.!",
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
                            url: apiURL + 'deletecategory/' + categoryID,
                            dataType: 'json',
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Category deleted successfully';
                                    window.location = baseURL + 'listcategory';
                                    return false;
                                } else if (result.Code === 400) {
                                    alert(result.Message);
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
        swal({
            title: "Are you sure?",
            text: "You want to delete service entry.!",
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
                            url: apiURL + 'deleteservice/' + serviceID,
                            dataType: 'json',
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Service deleted successfully';
                                    window.location = baseURL + 'servicelist';
                                    return false;
                                } else if (result.Code === 400) {
                                    alert(result.Message);
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
    $('.serviceActiveButton').click(function () {
        var serviceID = this.id;
        $.ajax({
            type: 'post',
            data: {service_id: serviceID},
            url: apiURL + 'activeservice',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.inmateActive = 'Service active successfully';
                    window.location.href = 'serviceinactivelist';
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
            text: "You want to delete Email entry.!",
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
                                    alert(result.Message);
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
    $('.serviceCheckEmail').click(function () {
        ShowInmateEmailModal();
    });

    $('#EmailModal').on('hidden.bs.modal', function () {
        $('.serviceCheckEmail').prop('checked', false);
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
                password: 'required',
            },
            messages: {
                email: 'Please enter correct email id.',
                password: 'Please enter password.'

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
                        sessionStorage.insert = 'Email details Added successfuly';
                        window.location.href = baseURL + 'inmateservicedetails/' + id;
                        return false;
                    } else if (result.Code === 400) {
                        alert(result.Message);
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


function ShowInmateEmailModal() {
    if ($('.serviceCheckEmail').is(':checked')) {
        $('#EmailModal').modal();
    }
}