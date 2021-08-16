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
    }  else if(sessionStorage.inmateActive) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.inmateActive+"</div>");
    } else if(sessionStorage.inmateError) {
        $('#alertDiv').show();
        $('#erroralert').prepend("<div class='msg'>"+sessionStorage.inmateError+"</div>");
    }
    setTimeout(function(){ $('.msg').css('display','none');  $('#alertDiv').hide();}, 5000);
    sessionStorage.insert = '';
    sessionStorage.update = '';
    sessionStorage.delete = '';
    sessionStorage.inmateActive = '';
    
    $('#inmateAddDataSend').click(function () { 
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
                /*state: {
                    required: true
                },*/
                zip: {
                    number: true,
                    //required: true,
                    minlength: 5,
                    maxlength: 5,
                },/*
                address_line_1: 'required',
                address_line_2: 'required',
                city: 'required',*/
                phone: {
                    //required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                username: {
                    required: true,
                    //email: true
                },
            },
            messages: {
                inmate_id: 'Please enter your user id',
                // facility_id : 'Please enter your facility id',
                first_name: 'Please enter your first name',
                last_name: 'Please enter your last name',
                //phone: 'Please enter your primary phone',
                username: 'Please enter your user name',
                //address_line_1: 'Please enter your first address',
                //address_line_2: 'Please enter your second address',
                //city: 'Please enter your city',
                //state: 'Please enter your state',
               // zip: 'Please enter your zip',
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL+'registerinmate',
                data: $('#inmateData').serialize(),
                dataType: 'json',
                beforeSend : function(){
                    $('#floatingBarsG').show();
                    $('.sendInmateData').attr('disabled', true);
                },
                success: function (result) { console.log(result);
                $('#floatingBarsG').hide(); 
                $('.sendInmateData').attr('disabled',false);
                    if (result.Code === 201) {
                    //alert('User created successfully');
                    sessionStorage.insert = 'User created successfully';
                    window.location.href = baseURL+'inmatelist';
                    return false;
                    } else if (result.Code === 400) {
                        alert(result.Message);
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    $('#floatingBarsG').hide(); 
                    $('.sendInmateData').attr('disabled',false);
                    sessionStorage.insertError = 'Something is problem in during registration';
                    //window.location.href = baseURL+'addinmate';
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                }
            });
        }
    });
    
    $('#inmateEditDataSend').on('click', function() { 
        
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
                /*state: {
                    required: true
                },*/
                zip: {
                    //digits: true,
                    number: true,
                    //required: true,
                    minlength: 5,
                    maxlength: 5,
                },
                //address_line_1: 'required',
                //address_line_2: 'required',
                //city: 'required',
                phone: {
                    //required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                username: {
                    required: true,
                   // email: true
                },
            },
            messages: {
                inmate_id: 'Please enter your user id',
                // facility_id : 'Please enter your facility id',
                first_name: 'Please enter your first name',
                last_name: 'Please enter your last name',
                phone: 'Please enter your primary phone',
                username: 'Please enter your user name',
                address_line_1: 'Please enter your first address',
                address_line_2: 'Please enter your second address',
                city: 'Please enter your city',
                state: 'Please enter your state',
                zip: 'Please enter your zip',
            }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: apiURL+'updateinmate',
                data: $('#inmateData').serialize(),
                dataType: 'json',
                success: function (result) { 
                    if (result.Code === 200) {
                        sessionStorage.update = 'User updated successfully';
                        window.location.href = baseURL+'inmatelist';
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
    $("body").on("click", ".inmateID", function(e){
//    $('.inmateID').on('click', function(e){
        inmateID = $(this).attr('id');
        swal({
                title: "Are you sure?",
                text: "You want to delete user entry.!",
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
                    url: apiURL+'deleteinmate/'+inmateID,
                    dataType: 'json',
                    success: function (result) { 
                        if (result.Code === 200) {
                            sessionStorage.delete = 'User deleted successfully';
                            window.location = baseURL+'inmatelist';
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
                swal("Cancelled", "Your user is safe :)", "error");
            }
        });
    });    
    
    
    /* Function for inmate email details show in view */
       $("body").on("click", ".emailView", function(e){
//   $('.emailView').click(function(){
        var inmateEmailID = this.value;
       var inmateEmailType = $(this).attr('etype');
       
        $.ajax({
            type: 'post',
            data: {inmateEmailID:inmateEmailID, inmateEmailType:inmateEmailType},
            url: apiURL+'getinmateemail',
            dataType: 'json',
            success: function (result) { //console.log('success'+result);return false;
                if (result.Code === 200) {
                    $('#body').html(result.Data[0].body);
                    $('#imageUploadModal').modal();
                    return false;
                } else if (result.Code === 400) {
                    alert(result.Message);
                    return false;
                }
            },
            error: function (jqXHR, exception) {alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
    
    $('.Maxlimitview').click(function(){
            var maxtype = $(this).attr("maxtype");
             var inmate_ID = $(this).attr("value");
            $('#user_id').val(inmate_ID);
            if(maxtype == 'phone'){
                $('#max_email').prop( "type", 'hidden' );
                $('.max_email').hide();
            }
            if(maxtype == 'email'){
                $('#max_phone').prop( "type", 'hidden' );
                $('.max_phone').hide();
            }
             if(maxtype == 'both'){
                 $('#max_email').prop( "type", 'text' );
                $('.max_email').show();
                $('#max_phone').prop( "type", 'text' );
                $('.max_phone').show();
            }
    
        $.ajax({
            type: 'post',
            data: {inmate_ID:inmate_ID},
            url: apiURL+'getmaxlimitval',
            dataType: 'json',
            success: function (result) { //console.log('success'+result);return false;
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
            error: function (jqXHR, exception) {alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
    
       /* Function for saving inmate limit for adding contact details */
    $('.setmaxlimitbtn').click(function(){
        
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
            data:$('#InmateSetMaxForm').serialize(),
            url: apiURL+'setmaxlimit',
            dataType: 'json',
             success: function (result) { console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                    window.location.href = baseURL+'inmatelist';
                    return false;
                } else if (result.Code === 400) {
                    alert(result.Message);
                    return false;
                }
            },
            error: function (jqXHR, exception) {alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
    /* Function for inmate report to reste password */
    $('.reportResetPassword').click(function(){
        var inmateReportID = this.id;//alert(inmateEmailID);return false;
        console.log('hii');
        $('.reportResetPassword').off('click');
        $.ajax({
            type: 'post',
            data: {report_id:inmateReportID},
            url: apiURL+'resetinmatepassword',
            dataType: 'json',
            success: function (result) { 
               if (result.Code === 200) {
                    sessionStorage.delete = 'User password reset successfully please check facility admin register email';
                    window.location = baseURL+'getloginreportinmatelist';
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
        $('.reportResetPassword').on('click');
    });
    
    /* Function for inmate active and inactive data get in UI */
    $('#InmateActiveInactiveCall').on('change', function(){
        var id = this.value;
        var URL = '';
        if(id == 1){
           window.location.href = 'inmatelist';
        } else {
            window.location.href = 'inmateinactivelist';
        }
    });
    
    /* Function for inactive inmate update to active inmate. */
    $('.inmateActiveButton').click(function(){
        var inmateID = this.id;
        $.ajax({
            type: 'post',
            data: { inmate_id : inmateID },
            url: apiURL+'activeinmate',
            dataType: 'json',
            success: function (result) { 
                if (result.Code === 200) {
                    sessionStorage.inmateActive = 'User activated successfully';
                    window.location.href = 'inmateinactivelist';
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