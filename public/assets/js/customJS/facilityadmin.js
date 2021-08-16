$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
if (sessionStorage.updatefa) {
    $('#alertDiv').show();
    $('#alert').prepend("<div class='msg'>" + sessionStorage.updatefa + "</div>");
} else if (sessionStorage.insertfa) {
    $('#alertDiv').show();
    $('#alert').prepend("<div class='msg'>" + sessionStorage.insertfa + "</div>");
} else if (sessionStorage.deletefa) {
    $('#alertDiv').show();
    $('#alert').prepend("<div class='msg'>" + sessionStorage.deletefa + "</div>");
}
setTimeout(function () {
    $('.msg').css('display', 'none');
    $('#alertDiv').hide();
}, 5000);
sessionStorage.insertfa = '';
sessionStorage.updatefa = '';
sessionStorage.deletefa = '';
sessionStorage.facilityActive = '';
sessionStorage.facilityForgetPassword = '';
 sessionStorage.urlActive = '';
$('#facilityAdminAddDataSend').click(function () {

        var form = $('#fAData');
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
                total_facility: {
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
                fa_id: {
                    required: true,
                   
                    maxlength: 11,
                },
                fa_name: {
                    required: true,
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                username: {
                    required: true
                },
                password: 'required'
                
            },
            messages: {
                fa_id: 'Please enter facility admin id',
                fa_name: 'Please enter organisation name',
                total_facility: 'Please enter total facility(numeric only)',
                first_name: 'Please enter facility admin first name',
                last_name: 'Please enter facility admin last name',
                username: 'Please enter username',
                password: 'Please enter password',
             
                phone: 'Please enter facility phone',
                email: 'Please enter your email',
                zip: 'Please enter zip',
            }
        });
        if (form.valid() === true) {
            // if (telInput.intlTelInput("isValidNumber")) {
                $.ajax({
                    type: 'post',
                    url:  'registerfadmin',
                    data: $('#fAData').serialize(),
                    dataType: 'json',
                    success: function (result) {
                        if (result.Code === 201) {
                            sessionStorage.insert = 'Facility created successfully';
                            window.location.href = baseURL + 'fadmins';
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

//Assign Facility Function

function assignFacility($fadmin_id){
    $('#facility_select')
    .find('option')
    .remove()
    $.ajax({
                type: "GET",
                url: '/getfadmindata/'+$fadmin_id,
                success: function( data ) {
                    console.log(data.fa_name);
                    $("#fa_id").val(data.facilityadmin.id);
                    $("#fa_name").val(data.facilityadmin.fa_name);
                    $.each(data.facility_list, function(key, value) {
                        if (value.facility_admin == $fadmin_id) {
                            $('#facility_select').append($('<option selected value="'+ value.id +'">', { value : value.id }).text(value.name));
                        } else{
                            $('#facility_select').append($('<option value="'+ value.id +'">', { value : value.id }).text(value.name));
                        }
                         
                    });
                    $('#assignfacility').modal('show');

                }
            });
    
    
};

//delete facility administrator
function deletefa($id){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $.ajax({
        type: 'post',
        url:  'deletefadmin/'+$id,
        success: function (result) {
               
            if (result.code == 201) {

                $('#fadmin-table').DataTable().ajax.reload();
                return false;
            } else if (result.code === 400) {
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



//facility admin edit ajax call
$('#facilityAdminEditDataSend').on('click', function () {

    var form = $('#fAData');
    
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
                total_facility: {
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
                fa_id: {
                    required: true,
                   
                    maxlength: 11,
                },
                fa_name: {
                    required: true,
                },
                phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                username: {
                    required: true
                },
                password: 'required'
                
            },
            messages: {
                fa_id: 'Please enter facility admin id',
                fa_name: 'Please enter facility admin name',
                total_facility: 'Please enter total facility(numeric only)',
                first_name: 'Please enter facility admin first name',
                last_name: 'Please enter facility admin last name',
                username: 'Please enter username',
                password: 'Please enter password',
             
                phone: 'Please enter facility phone',
                email: 'Please enter your email',
                zip: 'Please enter zip',
            }
    });
    if (form.valid() === true) {
        $.ajax({
            type: 'post',
            url: baseURL + 'updatefacilityadmin',
            data: $('#fAData').serialize(),
            dataType: 'json',
            success: function (result) { 
                if (result.Code === 200) {
                    sessionStorage.updatefa = 'Facility Admin updated successfully';
                    window.location.href = baseURL + 'fadmins';
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

$('.js-example-basic-multiple').select2({
    placeholder: "Select Facility",
    allowClear: true
});

//Function to make active facility admin
function activatefa($id){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url:  'activatefadmin/'+$id,
        success: function (result) {
               
            if (result.code == 201) {

                $('#fa_inactive-table').DataTable().ajax.reload();
                return false;
            } else if (result.code === 400) {
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
    
