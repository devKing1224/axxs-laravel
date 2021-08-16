@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Facility
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/facilities')}}">Facility</a></li>
            <li class="active">View Facility</li>
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
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">facility ID</label>
                                    <input type="text" class="form-control" name="facility_id" value="{{ $facilityInfo->facility_id or '' }}" id="facility_id" placeholder="Please enter facility ID" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name </label>
                                    <input type="text" class="form-control" name="name" value="{{ $facilityInfo->name or '' }}" id="first_name" placeholder="Please enter name" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Total User</label>
                                    <input type="text" class="form-control" name="total_inmate" value="{{ $facilityInfo->total_inmate or '' }}" id="last_name" placeholder="Please enter total User" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="text" class="form-control" name="email" value="{{ $facilityInfo->email or '' }}" id="phone" placeholder="Please enter email" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">User Name <i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="username" value="{{ $facilityInfo->username or '' }}" id="username" placeholder="Please enter user name" disabled="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Phone</label>
                                    <input type="email" class="form-control" name="phone" value="{{ $facilityInfo->phone or '' }}" id="email" placeholder="Please enter phone" disabled>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Twilio Number</label>
                                    <input type="email" class="form-control" name="phone" value="{{ $facilityInfo->twilio_number or '' }}" id="email" placeholder="Please enter twilio number" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $facilityInfo->address_line_1 or '' }}" name="address_line_1" id="address_line_1" placeholder="Please enter address line 1" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 2 </label>
                                    <input type="text" class="form-control" name="address_line_2" value="{{ $facilityInfo->address_line_2 or '' }}" id="address_line_2" placeholder="Please enter address line 2" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State </label>
                                    <input type="text" class="form-control" name="state" value="{{ $facilityInfo->state or '' }}" id="state" placeholder="Please enter state" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ $facilityInfo->city or '' }}" id="city" placeholder="Please enter city" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip </label>
                                    <input type="text" class="form-control" name="zip" value="{{ $facilityInfo->zip or '' }}" id="zip" placeholder="Please enter zip" disabled>
                                </div>
                            </div>
                            
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tablet Charge </label>
                                    <input type="text" class="form-control" name="tablet_charge" value="{{ $facilityInfo->tablet_charge or '' }}" id="tablet_charge" placeholder="Please enter tablet_charge" disabled>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email Charge </label>
                                    <input type="text" class="form-control" name="email_charges" value="{{ $facilityInfo->email_charges or '' }}" id="email_charges" placeholder="Please enter email charges" disabled>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">SMS Charge </label>
                                    <input type="text" class="form-control" name="sms_charges" value="{{ $facilityInfo->sms_charges or '' }}" id="sms_charges" placeholder="Please enter sms charges" disabled>
                                </div>
                            </div>
                                @if($maxlimits) 
                          
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email Limit</label>
                                        <input type="text" class="form-control" value="{{ $maxlimits->max_email }}" id="zip" placeholder="" disabled>
                                    </div>
                                 </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Contact Number Limit</label>
                                        <input type="text" class="form-control" value="{{ $maxlimits->max_phone }}" id="zip" placeholder="" disabled>
                                    </div>
                                 </div>
                            @endif
                        </div>
                        
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <a href="{{action('FacilityController@facilityListUI')}}" class="btn btn-primary" >Cancel</a>
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