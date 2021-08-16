@extends('layouts.default')
@section('content')
<!-- Content Wrapper. Contains page content -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha256-ENFZrbVzylNbgnXx0n3I1g//2WeO47XxoPe0vkp3NC8=" crossorigin="anonymous" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($serviceInfo)) Edit
                @else Add
            @endif Service
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/services')}}">Service</a></li>
            <li class="active">@if (isset($serviceInfo)) Edit
                @else Add
                @endif Service
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content service">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@if (isset($serviceInfo)) Edit
                @else Add
                @endif Service</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="serviceData">
                        <input type="hidden" class="form-control" name="service_id" value="{{ $serviceInfo->id or '' }}">
                         {{ csrf_field() }}
                        <div class="box-body">
                            @if(isset($service_id) && !empty($service_id))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFacility1">Facility</label>
                                        <select class="form-control" name="facility_id" id="dropdown_selector">
                                            <option value="" disabled>Select your option</option>
                                            <option  value="" style="color: #337ab7;font-weight: 800;font-family: cursive;" selected>Default</option>
                                            @foreach($facilityList as $facility)
                                            <option value="{{$facility->id}}">{{$facility->facility_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6" style="height:72px">
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    @if(isset($serviceInfo))
                                        <input type="checkbox" id="flat-rate" name="flat-rate" {{$serviceInfo->flat_rate == 1 ? 'checked' : ''}}>
                                    @else
                                        <input type="checkbox" id="flat-rate" name="flat-rate">
                                    @endif
                                    <label style="float: none" for="flat-rate">Flat Rate</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="flat-rate-charge">Flat Rate Charge</label>
                                    @if(isset($serviceInfo))
                                        <input type="text" class="form-control" name="flat-rate-charge" value="{{$serviceInfo->flat_rate_charge}}" id="flat-rate-charge" placeholder="$.$$" {{$serviceInfo->flat_rate == 0 ? 'disabled' : ''}}>
                                    @else
                                        <input type="text" class="form-control" name="flat-rate-charge" value="0.00" id="flat-rate-charge" placeholder="$.$$" disabled>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Category <i class="requiredInput text-red">*</i></label>
                                    <select class="form-control" name="service_category_id" id="service_category_id">
                                        <option value='' >None</option>
                                        @if (count($serviceCategory) > 0)
                                            @foreach( $serviceCategory as $value )
                                                <option value="{{$value->id}}" @if(isset($serviceInfo->service_category_id)){{ $value->id == $serviceInfo->service_category_id ? 'selected':'' }} @endif >{{$value->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Per minute charge <i class="requiredInput text-red">*</i></label>
                                    <select class="form-control" name="type" id="serviceType">
                                        @if (isset($serviceInfo))    
                                            <option value="0" {{ $serviceInfo->type == 0 ? 'selected' : '' }} >Free</option>
                                            <option value="1" {{ $serviceInfo->type == 1 ? 'selected' : '' }}>Facility Rate</option>
                                            <option value="2" {{ $serviceInfo->type == 2 ? 'selected' : '' }}>Premium Rate</option>
                                        @else
                                            <option value="0">Free</option>
                                            <option value="1">Facility Rate</option>
                                            <option value="2">Premium Rate</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="name" value="{{ $serviceInfo->name or '' }}" id="first_name" placeholder="Please enter name">
                                    <label>Disable Automatic Logout</label> &nbsp;
                                <input id="autolg" type="checkbox"   data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-size="small" @if(isset($serviceInfo->auto_logout))
                                @if($serviceInfo->auto_logout == 1) checked @endif
                                @endif  >
                                <input type="hidden" id="auto_logout" value="{{ $serviceInfo->auto_logout  or '0' }}" name="auto_logout" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Base URL<i class="requiredInput text-red">*</i></label>
                                    <textarea class="form-control" id="base_url" name="base_url" placeholder="Please enter base url">{{ $serviceInfo->base_url or '' }}</textarea>
                                     <input type="file" class="form-control" id="base_urlfile" name="base_urlfile" >

                                </div>
                            </div>
                            <div class="col-md-6">
<!--                                <div class="form-group">-->
<!--                                    <label>Logo URL<i class="requiredInput text-red">*</i></label>-->
                                    <input type="hidden" class="form-control" id="logo_url" name="logo_url" value="{{ $serviceInfo->logo_url or '' }}">
<!--                                </div>-->
                                <div class="form-group uploadimg">
                                    <label>Logo Image </label >@if(isset($serviceInfo->logo_url))
                                    &ensp;<img class="addimageurl" style="border:.5px #1ab7ea dashed " src="{{ $serviceInfo->logo_url or '' }}" height="35" width="35"> @endif
                                    <input type="file" class="form-control" id="logo_urlfile" name="logo_urlfile" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="chargelabel">Charge/Minute ($.$$)<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="charge" value="{{ $serviceInfo->charge or '' }}" id="charge" placeholder="Please enter charge">
                                </div>
                            </div>
                            <div class="col-md-12 text-info">
                               <div class="form-group">
                                   <b> Note: </b>Image size must be less than 100 KB and Extension should be JPG/JPEG/PNG</div>

                           </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea id="popup_msg" name="msg" class="form-control" placeholder="Enter message here">{{ $serviceInfo->msg or '' }}</textarea>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="checkbox" name="keyboardEnabled" id="keyboardEnabled" <?php if(isset($serviceInfo)){ ?> {{ $serviceInfo->keyboardEnabled == 1 ? 'checked' : '' }}<?php } ?>>
                                    <label style="float: none" for="keyboardEnabled">Keyboard Enabled</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="<?php if(isset($serviceInfo)){ ?>serviceEditDataSend<?php } else { ?>serviceAddDataSend<?php } ?>">Submit</button>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/service.js" type="text/javascript"></script>
<script type="text/javascript">
    $('#dropdown_selector').change(function() {
    //Use $option (with the "$") to see that the variable is a jQuery object
    var fac_id = $(this).find('option:selected').val();
    if( !fac_id) {
        fac_id = 'default';
        $('#service_category_id').removeAttr('disabled');
        $('#base_url').removeAttr('disabled');
        $('#first_name').removeAttr('disabled');
        $('#autolg').removeAttr('disabled');
        $('#logo_urlfile').removeAttr('disabled');
        $('#base_urlfile').removeAttr('disabled');
    } else{
        $('#service_category_id').attr('disabled', 'disabled');
        $('#base_url').attr('disabled', 'disabled');
        $('#first_name').attr('disabled', 'disabled');
        $('#autolg').attr('disabled', 'disabled');
        $('#logo_urlfile').attr('disabled', 'disabled');
        $('#base_urlfile').attr('disabled', 'disabled');


    }
    $.ajax({
      type:'GET',
      url:'{{url("/getServicechargeby_facility")}}'+'/'+fac_id+'/'+'{{$service_id}}',
      success:function(data) {
       var Data = data.Data;
       if (data.Code == 200) {
        if (Data.type != 0) {
            $("#charge").attr("readonly", false);
        } else{
            $("#charge").attr("readonly", true);
        }
        $('#charge').val(Data.charge);
        $('[name=type]').val(Data.type);
        $("#popup_msg").val(Data.service_msg);

    } else if(data.Code == 404){
       var ser_type = '<?php echo (isset($serviceInfo->type)) ? $serviceInfo->type : null ?>'
       var charge = '<?php echo (isset($serviceInfo->charge)) ? $serviceInfo->charge : null ?>'
       if (ser_type != 0) {
        $("#charge").attr("readonly", false);
    } else{
        $("#charge").attr("readonly", true);
    }
    $('[name=type]').val(ser_type);
    $('#charge').val(charge);
    $("#popup_msg").val(Data.Message);
    toastr.info(data.Message, 'Info');
}
}
});
});
</script>
<script type="text/javascript">
  const flatCharge = document.getElementById('flat-rate-charge');
  const flatRate = document.getElementById('flat-rate');
  flatRate.addEventListener('change', () => flatRate.checked ? flatCharge.disabled = false : flatCharge.disabled = true);
</script>
@stop