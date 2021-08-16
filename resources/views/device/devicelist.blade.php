@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Toaster  -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/toaster/toaster.css')}}">
<script src="{{ asset('assets/toaster/toaster.js')}}"></script>
<style>
    .ui-autocomplete { 
        cursor:pointer; 
        height:120px; 
        overflow-y:scroll;
    }
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }  

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Device List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/devices')}}">Device</a></li>
            <li class="active">Device List</li>
        </ol>
    </section>
 
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    @if(Session::has('error'))
    <p id="error" class="alert {{ Session::get('alert-class', 'alert-danger') }} " >{{ Session::get('error') }}<button type="button" class="close" data-dismiss="alert">x</button></p>
    @endif
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <a href="{{action('DeviceController@addDeviceUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Device</a>
                        <a class="text-right"  href="{{route('device_list_report', ['id' => $facility_id ])}}" ><button class="btn btn-info"  @if(count($deviceList) == 0) disabled @endif ><i class="fa fa-file-excel-o" aria-hidden="true" ></i> Export Device List</button></a>
                        @role('Facility Admin')
                        <div style="float: right;">
                        <kbd>Device ON/OFF</kbd>
                        <input id="toggle-one" name="devicetoggle" type="checkbox"  data-toggle="toggle" data-style="ios" data-onstyle="success" data-offstyle="danger" value="{{$deviceStatus or '1'}}" data-on="ON" data-off="OFF" data-size="small" @if(isset($deviceStatus) && $deviceStatus == 1) checked @endif>
                        <input id="facility_id" type="hidden" name="facility_id" value="{{Auth::user()->id}}">
                        <input type="hidden" id="device_status" name="device_status" value="{{$deviceStatus or ''}}">
                        </div>
                        @endrole
                        <br>
                        <br>
                        <div class="row">
                          <div class="col-md-3">
                           <select class="form-control" id="facility_user">
                            <option> Select Facility</option>
                            @if(count($facility) > 0)
                                @foreach($facility as $fac)
                                    <option value="{{$fac->id}}" @if($fac->id == $facility_id) selected @endif>{{$fac->facility_name}}</option>
                                @endforeach

                            @endif
                        </select>
                          </div>
                          <div class="col-md-5"></div>
                          @if($facility_id)
                          @if(count($deviceList)>0)
                          <div class="col-md-4" style="text-align: right;">
                            <b><small>Update App :</small></b> &nbsp;
                              <a onclick="enableDisableupdate({{$facility_id}},1)" ><input type="button" class="btn btn-success" name="Check All" value="Check All" data-toggle="tooltip" data-placement="top" title="Click this Button to Enable Update for all device"></a>

                              <a onclick="enableDisableupdate({{$facility_id}},0)"><input type="button" class="btn btn-danger" name="" value="Uncheck All" data-toggle="tooltip" data-placement="top" title="Click this Button to Disable Update for all device"></a>
                          </div>
                          @endif
                          @endif
                          
                        </div>
                    </div>
                    
                     <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->
                    
                    <!-- /.box-header -->
                    <button id="search_global" style="float: right" class="btn btn-primary">GO</button>
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    @if(Auth::user()->role_id == 1)
                                    <th>Facility Name</th>
                                    @endif
                                    <th>Device ID</th>
                                    <th>Wi-FI Mac Address</th>
                                    <th>App Version / Date</th>
                                    <th>Enable Update</th>
                                    <th>Device Provider</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1) 
                                @if(count($deviceList)>0)
                                @foreach($deviceList as $val)
                               <tr>
                                    <td>{{ $count++ }}</td>
                                    @if(Auth::user()->role_id == 1)
                                    <td>{{ $val->facility_name }}</td>
                                    @endif
                                    <td>{{ $val->device_id }}</td>
                                    <td>{{ $val->imei }}</td>
                                    <td>{{ $val->app_version_date }}</td>
                                    <td><input  id="{{$val->id}}" value='{{$val->update_app}}' type="checkbox" onclick="appupdate(this.value,this.id)" <?php if ($val->update_app == 1): ?> checked
                                        
                                    <?php endif ?>  ></td>
                                    <td>{{ $val->device_provider }}</td>
                                    <td>    
                                        <a href="{{route('device.view', ['id' =>  $val->id ])}}"<i class="fa fa-eye" data-toggle="tooltip" title="View"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{route('device.add', ['id' =>  $val->id ])}}"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="deviceID" id="{{ $val->id  }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script src="<?php echo asset('/'); ?>assets/js/customJS/device.js" type="text/javascript"></script>
<script type="text/javascript">
    $('#facility_user').on('change', '', function (e) {
        var optionSelected = $("option:selected", this);
    var facility_user_id = this.value;
    if (facility_user_id ==  'Select Facility') {
        return false;
    }
    //var base_url = window.location.origin;
    window.location.href = '{{URL::to('devices')}}'+'/'+facility_user_id;
});
$('#search_global').click( function() {
    var search_key = $('#example1_filter>label>input').val();
     var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?search=' + search_key;
          window.history.pushState({path:newurl},'',newurl);
    window.location.href = newurl;

  });
function appupdate($val,$id){
    var val = $val;
    if (val == 0) {
        var update = 1;
    }else{
        update =0;
    }
    $("#"+$id).val(update);
    $.ajax({
                  type:'POST',
                  url:'/appupdate',
                  data: {
        "_token": "{{ csrf_token() }}",
        "id": $id,
        "value": update
        },
                  success:function(data) {
                     $("#msg").html(data.msg);
                  }
               });
}

function enableDisableupdate($facility_id,$val){
    if ($val == 1) {
        var text = 'You want to Enable Update App for all devices';
    }else{
        text = 'You want to Disable Update App for all devices';
    }

        swal({
  title: "Are you sure ? ",
  text: text,
  type: "info",
  showCancelButton: true,
  closeOnConfirm: false,
  showLoaderOnConfirm: true
}, function () {
    $.ajax({
        url     : '/checkuncheckapp/'+$facility_id+'/'+$val,
        method  : 'post',
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success : function(response){
            setTimeout(function () {
                location.reload();
              }, 2000);

                    }
    });
              
});
}
    </script>
@stop