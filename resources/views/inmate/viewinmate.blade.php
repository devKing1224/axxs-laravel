@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View User
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/allusers')}}">User</a></li>
            <li class="active">View User</li>
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
                        <input type="hidden" class="form-control" name="id" value="{{ $userInfo->id or '' }}">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Facility Name </label>
                                    <input type="text" class="form-control" name="facility_id" @if(isset($userInfo->inmateFacility)) value="{{ $userInfo->inmateFacility->name or '' }}" @endif id="facility_id"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Device ID </label>
                                    <select class="form-control" name="device_id" id="device_id" disabled>
                                        @if(isset($userInfo) && $userInfo->device_id === Null )
                                              <option value="" selected >All devices</option>
                                              @endif
                                        <?php if (count($deviceList) > 0) {
                                            foreach ($deviceList as $val) { ?> <option value="<?php echo $val->id; ?>" <?php if(isset($userInfo) && $userInfo->device_id == $val->id ){ ?> selected <?php } ?> ><?php echo "$val->device_id ($val->device_provider)" ?></option><?php }
                                        } else { ?> <option>There are no device list</option> <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Inmate ID</label>
                                    <input type="text" class="form-control" name="inmate_id" value="{{ $userInfo->inmate_id or '' }}" id="inmate_id"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ $userInfo->username or '' }}" id="username"  disabled>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name </label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $userInfo->first_name or '' }}" id="first_name" placeholder="Please enter first name" disabled>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Middle Name </label>
                                    <input type="text" class="form-control" name="middle_name" value="{{ $userInfo->middle_name or '' }}" id="middle_name"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name </label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $userInfo->last_name or '' }}" id="last_name"  disabled>
                                </div>
                            </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Birthday </label>
                                    <input type="text" class="form-control" name="date_of_birth" value="{{ $userInfo->date_of_birth or '' }}" id="date_of_birth"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $userInfo->phone or '' }}" id="phone"  disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $userInfo->address_line_1 or '' }}" name="address_line_1" id="address_line_1"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 2</label>
                                    <input type="text" class="form-control" name="address_line_2" value="{{ $userInfo->address_line_2 or '' }}" id="address_line_2"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State</label>
                                    <input type="text" class="form-control" name="state" value="{{ $userInfo->state or '' }}" id="state" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ $userInfo->city or '' }}" id="city"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip</label>
                                    <input type="text" class="form-control" name="zip" value="{{ $userInfo->zip or '' }}" id="zip" disabled>
                                </div>
                            </div>
                            @if(isset($userInfo->inmateEmail))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User Email Id</label>
                                    <input type="text" class="form-control" name="emailid" value="{{$userInfo->inmateEmail->email or '' }}"  disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User Password</label>
                                    <input type="text" class="form-control" name="password" value="{{ $userInfo->inmateEmail->password or '' }}" id="password" disabled>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <a href="{{action('InmateController@inmateListUI')}}" class="btn btn-primary" >Cancel</a>
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
<script src="{{ asset('/assets/js/customJS/inmate.js') }}" type="text/javascript"></script>
@stop