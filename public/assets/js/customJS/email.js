$(document).ready(function () {
    $("a[data-wysihtml5-command=insertImage]").hide();
    $("a[data-wysihtml5-command=createLink]").hide();
    var currentLocation = window.location.href;
    if (sessionStorage.sendEmail) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg ol-md-11 col-lg-11'>" + sessionStorage.sendEmail + "</div>");
    }
    if (sessionStorage.alertsendEmail) {
        $('#alertDangerDiv').show();
        $('#alertDanger').prepend("<div class='msg ol-md-11 col-lg-11'>" + sessionStorage.alertsendEmail + "</div>");
    }
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    sessionStorage.sendEmail = '';
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDangerDiv').hide();
    }, 5000);
    sessionStorage.alertsendEmail = '';


    oTable = $(".mailtable").dataTable({
        "bPaginate": true,
        "bInfo": true,
        "bFilter": true,
        "pageLength": 25,
        "pagingType": "simple",
        "bLengthChange": false,
        "fnInfoCallback": function (oSettings, iStart, iEnd, iMax, iTotal, sPre) {
            $('.pagenumber').text(iStart + " - " + iEnd + "/" + iTotal);
        }
    });
    $(".input-sm").on("keyup", function () {
        oTable.fnFilter($(this).val());
    });

    $('.prev_btn').click(function () {
        oTable.fnPageChange('previous');
    });

    $('.next_btn').click(function () {
        oTable.fnPageChange('next');
    });

    $('.dataTables_paginate').addClass('hide');
    $('.dataTables_filter').addClass('hide');


    $('.forward_mail').on('click', function () {
        inmateID = $(this).attr('userid');
        mailID = $(this).attr('mailid');
        $.ajax({
            type: 'get',
            url: baseURL + 'api/inmate_emails/',
            data: {
                "inmate_id": inmateID,
                "mail_id": mailID
            },
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    createEmailDropdown(result.Data);
                    $('#emailForwardModal').modal();
                    return false;
                } else if (result.Code === 400) {
                    swal("Error!!", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });

    $('.ForwardEmalMessage').on('click', function () {
        $( ".loader-default" ).addClass( "is-active" );
        inmateID = $(this).attr('userid');
        mail_id = $("input[name=email_id]").val();
        bodynew = $("textarea[name=body]").val();
        emailaddress = $("select[name=emails]").val();
        emailtype = 2;
        console.log(emailaddress);
        $.ajax({
            type: 'post',
            url: apiURL + 'forward_email',
            dataType: 'json',
            data: {
                "inmate_id": inmateID,
                "mail_id": mail_id,
                "to": emailaddress,
                'bodynew': bodynew,
                'emailtype' :emailtype
            },
            success: function (result) {
                if (result.Code === 200) {
                    $( ".loader-default" ).removeClass( "is-active" );
                    sessionStorage.sendEmail = result.Message;
                    window.location.href = currentLocation;
                    return false;
                } else if (result.Code === 400) {
                    $( ".loader-default" ).removeClass( "is-active" );
                    swal("Error!!", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                $( ".loader-default" ).removeClass( "is-active" );
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });

    $('.reply_mail').on('click', function () {
        inmateID = $(this).attr('userid');
        mailID = $(this).attr('mailid');
        $.ajax({
            type: 'get',
            url: baseURL + 'api/inmate_emails/',
              data: {
                "inmate_id": inmateID,
                "mail_id": mailID
            },
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    createEmailReplyDropdown(result.Data,result.selectedEmail,result.selectedname);
                    $('#emailReplyModal').modal();
                    return false;
                } else if (result.Code === 400) {
                    swal("Error!!", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });

 $('.replyEmalMessage').on('click', function () {
        $( ".loader-default" ).addClass( "is-active" );
        inmateID = $(this).attr('userid');
        mail_id = $("input[name=email_id]").val();
        //bodynew = $("textarea[name=repbody]").val();
        bodynew  = $('textarea#repcompose-textarea').val();
        emailaddress = $("select[name=emails]").val();
        emailtype = 1;
        console.log(emailaddress);
        console.log(bodynew);
        $.ajax({
            type: 'post',
            url: apiURL + 'forward_email',
            dataType: 'json',
            data: {
                "inmate_id": inmateID,
                "mail_id": mail_id,
                "to": emailaddress,
                'bodynew': bodynew,
                'emailtype' : emailtype
            },
            success: function (result) {
                if (result.Code === 200) {
                    $( ".loader-default" ).removeClass( "is-active" );
                    sessionStorage.sendEmail = result.Message;
                    window.location.href = currentLocation;
                    return false;
                } else if (result.Code === 400) {
                    $( ".loader-default" ).removeClass( "is-active" );
                    swal("Error!!", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                $( ".loader-default" ).removeClass( "is-active" );
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
});

function createEmailReplyDropdown(data,selectedEmail,selectedname) {

    if (data.length > 0) {
        var strTable = '<label for="exampleInputEmail">Select Email <i class="requiredInput text-red">*</i></label>';
        strTable += '<select class="form-control" name="emails" >';
            var selectedEmails = selectedEmail;
              var selectedName = selectedname;
             if(selectedEmails ){

                 strTable += '<option selected="selected" value="' + selectedEmails + '">' + selectedname + '</option>';
            }
        
        strTable += "</select>";
    } else {
        var strTable = '<h4>No approved email found.</h4>';
    }
    $('#replybody').html(strTable);

}

function createEmailDropdown(data,EmailData) {

    if (data.length > 0) {
        var strTable = '<label for="exampleInputEmail1">Select Email <i class="requiredInput text-red">*</i></label>';
        strTable += '<select class="form-control" name="emails" >';
        for (var i = 0, len = data.length; i < len; i++) {
            var email = data[i].email_phone;
             var name = data[i].name;
            strTable += '<option value="' + email + '">' + name + '</option>';
        }
       
        strTable += "</select>";
    } else {
        var strTable = '<h4>No approved email found.</h4>';
    }
    $('#forwardbody').html(strTable);

}