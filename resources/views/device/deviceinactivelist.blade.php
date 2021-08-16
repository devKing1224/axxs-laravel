@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inactive Device List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('device.list')}}">Device</a></li>
            <li class="active">Inactive Device List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-left ">
                        <select class="" id="DeviceActiveInactiveCall">
                            <option>Please select any option</option>>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Device ID</th>
                                    <th>IMEI Number</th>
                                    <th>Facility Name</th>
                                    <th>Device Provider</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; if(count($userList)>0){ foreach($userList as $val) { ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $val->device_id; ?></td>
                                    <td><?php echo $val->imei; ?></td>
                                    <td><?php echo $val->facility_id; ?></td>
                                    <td><?php echo $val->device_provider; ?></td>
                                    <td>
                                        <!--<a href="viewinmate/{{$val->id  or ''}}" data-toggle="tooltip" title="View" <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>-->
                                        <a href="javascript:;" token="{{ csrf_token() }}" id="<?php echo $val->id; ?>" data-toggle="tooltip" title="Active" <i class="fa fa-thumbs-up deviceActiveButton"></i>&nbsp;&nbsp;&nbsp;</a>
                                    </td>
                                </tr>
                                <?php $count++; } } ?>
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
@stop