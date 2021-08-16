@extends('layouts.default')
@section('content')   

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
   .form-group input[type="checkbox"] {
    display: none;
}

.form-group input[type="checkbox"] + .btn-group > label span {
    width: 20px;
}

.form-group input[type="checkbox"] + .btn-group > label span:first-child {
    display: none;
}
.form-group input[type="checkbox"] + .btn-group > label span:last-child {
    display: inline-block;   
}

.form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
    display: inline-block;
}
.form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
    display: none;   
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($facilityInfo)) Edit
                @else Add
            @endif Facility
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('superadmin.index')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('facility.list')}}">Facility List</a></li>
            <li class="active">@if (isset($facilityInfo)) Edit
                @else Add
                @endif Facility
            </li>
        </ol>
    </section>
    
     @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@if (isset($facilityInfo)) Edit
                            @else Add
                            @endif Facility
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <input type="hidden"  id="fa_admin_id"  value="{{ $facilityInfo->facility_admin or '' }}">
                    <form role="form" method="post" action="javascript:;" id="facilityData">
                         {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id" value="{{ $facilityInfo->id or '' }}">
                        <div class="box-body">
                             <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Facility ID <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="facility_id" value="{{ $facilityInfo->facility_id or '' }}" maxLength = "11" id="facility_id" placeholder="Please enter first id"  @isset($facilityInfo) disabled @endisset/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $facilityInfo->first_name or '' }}" id="first_name" placeholder="Please enter first name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $facilityInfo->last_name or '' }}" id="last_name" placeholder="Please enter last name">
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Facility Admin<i class="requiredInput text-red">*</i></label>
                                    <select id="fa_list" class="form-control" name="facility_admin">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Total User<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="total_inmate" value="{{ $facilityInfo->total_inmate or '' }}" id="total_inmate" placeholder="Please enter total User number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="email" value="{{ $facilityInfo->email or '' }}" id="email" placeholder="Please enter email" >
                                </div>
                            </div> </div>
                           <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Username <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="username" value="{{ $facilityInfo->username or '' }}" id="username" placeholder="Please enter user name" @isset($facilityInfo) disabled @endisset>
                                </div>
                            </div>
                           
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $facilityInfo->phone or '' }}" onkeypress="return isNumberKey(event);" placeholder="Please enter phone">
                                </div>
                            </div> </div>
                            <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Facility Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="facility_name" value="{{ $facilityInfo->facility_name or '' }}" id="facility_name" placeholder="Please enter user facility name" >
                                </div>
                            </div>
                           
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Location </label>
                                    <input type="text" class="form-control" name="location" value="{{ $facilityInfo->location or '' }}" placeholder="Please enter location">
                                </div>
                            </div> </div>
                           <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Twilio Phone <i class="requiredInput text-red"></i></label>
                                    <input type="hidden"  id="output" name="twilio_number"  value="{{ $facilityInfo->twilio_number or '' }}"/>
                                    <input id="phone" type="tel" class="form-control" value="{{ $facilityInfo->twilio_number or '' }}" />	
                                    <span id="valid-msg" class="hide">✓ Valid</span>
                                    <span id="error-msg" class="hide">Invalid number</span>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State </label>
                                    <input type="text" class="form-control" name="state" value="{{ $facilityInfo->state or '' }}" id="state" placeholder="Please enter state">
                                </div>
                            </div> </div>
                           <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City </label>
                                    <input type="text" class="form-control" name="city" value="{{ $facilityInfo->city or '' }}" id="city" placeholder="Please enter city">
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip </label>
                                    <input type="text" class="form-control" name="zip" value="{{ $facilityInfo->zip or '' }}" id="zip" placeholder="Please enter zip">
                                </div>
                            </div> </div>
                           <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $facilityInfo->address_line_1 or '' }}" name="address_line_1" id="address_line_1" placeholder="Please enter address line 1">
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 2 </label>
                                    <input type="text" class="form-control" name="address_line_2" value="{{ $facilityInfo->address_line_2 or '' }}" id="address_line_2" placeholder="Please enter address line 2">
                                </div>
                            </div> </div>
                           
                            <div class="row no-margin">
                                  @if(!isset($facilityInfo->id))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password<i class="requiredInput text-red">*</i></label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Please enter password">
                                </div>
                            </div> 
                         @endif
                          <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="exampleInputEmail1">Tablet Charge($/Minutes)<i class="requiredInput text-red">*</i></label>
                                     <input type="text" class="form-control" name="tablet_charge" value="{{ $facilityInfo->tablet_charge or '' }}" id="zip" placeholder="Please enter tablet charges">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                <div class="col-md-4">
                                <div class="form-group">
                                    <!-- Default checked -->
                                    <br>
                                    <label  for="exampleInputEmail1">CPC Funding</label> &nbsp;
                                    <input id="f_status" type="checkbox" class="form-control"  data-toggle="toggle" data-style="ios" data-on="Enabled" data-off="Disabled" data-size="small"  value="{{$facilityInfo->cpc_funding or '0'}}" <?php if (isset($facilityInfo->cpc_funding) && $facilityInfo->cpc_funding  == 1): ?>
                                        checked
                                    <?php endif ?>>
                                    <input id="cpc_funding" type="hidden" name="cpc_funding" value="{{$facilityInfo->cpc_funding or '0'}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <!-- Default checked -->
                                    <br>
                                    <label for="exampleInputEmail1">Contact Approval</label> &nbsp;
                                    <input id="contact_app_switch" type="checkbox" class="form-control"  data-toggle="toggle"  title="Tooltip on top" data-style="ios" data-on="Enabled" data-off="Disabled" data-size="small"  value="{{$facilityInfo->cntct_approval or '1'}}" <?php if (isset($facilityInfo->cntct_approval) && $facilityInfo->cntct_approval  == 1 || !isset($facilityInfo->cntct_approval)): ?>
                                        checked
                                    <?php endif ?>>
                                    <input id="cntct_approval" type="hidden" name="cntct_approval" value="{{$facilityInfo->cntct_approval or '1'}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <!-- Default checked -->
                                    <br>
                                    <label for="exampleInputEmail1">Device On/Off</label> &nbsp;
                                    <input id="device_status_switch" type="checkbox" class="form-control"  data-toggle="toggle" data-onstyle="success" data-offstyle="danger"  title="Tooltip on top" data-style="ios" data-on="On" data-off="Off" data-size="small"  value="{{$facilityInfo->cntct_approval or '1'}}" <?php if (isset($facilityInfo->device_status) && $facilityInfo->device_status  == 1 || !isset($facilityInfo->device_status)): ?>
                                        checked
                                    <?php endif ?>>
                                    <input id="device_status" type="hidden" name="device_status" value="{{$facilityInfo->device_status or '1'}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <!-- Default checked -->
                                    <br>
                                    <label for="exampleInputEmail1">Create User Email</label> &nbsp;
                                    <input id="create_email_switch" type="checkbox" class="form-control"  data-toggle="toggle" data-onstyle="success" data-offstyle="danger"  title="Tooltip on top" data-style="ios" data-on="Enabled" data-off="Disabled" data-size="small"  value="{{$facilityInfo->create_email or '0'}}" <?php if (isset($facilityInfo->create_email) && $facilityInfo->create_email  == 1): ?>
                                        checked
                                    <?php endif ?>>
                                    <input id="create_email" type="hidden" name="create_email" value="{{$facilityInfo->create_email or '0'}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <!-- Default checked -->
                                    <br>
                                    <label for="exampleInputEmail1">Tablet Charge</label> &nbsp;
                                    <input id="tb_charge_switch" type="checkbox" class="form-control"  data-toggle="toggle" data-onstyle="success" data-offstyle="danger"  title="Tooltip on top" data-style="ios" data-on="On" data-off="Off" data-size="small"  value="{{$facilityInfo->create_email or '0'}}" <?php if (isset($facilityInfo->tablet_charges) && $facilityInfo->tablet_charges  == 1): ?>
                                        checked
                                    <?php endif ?>>
                                    <input id="tb_charge" type="hidden" name="tb_charge" value="{{$facilityInfo->tablet_charges or '0'}}">
                                </div>
                            </div>
                        </div>

                            </div>
                            <div class="col-md-6">
                                <label>Free Minutes/every day<i class="requiredInput text-red">*</i></label>
                                <input type="text" class="form-control" value="{{ $facilityInfo->free_minutes or '' }}"name="free_minutes" placeholder="Please enter free minutes">
                                
                            </div>
                            <div class="col-md-6">
                                <br>
                                <div class="[ form-group ]">
                                            <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" @if(isset($facilityInfo) && $facilityInfo->show_email == 1) checked @endif/>
                                            <div class="[ btn-group ]">
                                                <label for="fancy-checkbox-info" class="[ btn btn-info ]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span> </span>
                                                </label>
                                                <label for="fancy-checkbox-info" class="[ btn btn-default active ]">
                                                    Show Email
                                                </label>
                                                <br>
                                                <small class="mute">Click to show email address of inmates on devices</small>
                                            </div>
                                        </div>
                                    <input type="hidden" name="show_email" id="show_email" value="{{ $facilityInfo->show_email or 0 }}">
                                
                            </div>
                            </div>
                             <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Outgoing Email Charge ($/per Email) </label>
                                    <input type="text" class="form-control" value="{{ $facilityInfo->email_charges or '' }}" name="email_charges" id="email_charges" placeholder="Please enter email charges">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Incoming Email Charge ($/per Email) </label>
                                    <input type="text" class="form-control" value="{{ $facilityInfo->incoming_email_charge or '' }}" name="incoming_email_charge" id="incoming_email_charge" placeholder="Please enter email charges">
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Outgoing SMS Charge ($/per SMS)</label>
                                    <input type="text" class="form-control" name="sms_charges" value="{{ $facilityInfo->sms_charges or '' }}" id="sms_charges" placeholder="Please enter outgoing sms charges">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Incoming SMS Charge ($/per SMS)</label>
                                    <input type="text" class="form-control" name="in_sms_charge" value="{{ $facilityInfo->in_sms_charge or '' }}" id="in_sms_charge" placeholder="Please enter incoming sms charges">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Attachment Charge ($/per Attachment)</label>
                                    <input type="text" class="form-control" name="attachment_charge" value="{{ $facilityInfo->attachment_charge or '' }}" id="sms_charges" placeholder="Please enter attachment charges">
                                </div>
                            </div>
                            @role('Super Admin')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Terms and Condition</label>
                                    <br>
                                        <textarea class="form-control" id="terms_condition" name="terms_condition">{{$facilityInfo->terms_condition or ''}}</textarea>
                                    </div>
                            </div>
                            @endrole

                            @can('Manage Welcome Message')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Welcome Message</label>
                                    <br>
                                        <textarea rows="5" class="form-control"  name="welcome_msg">{{$facilityInfo->welcome_msg or ''}}</textarea>
                                    </div>
                            </div>
                            @endcan

                             </div>
                            
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                             <div class="col-md-12">
                                <a href="{{action('FacilityController@facilityListUI')}}" class="btn btn-primary" >Cancel</a>
                                <button type="submit" class="btn btn-primary" id="{{ isset($facilityInfo) ? 'facilityEditDataSend' : 'facilityAddDataSend' }}">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/facility.js') }}" type="text/javascript"></script>
<script src="<?php echo asset('/'); ?>assets/build/js/intlTelInput.js" type="text/javascript"></script>
  <script>
     $(document).ready(function() {
        $('#terms_condition').wysihtml5();
    });

     $('#fancy-checkbox-info').click(function(){
            if($(this).prop("checked") == true){
                alert('Please make sure the email address is created for all the inmates');
               $('#show_email').val(1);
            }
            else if($(this).prop("checked") == false){
                $('#show_email').val(0);
            }
        });

$.ajax({
        type: "GET",
        url: '/getfa_list',
        success: function( data ) {
            var facility_admin_id = $("#fa_admin_id").val();
            $('#fa_list').append($('<option selected value="" >', { value :null }).text('Select Facility'));
        $.each(data.fa_list, function(key, value) {
            if (value.id == facility_admin_id) {
                $('#fa_list').append($('<option selected  value="'+ value.id +'" >', { value : value.id }).text(value.name));
            } else{
                $('#fa_list').append($('<option  value="'+ value.id +'" >', { value : value.id }).text(value.name));
            }
             
        });
      }
                });

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
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : event.keyCode;
 console.log(charCode);
    if (charCode != 46 && charCode != 45 && charCode > 31
    && (charCode < 48 || charCode > 57))
     return false;

  return true;
}
  </script>
@stop