@extends('layouts.mobilesms')
@section('content')
        <!-- Main content -->
<div class="content-wrapper">
     <section class="content-header">
        <h1>Send Text </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <form method="post" id="smsData" class="emailDataform" action="javascript:;">
                        
                                <!-- Flash message -->
                                <div class="col-md-12 col-sm-12 alert alert-success" id="alertDiv" style="display:none">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                    <span id="alert"></span>
                                </div>
                                <div class="col-md-12  col-sm-12 alert alert-danger" id="alertDangerDiv" style="display:none">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                    <span id="alertDanger"></span>
                                </div>
                                <!-- Flash message -->

                         
                            <div class="col-md-9 col-lg-9 col-lg-push-3  col-md-push-3">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-11 col-xs-11 col-sm-11 text-right">
                                            <a href="{{route('contactnumber',['inmate_id' => $inmate_id,'service_id' => $service_id])}}" type="button" id="add_listSMS"class="btn btn-primary btn-sm">ADD/VIEW Number</a>
                                        </div>
                                    </div>
                                </div>
                            @if($limitleft < 0 )
                            <h5>Your Maximum Limit for total Contact number list has been redefined. Please remove any {{ $limitleft }}
                            then you will be able to send text</h5>
                            @else
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2 col-lg-2 col-xs-2 col-sm-2 text-right">
                                            <label><span class="label-icon"></span><strong>To</strong></label>
                                        </div>
                                        <div class="col-xs-9 control-block">
                                            <input type="hidden" name="inmate_id" class="form-control" value="{{$inmate_id}}" />
                                            <input type="hidden" name="service_id" class="form-control" value="{{$service_id}}" />
                                             <select class="form-control" name="number" >
                                                            @if(count($contactnumber) > 0)
                                                                @foreach($contactnumber as $phone)
                                                                <option value={{ $phone->email_phone }}>{{ $phone->name }}</option>
                                                                @endforeach
                                                            @else
                                                                <option>Add Contact Number First</option>
                                                            @endif 
                                                            @if(count($PreApprovedContacts) >0)
                                                                @foreach($PreApprovedContacts as $contacts)
                                                                 <option value={{ $contacts->contact_number }}>{{ $contacts->name }}</option>
                                                                @endforeach
                                                            @endif
                                            </select>	
                                        </div>    
                                    </div>
                                </div>
                        
                                <div class="form-group"  style="display: none;">
                                   <div class="row">
                                       <div class="col-md-2 col-lg-2 col-xs-2 col-sm-2 text-right">
                                           <label><span class="label-icon"></span><strong>Name</strong></label>
                                       </div>
                                       <div class="col-xs-9 control-block">
                                           <input type="text" name="name" class="form-control" id="name" />	
                                       </div>    
                                   </div>
                               </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2 col-lg-2 col-xs-2 col-sm-2 text-right">
                                            <label><span class="label-icon"></span><strong>Body</strong></label>
                                        </div>
                                        <div class="col-xs-9 control-block">
                                            <textarea id="body" name="body" class   ="form-control"></textarea>	
                                        </div>    
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                      <div class="col-xs-7 text-right">
                                          <input type="submit" id="smsDateSend" value="Send Text" class="btn btn-primary btn-sm" onclick="configureValue()">  
                                      </div>    
                                  </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="col-xs-10">
                                            <p>Note: This action will deduct  ${{$smsCharge}}</p>
                                        </div>    
                                    </div>
                                </div>
                             @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<div class="loader loader-default" data-text="Sending..." data-blink></div>
</div>
<script>
    var currentLocation = window.location.href;
   // baseURL = window.location.origin+'/axxs_qa/public/index.php/';
   //baseURL = 'http://172.16.10.117:8000/';
    if(sessionStorage.sendSMS) {  
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg ol-md-11 col-lg-11'>"+sessionStorage.sendSMS+"</div>");
    }
    if(sessionStorage.alertsendSMS) {  
        $('#alertDangerDiv').show();
        $('#alertDanger').prepend("<div class='msg ol-md-11 col-lg-11'>"+sessionStorage.alertsendSMS+"</div>");
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
//                alert(jqXHR);
                
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

