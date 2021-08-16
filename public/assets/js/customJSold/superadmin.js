/* global apiURL, baseURL */

$(document).ready(function () {
    
    if(sessionStorage.inmateConfiguration) {  
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>"+sessionStorage.inmateConfiguration+"</div>");
    }
    setTimeout(function(){ $('.msg').css('display','none');  $('#alertDiv').hide(); }, 5000);
    sessionStorage.inmateConfiguration = '';
    
    /* Function for inactive service update to active service. */
    $('.sendConfigureData').click(function(){
        
        var tempIDValue = $(this).attr('id');
        $.ajax({
            type: 'post',
            data: $('#'+tempIDValue+'form').serialize(),
            url: apiURL+'registerconfiguration',
            dataType: 'json',
            success: function (result) { 
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                        window.location.href = baseURL+'configuration';
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
    
    /* Function for inactive service update to active service. */
    $('.sendFreeTabletConfigureData').click(function(){
        $.ajax({
            type: 'post',
            data: $('#freeTabletConfigform').serialize(),
            url: apiURL+'registertabletfreetimetabletchargeconfiguration',
            dataType: 'json',
            success: function (result) { console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                        window.location.href = baseURL+'configuration';
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
    
      $('.sendMaxLimit').click(function(){

        $.ajax({
            type: 'post',
            data: $('#maxContact').serialize(),
            url: apiURL+'setmaxlimit',
            dataType: 'json',
            success: function (result) { console.log(result);
                if (result.Code === 200) {
                    sessionStorage.inmateConfiguration = result.Status;
                        window.location.href = baseURL+'facilitydashboard';
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