@extends('layouts.mobile')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <!-- Main content -->
   <section class="content-header">
        <h1>Manage Emails</h1>
    </section>
         <section class="content">
            <div class="row">
                 <div class="col-md-12">
                       <div class="box box-primary">
                      <div class="box-body">
                   <div class="row" style="margin: 2% 0% 0% 0%;">
                    @if(($limitleft > 0) || !($limitinfo))
                   <form method="post" id="emailData" class="emailDataform"  action="javascript:;">
                      
                            <!-- Flash message -->
                            <div class="col-md-12 col-sm-12 alert alert-success" id="alertDiv" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                <span id="alert"></span>
                            </div>
                            <div class="col-md-12  col-sm-12  text-center alert alert-danger" id="alertDangerDiv" style="display:none">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                <span id="alertDanger"></span>
                            </div>
                            <!-- Flash message -->
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
                      
                        <div class="col-md-9 col-lg-9  col-sm-12 col-lg-push-2 col-md-push-2">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2 col-lg-1 col-xs-2 col-sm-2 text-right">
                                        <label><span class="label-icon"></span><strong>Email</strong></label>
                                    </div>
                                    <div class="col-xs-9 control-block">
                                        <input type="hidden" name="inmate_id" class="form-control" value="{{$inmate_id}}" />
                                        <input type="hidden" name="service_id" class="form-control" value="{{$service_id}}" />
                                        <input type="text" name="email_phone" class="form-control" value="" id="number" />  
                                    </div>    
                                </div>
                            </div>

                             <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2 col-lg-1 col-xs-2 col-sm-2 text-right">
                                        <label><span class="label-icon"></span><strong>Name</strong></label>
                                    </div>
                                    <div class="col-xs-9 control-block">
                                        <input type="text" name="name" class="form-control" id="name" />    
                                    </div>    
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2 col-lg-1 col-xs-2 col-sm-2 text-right">
                                        <label><span class="label-icon"></span><strong>Relation</strong></label>
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
                                        <input type="submit" id="emailDateSend" @if($cntct_approval == 1)value="Ask for Approval" @else value="Add Contact" @endif class="btn btn-primary btn-sm" onclick="configureValue()">  
                                    </div>    
                                </div>
                              </div>
                             <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-8 ">
                                        @if($limitinfo)
                                        <p class="text-info"> Note: Your limit for adding Email ID is set to {{$limitinfo}}, only {{$limitleft}} left.</p>
                                        @else
                                        <p>Note: No limit has been set for adding Email Id</p>
                                        @endif
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </form>
                       </div>
                    
                @else
                    <div class="row" style="margin: 2% 0% 0% 0%;">
                         <div class="col-md-9 col-lg-9 col-lg-push-2 col-md-push-2">
                         <h4 class="text-info"> Note: Your limit for adding Email ID is set to {{$limitinfo}} and you have reached to you limit . </h4>
                         </div>
                    </div>
                @endif
                    <div class="row">
                        <div class="col-md-9 col-lg-9 col-lg-push-2 col-md-push-2">
                        <div class="form-group">
                            
                             @if(count($contacts)==0)
                             <h5 class="text-info">You don't have any email address,Please add one.</h5> 
                             @else
                             
                            <div class="row">
                                <div class="col-xs-10 col-md-10 col-lg-10  ">
                                    <h4>List of all Email contacts</h4> 
                                </div>   
                            </div>
                            
                              <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Contact Name</th>
                                        <th>Contact Email</th>
                                        <th >Status</th>
                                        <th>Action</th>
                                          <th>Resend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                      <?php  $index = 0; ?>
                                    @foreach($contacts as $contact)
                                     <?php  $index++ ?>
                                    <tr>
                                        <td >{{ $index }}</td>
                                        <td >{{ $contact->name }}</td>
                                        <td >{{ $contact->email_phone }}</td>
                                        <td >@if($contact->varified == 0) Not Verified @else @if($contact->is_approved == 0) Un Approved @else Approved @endif @endif
                                        </td>                               
                                        <td>
                                            <form method="get" action="/index.php/deletecontact/{{ $contact->id }}">
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
                                             </button>
                                             </form>
                                        </td>
                                         <td> 
                                         <button type="submit" userid="{{ $contact->id }}" @if($contact->is_approved == 1 && $contact->varified == 1) disabled class=" btn btn-success btn-xs"  @endif @if($contact->varified == 0)  class=" resend btn btn-warning btn-xs" @endif @if($contact->is_approved == 0 && $contact->varified == 1)  class=" disabled btn btn-success btn-xs" @endif>
                                          Resend
                                      </button>
                                    </td>
                                    </tr>
                                    @endforeach
                                     @foreach($preApprovedContacts as $contact)
                                      <?php  $index++; ?>
                                    <tr>
                                        <td >{{ $index }}</td>
                                        <td >{{ $contact->name }}</td>
                                        <td >{{ $contact->email_phone }}</td>
                                        <td >@if($contact->varified == 0) Not Verified @else @if($contact->is_approved == 0) Un Approved @else Approved @endif @endif
                                        </td>                               
                                        <td>
                                             Pre-Approved
                                        </td>
                                        <td><button type="submit"  disabled class=" btn btn-success btn-xs"> Resend</button></td>
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
            </div>
             <div class="loader loader-default" data-text="Sending..." data-blink></div>
        </section>
         
</div>


<script src="{{ asset('/assets/js/customJS/inmatecontact.js') }}" type="text/javascript"></script>

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
    $('#emailDateSend').click(function () { 
          $( ".loader-default" ).addClass( "is-active" );
        $.ajax({
            //url: 'http://172.16.10.117:8000/api/sentmail',
            url: baseURL+'api/sendemail',
            type: 'POST',            
            data:  $('#emailData').serialize(),
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
    
    if (sessionStorage.insert) {
        $('#alertDiv').show();
        $('#alert').prepend("<div class='msg'>" + sessionStorage.insert + "</div>");
  }
    if(sessionStorage.alertemail) {  
        $('#alertDangerDiv').show();
        $('#alertDanger').prepend("<div class='msg ol-md-11 col-lg-11'>"+sessionStorage.alertemail+"</div>");
    }
    
    setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDiv').hide();
    }, 5000);
    
     setTimeout(function () {
        $('.msg').css('display', 'none');
        $('#alertDangerDiv').hide();
    }, 5000);
    sessionStorage.insert = '';
    sessionStorage.alertemail = '';

  $("body").on("click", ".resend", function (e) {
 if(confirm('Are you want to send verification email again.')){  
  id = $(this).attr('userid');
   $( ".loader-default" ).addClass( "is-active" );
  $.ajax({
    type: 'get',
    url: baseURL + 'resendvarification',
   data: {id: id},
    dataType: 'json',
       success: function (result) {
        if (result.Code === 200) {
            $( ".loader-default" ).removeClass( "is-active" );
            sessionStorage.insert = 'Resend verification email successfully';
            location. reload(true);
            //window.location.href = baseURL + 'allusers';
            return false;
        } else if (result.Code === 400) {
            $( ".loader-default" ).removeClass( "is-active" );
             sessionStorage.insert = 'Allready Approved your email id';
             location. reload(true);
            return false;
        }
          else if (result.Code === 401) {
            $( ".loader-default" ).removeClass( "is-active" );
               sessionStorage.alertemail = 'Due to Email ID block you can not send verification email';
               location. reload(true);
              return false;
          }
    },
    error: function (jqXHR, exception) {
        $( ".loader-default" ).removeClass( "is-active" );
        console.log('jqXHR' + jqXHR);
        console.log('exception' + exception);
        swal('Error!!', exception, 'error');
    }
  });
 } else {
    e.preventDefault();
    return false;
  }

  });
</script>  
@stop