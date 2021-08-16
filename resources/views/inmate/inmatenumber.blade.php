@extends('layouts.mobilesms')

@section('styles')
    <link rel="stylesheet" href="<?php echo asset('/'); ?>assets/build/css/intlTelInput.css">
@stop 
@section('content')
<div class="content-wrapper">
        <!-- Main content -->
   <section class="content-header">
        <h1>Manage Contact Numbers</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                      <div class="box-body">
                @if(($limitleft > 0) || !($limitinfo)) 
               <form method="post" id="PhoneData" class="emailDataform"  action="javascript:;">
                        <!-- Flash message -->
                        <div class="col-md-12 alert alert-success" id="alertDiv" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                            <span id="alert"></span>
                        </div>
                        <div class="col-md-12 alert alert-danger" id="alertDangerDiv" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                            <span id="alertDanger"></span>
                        </div>
                    @if(Session::has('message'))
                        <div class="alert alert-success fade-message">
                             <p>{{ Session::get('message') }}</p>
                        </div><br />

                        <script>
                        $(function(){
                            setTimeout(function() {
                                $('.fade-message').slideUp();
                            }, 4000);
                        });
                        </script>
                    @endif   

                        <!-- Flash message -->

                    <div class="col-md-9 col-lg-9 col-lg-push-2 col-md-push-2">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-xs-2 col-sm-2 text-right">
                                    <label><span class="label-icon"></span><strong>Add Number</strong></label>
                                </div>
                                <div class="col-xs-9 control-block">
                                    <input type="hidden" name="inmate_id" class="form-control" value="{{$inmate_id}}" />
                                    <input type="hidden"  name="service_id" class="form-control" value="{{$service_id}}" />
                                    <input type="hidden"  id="output" name="email_phone"  value=""/>
                                    <input id="phone" type="tel" class="form-control" value="" />	
                                    <span id="valid-msg" class="hide">✓ Valid</span>
                                    <span id="error-msg" class="hide">Invalid number</span>
                                </div>    
                            </div>
                        </div>
                        
                         <div class="form-group">
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
                                    <label><span class="label-icon"></span><strong>Relationship</strong></label>
                                </div>
                                 <div class="col-xs-9 control-block">
                                    <input type="text" name="relation" class="form-control" id="name" />	
                                </div>   
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-7 text-right">
                                    <input type="hidden" name="cntct_approval" value="{{$cntct_approval}}">
                                    <input type="submit" id="phoneDateSend" @if($cntct_approval == 1)value="Ask for Approval" @else value="Add Contact" @endif class="btn btn-primary btn-sm" onclick="configureValue()">  
                                </div>    
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                   <div class="col-xs-10 ">
                                       @if($limitinfo)
                                       <p> Note: Your limit for adding Contact number is set to {{$limitinfo}}, only {{$limitleft}} left.</p>
                                       @else
                                       <p>Note: No limit has been set for adding Contact number</p>
                                       @endif
                                   </div>    
                            </div>    
                        </div>
                    </div>
                 
                </form>
              @else
                <div style="margin: 5% 0% 0% 0%;">
                    <div class="col-md-10 col-lg-10 col-sm-11 col-lg-push-2 col-md-push-1 col-sm-push-1">
                        <div class="row">
                            <div class="col-md-11 col-lg-11 col-xs-11 col-sm-11">
                                <h4 class="text-info"> Note: Your limit for adding Contact number is set to {{$limitinfo}} and you have reached to you limit . </h4>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
              
                     <div class="col-md-12 col-lg-12 col-sm-12 ">     
                        <div class="form-group">
                            @if(count($contacts)==0)
                            
                            @else
                            <div class="row">
                                <div class="col-xs-11 control-block">
                                    <h4> List of all contacts</h4>	
                                </div>   
                            </div>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Contact Name</th>
                                        <th>Contact Number</th>
                                        <th >Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  $i = 0; ?>
                                 @foreach($preApprovedContacts as $index =>$preApprovedContact)
                                 <?php  $i++; ?>
                                <tr>
                                    <td >{{$i}}</td>
                                    <td >{{ $preApprovedContact->name }}</td>
                                    <td>{{ $preApprovedContact->contact_number }}</td>
                                    <td >Active</td>
                                  <td> Pre-Approved</td>                               
                                </tr>
                                 @endforeach

                                    @foreach($contacts as $contact)
                                     <?php  $i++; ?>
                                    <tr>
                                        <td >{{ $i }}</td>
                                         <td >{{ $contact->name }}</td>
                                        <td>{{ $contact->email_phone }}</td>
                                        <td >@if($contact->varified == 0) 
                                                Not Verified 
                                            @else 
                                                @if($contact->is_deleted == 0) 
                                                    @if($contact->is_approved == 0)
                                                        UnApproved 
                                                     @else 
                                                        Active
                                                    @endif 
                                                @else 
                                                    InActive 
                                                @endif 
                                            @endif
                                    </td>
                                        <td >
                                             <form method="delete" action="/index.php/deletecontact/{{ $contact->id }}">
                                             <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                                             <button type="submit" @if($contact->is_deleted == 0) @if($contact->is_approved == 1) class=" btn btn-danger btn-xs" @else class=" btn btn-warning btn-xs" @endif @else class=" btn btn-info btn-xs" @endif onclick="return confirm('Are you sure?')">
                                              @if($contact->is_deleted == '1') 
                                                     @if($contact->is_approved == '0')
                                                         Activate
                                                     @else
                                                         DeActivate
                                                     @endif
                                                     
                                                @else
                                                     DeActivate 
                                             @endif
                                             </button></form>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            @endif
                        </div> 
                    </div>
                </div>
                </div>
            </div>
        </div>
         <div class="loader loader-default" data-text="Processing..." data-blink></div>
    </section>
        
</div>
        <!--End Of Model Screen-->  
<script src="<?php echo asset('/'); ?>assets/build/js/intlTelInput.js" type="text/javascript"></script>
  <script>
    var telInput = $("#phone"),
    output = $("#output");
    errorMsg = $("#error-msg"),
    validMsg = $("#valid-msg");
    $("#phone").intlTelInput({ 
        initialCountry: "auto",
        geoIpLookup: function(callback) {
          $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
          });
        },
        nationalMode: true,
      utilsScript: "<?php echo asset('/'); ?>assets/build/js/utils.js"
    });
    
    var reset = function() {
    var intlNumber = telInput.intlTelInput("getNumber");
    if (intlNumber) {
      output.val("" + intlNumber);
    } else {
      output.val("Please enter a number below");
    }
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);

  </script>
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
    $('#phoneDateSend').click(function () { 
     if (telInput.intlTelInput("isValidNumber")) {
        $( ".loader-default" ).addClass( "is-active" );
        $.ajax({
            //url: 'http://172.16.10.117:8000/api/sentmail',
            url: baseURL+'api/sendphone',
            type: 'POST',            
            data:  $('#PhoneData').serialize(),
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
                 window.location.href = currentLocation;
                return false;
            }
        });
    } else {
     sessionStorage.alertsendSMS = "Enter valid number";
         if(sessionStorage.alertsendSMS) {  
        $('#alertDangerDiv').show();
        $('#alertDanger').prepend("<div class='msg ol-md-9 col-lg-9 col-lg-push-3 col-sm-push-3 col-md-push-3'>"+sessionStorage.alertsendSMS+"</div>");
    }
    setTimeout(function(){ $('.msg').css('display','none');  $('#alertDangerDiv').hide(); }, 5000);
    sessionStorage.alertsendSMS = '';
    }
    });
        function configureValue() {
        return false;
    }
</script>  
@stop