/* global apiURL, baseURL */

$(document).ready(function () {
    $("#successMessage").delay(2000).slideUp(300);


    if (sessionStorage.inmateConfiguration) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.inmateConfiguration + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.inmateConfiguration = '';
    
        var el = $('.intonly');
    
    el.prop("autocomplete", false); // remove autocomplete (optional)
    el.on('keydown', function (e) {
        var allowedKeyCodesArr = [9,48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 8,46];  // allowed keys
        if ($.inArray(e.keyCode, allowedKeyCodesArr) === -1 && (e.keyCode != 17 && e.keyCode != 86)) {  // if event key is not in array and its not Ctrl+V (paste) return false;
            e.preventDefault();
        } else if ($.trim($(this).val()).indexOf('.') > -1 && $.inArray(e.keyCode, [110, 190]) != -1) {  // if float decimal exists and key is not backspace return fasle;
            e.preventDefault();
        } else {
            return true;
        }
        
    }).on('paste', function (e) {  // on paste
        var pastedTxt = e.originalEvent.clipboardData.getData('Text').replace(/[^0-9.]/g, '');  // get event text and filter out letter characters
        if ($.isNumeric(pastedTxt)) {  // if filtered value is numeric
            e.originalEvent.target.value = pastedTxt;
            e.preventDefault();
        } else {  // else 
            e.originalEvent.target.value = ""; // replace input with blank (optional)
            e.preventDefault();  // retur false
        }
        
    });

    /* Function for inactive service update to active service. */
    $("body").on("click", ".sendConfigureData", function (e) {
//    $('.sendConfigureData').click(function(){

        var tempIDValue = $(this).attr('id');
        $.ajax({
            type: 'post',
            data: $('#' + tempIDValue + 'form').serialize(),
            url: apiURL + 'registerconfiguration',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    /* Function for inactive service update to active service. */
    $('.sendFreeTabletConfigureData').click(function () {
        $.ajax({
            type: 'post',
            data: $('#freeTabletConfigform').serialize(),
            url: apiURL + 'registertabletfreetimetabletchargeconfiguration',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

/*Negative Balance Update Function*/
    $('#NegativeChargesub').click(function () {
        $.ajax({
            type: 'post',
            data: $('#NegativeChargeForm').serialize(),
            url: apiURL + 'updatenegativebalance',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    /*Logout Time update function*/
    $('#logoutTimesub').click(function () {
        $.ajax({
            type: 'post',
            data: $('#logoutTimeform').serialize(),
            url: apiURL + 'updatelgtime',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    /*Welcome Message Update Function*/
    $('#Welcomemsgsub').click(function () {
        var data = $('#WelcomeForm').serialize();
        $.ajax({
            type: 'post',
            data: $('#WelcomeForm').serialize(),
            url: apiURL + 'updatewelcomemsg',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    /*Update POP up Message(Low Balance & free minutes)*/
    $('#low_bl_msg').click(function () {
        var data = $('#LowBLMsg').serialize();
        
        $.ajax({
            type: 'post',
            data: $('#LowBLMsg').serialize(),
            url: apiURL + 'updatelowblmsg',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    /*Update API URL*/
    $('#api_url').click(function () {
        var data = $('#APIURL').serialize();
        if ($("#qa_api_url").val() == '' || $("#pro_api_url").val() == '') {
            return false;
        }
        $.ajax({
            type: 'post',
            data: $('#APIURL').serialize(),
            url: apiURL + 'updateapiurl',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    /*Update POP up Message(Low Balance & free minutes)*/
    $('#free_min_exp').click(function () {
        var data = $('#FreeMinMsg').serialize();
        
        $.ajax({
            type: 'post',
            data: $('#FreeMinMsg').serialize(),
            url: apiURL + 'updatefreeminexpmsg',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'configuration';
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

    $('.sendMaxLimit').click(function () {

        $.ajax({
            type: 'post',
            data: $('#maxContact').serialize(),
            url: apiURL + 'setmaxlimit',
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL + 'facilitydashboard';
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

    $("body").on("click", ".CommonDelete", function (e) {
//    $('.sendConfigureData').click(function(){
        deletename = $(this).attr('deletename');
        id = $(this).attr('id');
        token = $(this).attr('token');
        url= deletename+'s/' + id;
        swal({
            title: "Are you sure?",
            text: "You want to delete " + deletename + " entry!",
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
                            url: url,
                            dataType: 'json',
                            data: {
                                "id": id,
                                "_method": 'DELETE',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.inmateConfiguration = result.Status;
                                    window.location = baseURL + deletename+'s';
                                    return false;
                                } else if (result.Code === 400) {
                                    sessionStorage.inmateConfiguration = result.Status;
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                            }
                        });
                    } else {
                        swal("Cancelled", "Your " +deletename +" is safe :)", "error");
                    }
                });
    });

    $("body").on("click", ".CommonActivate", function (e) {
//    $('.sendConfigureData').click(function(){
        deletename = $(this).attr('deletename');
        id = $(this).attr('id');
        token = $(this).attr('token');
        url= 'activateuser/' + id;
        swal({
            title: "Are you sure?",
            text: "You want to activate " + deletename + " entry!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "Yes, activate it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: 'PUT',
                            url: url,
                            dataType: 'json',
                            data: {
                                "id": id,
                                "_method": 'PUT',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.inmateConfiguration = result.Status;
                                    window.location = baseURL + 'inusers';
                                    return false;
                                } else if (result.Code === 400) {
                                    sessionStorage.inmateConfiguration = result.Status;
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                            }
                        });
                    } else {
                        swal("Cancelled", " ", "error");
                    }
                });
    });
    
     $("body").on("click", ".CommonAction", function (e) {
//    $('.sendConfigureData').click(function(){
        activename = $(this).attr('activename');
        id = $(this).attr('id');
        token = $(this).attr('token');
        url = 'action_staff/' + id;
        swal({
            title: "Are you sure?",
            text: "You want to " + activename + " staff entry!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, " + activename + " it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            dataType: 'json',
                            data: {
                                "id": id,
                                "_method": 'POST',
                                "_token": token
                            },
                            success: function (result) {
                                if (result.Code === 200) {
                                    sessionStorage.inmateConfiguration = result.Status;
                                    window.location = baseURL + 'staffs';
                                    return false;
                                } else if (result.Code === 400) {
                                    sessionStorage.inmateConfiguration = result.Status;
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                            }
                        });
                    } else {
                        swal("Cancelled", "No action has been taken :)", "error");
                    }
                });
    });

     $('.toggle').on('click', function() {
        var msg_status = $('#welcomemsg_status').val();
        if (msg_status == 1) {
            $('#welcomemsg_status').val(0);
        }else{
            $('#welcomemsg_status').val(1);
        }


     });

     /*Fuction for making devic on / off*/
    $('.toggle').on('click', function() {
        var deviceoff = $(this).closest(".toggle").find("input[name=devicetoggle]").attr('id');
        if (deviceoff != undefined) {
            var Status = $('#device_status').val();
        var ID = $('#facility_id').val();
        if (Status == 1) {
            $('#device_status').val(0);
            Status = 0;
        }else{
            $('#device_status').val(1);
            Status = 1;
        }
        $.ajax({
            type: 'post',
            data: {id: ID, _token: $('meta[name="_token"]').attr('content'), status: Status },
            url: baseURL + 'change_deviceStatus',
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
        


     });


});