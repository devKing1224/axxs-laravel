@extends('layouts.mobile')
@section('content')
<!-- Main content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Compose New Message</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        @if($inmateemaildetails)
                        <form method="post" id="emailData" class="emailDataform"  action="javascript:;">

                            <!-- Flash message -->
                            <div class="col-md-12  col-sm-12 alert alert-success" id="alertDiv" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                <span id="alert"></span>
                            </div>
                            <div class="col-md-12 col-sm-12 alert alert-danger" id="alertDangerDiv" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                <span id="alertDanger"></span>
                            </div>
                            <!-- Flash message -->
                            <div class="col-md-9 col-lg-9 col-lg-push-2 col-md-push-2">

                                <!-- /.box-header -->
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="{{route('emailid',['inmate_id' => $inmate_id,'service_id' => $service_id])}}" type="button" id="add_listSMS"class="btn btn-primary btn-sm">ADD/VIEW Email</a>
                                        </div>
                                    </div>
                                </div>
                                @if($limitleft < 0 )
                                <h5>Your Maximum Limit for total Contact email list has been redefined. Please remove any {{ $limitleft }}
                                    then you will be able to send Email</h5>
                                @else
                                <div class="form-group">
                                    <input type="hidden" name="inmate_id" class="form-control" value="{{$inmate_id}}" />
                                    <input type="hidden" name="service_id" class="form-control" value="{{$service_id}}" />
                                    <select class="form-control" name="to" id="to" >
                                        @if(count($contactnumber) > 0)
                                        @foreach($contactnumber as $email)
                                        <option value={{ $email['email_phone'] }}>{{ $email['name'] }}</option>
                                        @endforeach
                                        @else
                                        <option>Add Email ID First</option>
                                        @endif 

                                    </select>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="title"  placeholder="Subject:">
                                </div>
                                <div class="form-group">
                                    <textarea id="compose-textarea" name="body" class="form-control" style="height: 300px">
                                           
                                    </textarea>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        
                                        <div class="col-xs-10">
                                            <p>Note: this action will deduct ${{$emailCharge}}</p>
                                        </div>    
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <div class="pull-right">
                                        <button type="submit" onclick="configureValue()" id="emailDateSend" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                                    </div>
                                    <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
                                </div>
                                @endif

                            </div>
                        </form>
                        @else
                        <h5>Please contact to your Facility Admin as no Email id has been assigned to you. </h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="loader loader-default" data-text="Sending..." data-blink></div>
</div>
<!--End Of Model Screen-->  

<script>
    var currentLocation = window.location.href;
    if (sessionStorage.sendEmail) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg ol-md-10 col-lg-10 '>" + sessionStorage.sendEmail + "</div>");
    }
    if (sessionStorage.alertsendEmail) {
        $('#alertDangerDiv').show();
        $('#alertDanger').prepend("<div class='msg ol-md-10 col-lg-10  '>" + sessionStorage.alertsendEmail + "</div>");
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
    $('#emailDateSend').click(function () {
        $(".loader-default").addClass("is-active");

        $.ajax({
            //url: 'http://172.16.10.117:8000/api/sentmail',
            url: "{{route('email.sendfunctionality')}}",
            type: 'POST',
            data: $('#emailData').serialize(),
            success: function (result) {
                console.log(result);
                if (result.Code === 200) {
                    $(".loader-default").removeClass("is-active");
                    sessionStorage.sendEmail = result.Message;
                    window.location.href = currentLocation;
                    return false;
                } else if (result.Code === 400) {
                    $(".loader-default").removeClass("is-active");
                    sessionStorage.alertsendEmail = result.Message;
                    window.location.href = currentLocation;
                    return false;
                }
            },
            error: function (jqXHR) {
                $(".loader-default").removeClass("is-active");

                sessionStorage.alertsendEmail = result.Message;
                //window.location.href = baseURL+'sendEmail';
                return false;
            }
        });
    });

    function configureValue() {
        return false;
    }

$(document).ready(function () {
    $("a[data-wysihtml5-command=insertImage]").hide();
    $("a[data-wysihtml5-command=createLink]").hide();
});

</script>
@stop

