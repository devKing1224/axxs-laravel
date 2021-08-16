$(document).ready(function () {

    $("#successMessage").delay(2000).slideUp(300);
    if (sessionStorage.delete) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.delete + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.delete = '';

    /* Function for delete inmate */
    $("body").on("click", ".inmateContactID", function (e) {
        ContactID = $(this).attr('id');
        inmateID = $(this).attr('inmate_id');
        swal({
            title: "Are you sure?",
            text: "You want to delete Contact entry!",
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
                            url: '/deletecontactbyfacility/' + ContactID,
                            dataType: 'json',
                            success: function (result) { 
                                if (result.Code === 200) {
                                    sessionStorage.delete = 'Contact deleted successfully';
                                    window.location = baseURL + 'contactlist/' + inmateID;
                                    return false;
                                } else if (result.Code === 400) {
                                   swal("Contact not deleted", result.Message, "warning");
                                    return false;
                                }
                            },
                            error: function (jqXHR, exception) {
                                console.log('jqXHR' + jqXHR);
                                console.log('exception' + exception);
                            }
                        });

                    } else {
                        swal("Cancelled", "Your contact is safe :)", "error");
                    }
                });
    });



});