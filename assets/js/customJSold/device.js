$(document).ready(function () {
    
    if(sessionStorage.update) {  
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.update+"</div>");
    } else if(sessionStorage.insert) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.insert+"</div>");
    } else if(sessionStorage.delete) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.delete+"</div>");
    }  else if(sessionStorage.deviceActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.deviceActive+"</div>");
    } else if(sessionStorage.deviceError) {
        $('#alertDiv').show();
        $('#erroralert').prepend("<div class='msg'>"+sessionStorage.deviceError+"</div>");
    }
    setTimeout(function(){ $('.msg').css('display','none');  $('#alertDiv').hide();}, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.deviceActive = '';
    
    $('#deviceAddDataSend').click(function () { 
        var form = $('#deviceData');
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
                facility_id: 'required',
                device_provider: 'required',
                device_id: {
                    required: true,
                    number: true,
                    maxlength: 11,
                },
                imei: {
                    required: true,
                    maxlength: 50,
                },
                facility_id: {
                    required: {
                        depends: function(element) {
                            return $("#facility_id").val() == '';
                        }
                    }
                }
            
            },
            messages: {
                imei: 'Please enter imei number',
                facility_id: 'Please enter your facility name',
                device_provider: 'Please enter device provider name',
                device_id: 'Please enter device id',
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL+'registerdevice',
                data: $('#deviceData').serialize(),
                dataType: 'json',
                success: function (result) { //console.log('success'+result);return false;
                    if (result.Code === 201) {
                        //alert('Device created successfully');
                        sessionStorage.insert = 'Device created successfully';
                        window.location =  baseURL+'devicelist';
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
    
    $('#deviceEditDataSend').on('click', function() { 
        
        var form = $('#deviceData');
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
                imei: 'required',
                device_provider: 'required',
                device_id: 'required',
                facility_id: {
                    required: {
                        depends: function(element) {
                            return $("#facility_id").val() == '';
                        }
                    }
                }
            },
            messages: {
                imei: 'Please enter imei number',
                facility_id: 'Please enter your facility name',
                device_provider: 'Please enter device provider name',
                device_id: 'Please enter device id',
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL+'updatedevice',
                data: $('#deviceData').serialize(),
                dataType: 'json',
                success: function (result) { 
                    if (result.Code === 200) {
                        //alert('Device updated successfully');
                        sessionStorage.update = 'Device updated successfully';
                        window.location = baseURL+'devicelist';
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
    
    /* Function for delete inmate */
    $("body").on("click", ".deviceID", function(e){
    
        
        deviceID = $(this).attr('id');
        swal({
                title: "Are you sure?",
                text: "You want to delete device entry.!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {   
                $.ajax({
                    type: 'get',
                    url: apiURL+'deletedevice/'+deviceID,
                    dataType: 'json',
                    success: function (result) { //console.log('success'+result);return false;
                        if (result.Code === 200) {
                            //alert('Device delete successfully');
                            sessionStorage.delete = 'Device deleted successfully';
                            window.location = baseURL+'devicelist';
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
                swal("Cancelled", "Your device is safe :)", "error");
            }
        });
    });
    
    /* Function for inactive device update to active device. */
    $('.deviceActiveButton').click(function(){
        var deviceID = this.id; //alert(deviceID);return false;
        $.ajax({
            type: 'post',
            data: { device_id : deviceID },
            url: apiURL+'activedevice',
            dataType: 'json',
            success: function (result) { 
                if (result.Code === 200) {
                    sessionStorage.deviceActive = 'Device activated successfully';
                    window.location.href = 'deviceinactivelist';
                    return false;
                } else if (result.Code === 400) {
                    alert(result.Message);
                    return false;
                }
            },
            error: function (jqXHR, exception) { alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });

});