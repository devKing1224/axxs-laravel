@extends('layouts.mobilesms')
@section('content')    
  <style type="text/css" media="all">
    .boldemailtext {
          color: black;
          font-weight:bold;
    }
  </style>    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>View User SMS</h1>
    </section>
       <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                         <!-- Flash message -->
                        <div class="row">
                            <div class="col-md-10 col-sm-11 alert alert-success" style="display:none" id="alertDiv" >
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                <span id="alert"></span>
                            </div>
                            <div class="col-md-10 col-sm-11 alert alert-danger" id="alertDangerDiv" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                <span id="alertDanger"></span>
                            </div>
                        </div>
                        <!-- Flash message -->
                        @if($inmate_contact_detail->varified == '0')
                            @if($isdeleted == '1')
                            <h3 class="box-title">Trashed Chat with Un-Verified Contact</h3>
                            @else
                            <h3 class="box-title">Un-Verified Contact Number </h3>
                            @endif
                        @else
                       @if($inmate_contact_detail->is_approved == '0')
                            @if($isdeleted == '1')
                            <h3 class="box-title">Trashed Chat with Un-Approved Contact</h3>
                            @else
                            <h3 class="box-title">Un-Approved Contact Number </h3>
                            @endif
                       @else
                            @if($isdeleted == '1')
                            <h3 class="box-title">Trashed Chat</h3>
                            @else
                            <h3 class="box-title">Chat With {{$inmate_contact_detail->name}} </h3> 
                            <!-- /.btn-group -->
                <a href="{{ URL::current()}}" type="button" class="btn btn-default btn-sm" style="float:right;">Receive New Messages <i class="fa fa-refresh"></i></a>
                            @endif
                       @endif
                       @endif
                    </div> 
                     <div class="box-header with-border">
                     @if($isdeleted == '0' && $inmate_contact_detail->is_approved == '1' && $inmate_contact_detail->varified == '1')
                        <form method="post" id="smsData" class="emailDataform" action="javascript:;">
                            <div class="input-group">
                                <input type="hidden" name="inmate_id" class="form-control" value="{{$inmate_id}}" />
                                <input type="hidden" name="service_id" class="form-control" value="{{$service_id}}" />
                                <input type="hidden" name="number" class="form-control" value="{{$inmate_contact_detail->email_phone }}" />
                                <input type="hidden" name="name" class="form-control" value="{{$inmate_contact_detail->name }}" />
                                <input type="text" name="body" placeholder="Type Message ..." class="form-control">
                                    <span class="input-group-btn">
                                    <button type="submit"id="smsDateSend" class="btn btn-primary btn-flat" onclick="configureValue()">Send</button>
                                    </span>
                            </div>
                        </form>
                     @endif

                     </div>
                                   <!--/.box-header--> 
                    <div class="box-body">
<!--                Conversations are loaded here -->
                    @if($inmate_sms)
                        <div class="direct-chat-messages">
                        @foreach($inmate_sms as $sms)
                            @if($sms->bound == 'out')   
    <!--                 Message. Default to the left -->
                            <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">{{ $inmate_details->first_name }} {{ $inmate_details->last_name }}</span>
                                    <span class="direct-chat-timestamp pull-right">
                                        @if($sms->is_deleted == '1')
                                        <a href="{{ route('deletesms', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'sms_id' => $sms->id , 'delete' => '0' ]) }}" style="padding: 0 5px; color:red"><i class="fa fa-undo"></i></a>
                                        @else
                                        <a href="{{ route('deletesms', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'sms_id' => $sms->id , 'delete' => '1' ]) }}" style="padding: 0 5px; color:red"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </span>
                                    <span class="direct-chat-timestamp pull-right">{{ Carbon\Carbon::parse($sms->created_at)->setTimezone('EST')->format('jS, F Y,  h:i A') }}</span>
                                    
                                </div>
                                             <!--/.direct-chat-info--> 
                                <img class="direct-chat-img" src="{{ asset('bower_components\admin-lte\dist\img\greychat.png') }}" alt="Message User Image">
                                         <div class="direct-chat-text ">
                                             {{ $sms->message }}
                                      </div>
                       <!--/.direct-chat-text--> 
                            </div>
                            @else
    <!--               Message to the right -->
                            
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">{{ $inmate_contact_detail->name }}</span>
                                    <span class="direct-chat-timestamp pull-left">
                                      @if($sms->is_deleted == '1')
                                        <a href="{{ route('deletesms', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'sms_id' => $sms->id , 'delete' => '0' ]) }}" style="padding: 0 5px; color:red"><i class="fa fa-undo"></i></a>
                                        @else
                                        <a href="{{ route('deletesms', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'sms_id' => $sms->id , 'delete' => '1' ]) }}" style="padding: 0 5px; color:red"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </span>
                                    <span class="direct-chat-timestamp pull-left">{{ Carbon\Carbon::parse($sms->created_at)->setTimezone('EST')->format('jS, F Y,  h:i A') }}</span>
                                </div>
                                <img class="direct-chat-img" src="{{ asset('bower_components\admin-lte\dist\img\bluechat.png') }}" alt="Message User Image">
                                  @if($sms->is_viewed == 0)
                                        <div class="direct-chat-text boldemailtext">
                                    @else
                                          <div class="direct-chat-text">
                                    @endif
                                     {{ $sms->message }}
                                </div>

    <!--/.direct-chat-text--> 
                            </div>
     <!--/.direct-chat-msg--> @endif
                        @endforeach
                         </div>   
                   
                    @endif
                    </div>
<!--                                   /.box-body -->
                     <div class="box-footer">

                    </div>
<!--             /.box-footer-->
          </div>
        </div>
        <!-- /.row -->
        
         </div>
         <div class="loader loader-default" data-text="Sending..." data-blink></div>
        </section>
</div>
<script>
    var currentLocation = window.location.href;
   // baseURL = window.location.origin+'/axxs_qa/public/index.php/';
   //baseURL = 'http://172.16.10.117:8000/';
    if(sessionStorage.sendSMS) {  
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg ol-md-9 col-lg-9 col-lg-push-3 col-sm-push-3 col-md-push-3'>"+sessionStorage.sendSMS+"</div>");
    }
    if(sessionStorage.alertsendSMS) {  
        $('#alertDangerDiv').show();
        $('#alertDanger').prepend("<div class='msg ol-md-9 col-lg-9 col-lg-push-3 col-sm-push-3 col-md-push-3'>"+sessionStorage.alertsendSMS+"</div>");
    }
    setTimeout(function(){ $('.msg').css('display','none');  $('#alertDiv').hide(); }, 5000);
    sessionStorage.sendSMS = '';
    setTimeout(function(){ $('.msg').css('display','none');  $('#alertDangerDiv').hide(); }, 5000);
    sessionStorage.alertsendSMS = '';
    $('#smsDateSend').click(function () { 
         $( ".loader-default" ).addClass( "is-active" );
        $.ajax({
            //url: 'http://172.16.10.117:8000/api/sentmail',
            url: baseURL+'api/sendsms',
            type: 'POST',            
            data:  $('#smsData').serialize(),
            success: function (result) { //console.log(result);return false;
                if (result.Code === 200) {
                     $( ".loader-default" ).removeClass( "is-active" ); 
                    sessionStorage.sendSMS = result.Message;
                    window.location.href = currentLocation;
                    return false;
                } else if (result.Code === 400) {
                     $( ".loader-default" ).removeClass( "is-active" ); 
                    sessionStorage.alertsendSMS = result.Message;
                    window.location.href = currentLocation;
                    return false;
                }
            },
            error: function (jqXHR, exception) { 
                $( ".loader-default" ).removeClass( "is-active" ); 
                console.log(jqXHR);
                sessionStorage.alertsendSMS = result.Message;
                window.location.href = currentLocation;
                return false;
            }
        });
    });

    function configureValue() {
        return false;
    }
</script> 
@stop