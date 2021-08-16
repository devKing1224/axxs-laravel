@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Device
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/devices')}}">Device</a></li>
            <li class="active">View Device</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">View User</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="inmateData">
                        <input type="hidden" class="form-control" name="id" value="{{ $deviceInfo->id or '' }}">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Device ID</label>
                                    <input type="text" class="form-control" name="inmate_id" value="{{ $deviceInfo->device_id or '' }}" id="inmate_id" placeholder="Please enter User ID" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Facility ID<i class="requiredInput" style="color:red;">*</i></label>
                                    <select class="form-control" name="facility_id" id="facility_id" disabled>
                                        <?php if (count($facilityList) > 0) {
                                            foreach ($facilityList as $val) { ?> <option value="<?php echo $val->id; ?>" <?php if(isset($deviceInfo) && $deviceInfo->facility_id == $val->id ){ ?> selected <?php } ?> ><?php echo $val->name; ?></option><?php }
                                        } else { ?> <option>There are no facility list</option>> <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Wi-FI Mac Address<i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $deviceInfo->imei or '' }}" id="first_name" placeholder="Please enter first name" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Device Provider<i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $deviceInfo->device_provider or '' }}" id="last_name" placeholder="Please enter last name" disabled>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Device Password<i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $deviceInfo->device_password or '' }}" id="last_name" placeholder="No Password" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <a href="{{action('DeviceController@deviceListUI')}}" class="btn btn-primary" >Cancel</a>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/inmate.js" type="text/javascript"></script>
@stop