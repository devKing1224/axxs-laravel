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
    }  else if(sessionStorage.facilityActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.facilityActive+"</div>");
    }  else if(sessionStorage.facilityForgetPassword) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.facilityForgetPassword+"</div>");
    }
    setTimeout( function(){ $('.msg').css('display','none');  $('#alertDiv').hide();}, 5000 );
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.facilityActive = '';
    sessionStorage.facilityForgetPassword = '';
    
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
                name: 'required',
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
                facility_id: {
                    required: true,
                    number: true,
                    maxlength: 11,
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15
                },
                
                twilio_number: {
                  
                    required: true,
                    maxlength: 15
                },
                username: {
                    required: true
                },
                password: 'required'
            },
            messages: {
                facility_id: 'Please enter your facility id',
                total_inmate: 'Please enter your total users',
                name: 'Please enter your name',
                username: 'Please enter your user name',
                password: 'Please enter your password',
                charge: 'Please enter your charge',
                phone: 'Please enter your phone',
                email: 'Please enter your email',
                zip: 'Please enter your zip',
                twilio_number: 'Please enter valid number',
            }
        });
        if (form.valid() === true) {
         if(telInput.intlTelInput("isValidNumber")){
            $.ajax({
                type: 'post',
                url: apiURL+'registerfacility',
                data: $('#facilityData').serialize(),
                dataType: 'json',
                success: function (result) { 
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Facility created successfuly'; 
                        window.location.href = baseURL+'facilitylist';
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
        else{
            swal('Error!!', 'Enter a valid Twilio number', 'error');
        }
        }
    });
    
    $('#facilityEditDataSend').on('click', function() { 
        
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
                name: 'required',
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
                facility_id: {
                    required: true,
                    number: true
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15
                },
                username: {
                    required: true
                },
                password: 'required'
            },
            messages: {
                facility_id: 'Please enter your facility id',
                total_inmate: 'Please enter your total inmate',
                name: 'Please enter your name',
                username: 'Please enter your user name',
                password: 'Please enter your password',
                charge: 'Please enter your charge',
                phone: 'Please enter your phone',
                email: 'Please enter your email',
                zip: 'Please enter your zip'
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL+'updatefacility',
                data: $('#facilityData').serialize(),
                dataType: 'json',
                success: function (result) { //console.log('success'+result);return false;
                    if (result.Code === 200) {
                        sessionStorage.update = 'Facility updated successfully';
                        window.location.href = baseURL+'facilitylist';
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
    $("body").on("click", ".facilityDelete", function(e){
    // $('.facilityDelete').click(function(){  
        facilityID = $(this).attr('id');
        swal({
                title: "Are you sure?",
                text: "You want to delete facility entry.!",
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
                        url: apiURL+'deletefacility/'+facilityID,
                        dataType: 'json',
                        success: function (result) { 
                            if (result.Code === 200) {
                               sessionStorage.delete = 'Facility deleted successfully';
                                window.location = baseURL+'facilitylist';
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
    $('.facilityActiveButton').click(function(){
        var facilityID = this.id;
        $.ajax({
            type: 'post',
            data: { facility_id : facilityID },
            url: apiURL+'activefacility',
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
            error: function (jqXHR, exception) { alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
                swal('Error!!', exception, 'error');
            }
        });
    });
    
    
    /* Function for facility forget password. */
    $('.facilityForgetPasswordButton').click(function(){
        //var facility_user_id = $('#facility_user_id').val();
        $.ajax({
            type: 'post',
            data: $('#facilityForgetPasswordData').serialize(),
            url: apiURL+'changefacilitypassword',
            dataType: 'json',
            success: function (result) { console.log(result.Message);//return false;
                if (result.Code == 200) {
                    sessionStorage.facilityForgetPassword = 'Password successfully updated';
                    window.location.href = 'facilityforgetpassword';
                    return false;
                } else { 
                    var message = '<ul>';
                    $(result.Message).each(function(index, value){
                        message += '<li>'+value+'</li>';
                    });
                    message += '</ul>';
                    if (result.Code == 400) {
                        swal({html:true, title:'Error!!', text:message, type:'error'});
                        return false;
                    } else if (result.Code == 401) {
                        swal({html:true, title:'Error!!', text:result.Message, type:'error'});
                        return false;
                    }
                }
            },
            error: function (jqXHR, exception) { //console.log(exception);return false; alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
                swal('Error!!', exception, 'error');
            }
        });
    });
});