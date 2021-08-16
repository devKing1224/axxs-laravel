@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Change Password
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Profile</a></li>
            <li class="active">Change password</li>
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
                        <h3 class="box-title">Change password</h3>
                    </div>-->
                    
                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="facilityForgetPasswordData">
                        <input type="hidden" name="facility_user_id" id="facility_user_id" value="{{Auth::user()->id}}">
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Current Password<i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="current_password" value="" id="username" placeholder="Please enter old password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">New Password<i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="new_password" value="" id="first_name" placeholder="Please enter new password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Confirm Password<i class="requiredInput" style="color:red;">*</i></label>
                                    <input type="text" class="form-control" name="confirm_password" value="" id="last_name" placeholder="Please re-enter new password">
                                </div>
                            </div>
                         </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-primary facilityForgetPasswordButton" id="facility_foget_data_send">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
         <div class="box box-primary">
            <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                 </div>
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#freeServiceCharge">Change Profile Picture</a>
                                </h4>
                            </div>
                            <div id="freeServiceCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="UserPic">
                                        {{ csrf_field() }}
                                        <div class="box-body">
                                            <div class="col-md-3">
                                                <div class="">
                                                    <label for="exampleInputEmail1"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="">
                                                    <img class="user-image" src="{{Auth::user()->user_image}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Select new Image</label>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="">
                                                   
                                                    <input type="file" name="user_icon" id="user_icon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendMaxLimit" >Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Image extension should be JPEG,JPG or PNG, Size should not be greater than 100kb.</div>
                            </div>
                        </div>
                    </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/facility.js') }}" type="text/javascript"></script>
@stop