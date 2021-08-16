@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($deviceInfo)) Edit
            @else Add
            @endif Device
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('device.list')}}">Device</a></li>
            <li class="active">@if (isset($deviceInfo)) Edit
                @else Add
                @endif Device
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@if (isset($deviceInfo)) Edit
                            @else Add
                            @endif Device</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="deviceData">
                         {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id" value="{{ $deviceInfo->id or '' }}">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Facility Name<i class="requiredInput text-red">*</i></label>
                                    <select class="form-control" name="facility_id" id="facility_id">
                                        <?php if (isset($facilityList) && count($facilityList) > 0) {
                                            foreach ($facilityList as $val) { ?>
                                                <option value="<?php echo $val->id; ?>" <?php if (isset($deviceInfo) && $deviceInfo->facility_id == $val->id) { ?> selected <?php } ?> ><?php echo $val->facility_name; ?></option>
    <?php }
} else { ?> <option value="0">There are no list for facilitys</option> <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Device ID <i class="requiredInput text-red">*(alphanumeric)</i></label>
                                    <input type="text" class="form-control" name="device_id" value="{{ $deviceInfo->device_id or '' }}" @if(Auth::user()->role_id == 2 && isset($deviceInfo->device_id)) disabled @endif id="device_id" placeholder="Please enter name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Wi-FI Mac Address <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="imei" value="{{ $deviceInfo->imei or '' }}" id="imei" placeholder="Please enter name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Device Provider<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="device_provider" value="{{ $deviceInfo->device_provider or '' }}" id="device_provoder" placeholder="Please enter device provider name">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="<?php if (isset($deviceInfo)) { ?>deviceEditDataSend<?php } else { ?>deviceAddDataSend<?php } ?>">Submit</button>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/device.js" type="text/javascript"></script>
@stop