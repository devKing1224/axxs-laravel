@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($userInfo)) Edit
            @else Add
            @endif User
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/allusers')}}">User</a></li>
            <li class="active">@if (isset($userInfo)) Edit
                @else Add
                @endif User
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
                        <h3 class="box-title">@if (isset($userInfo)) Edit
                            @else Add
                            @endif User</h3>
                    </div>

                    <!-- Flash message -->
                    <div class="alert alert-danger" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->

                    <!-- /.box-header -->
                    <!-- form start -->

                    <form role="form" method="post" action="javascript:;" id="inmateData">
                         {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id" value="{{ $userInfo->id or '' }}">
                        <div class="box-body">
                           <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Facility Name <i class="requiredInput text-red">*</i></label>
                                    <select class="form-control" name="admin_id" id="admin_id">
                                        @role('Super Admin')
                                        <option value="" disabled="" selected="">Select Facility</option>
                                        @endrole
                                        <?php if (count($facilityList) > 0) {
                                            foreach ($facilityList as $val) {
                                                ?> <option totaluser="{{$val->total_inmate}}" value="<?php echo $val->facility_user_id; ?>" <?php if (isset($userInfo) && $userInfo->admin_id == $val->facility_user_id) { ?> selected <?php } ?> ><?php echo $val->facility_name; ?></option><?php }
                                } else {
                                    ?> <option>There are no facility list</option> <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Device ID <i class="requiredInput text-red">*</i></label>
                                    <div id='primaryDropdown'>
                                       
                                        <select class="form-control primaryDropdown" name="device_id" id="device_id">
                                        
                                            @if (count($alldevices) > 0)
                                            
                                              
                                              <option value ="">All Devices</option>
                                                @foreach ($alldevices as $val)
                                                
                                                     <option value="{{ $val->id }}" @if(isset($userInfo) && $userInfo->device_id == $val->id ) selected @endif >{{ $val->device_id }}({{ $val->device_provider }})</option>
                                                    
                                                @endforeach
                                            @elseif(isset($edit) && $edit ==1)
                                            <option value="">All Devices</option>
                                            @else
                                            <option>Select Facility First</option>
                                            
                                            @endif
                                          
                                        </select>
                                    </div>
                                    <div id='secondDropdown'></div>
                                </div>
                            </div> </div>
                           <div class="row no-margin">  <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Inmate ID <i class="requiredInput text-red">*(numeric)</i></label>
                                    <input type="text" class="form-control" name="inmate_id" value="{{ $userInfo->inmate_id or '' }}" id="inmate_id" <?php if (isset($userInfo->inmate_id)) { ?> disabled="disabled" <?php } ?> placeholder="Please enter inmate ID">
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Username/PIN <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="username" value="{{ $userInfo->username or '' }}" id="username" <?php if (isset($userInfo->username)) { ?> disabled="disabled" <?php } ?>placeholder="Please enter user name">
                                </div>
                            </div> </div>
                           <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $userInfo->first_name or '' }}" id="first_name" placeholder="Please enter first name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" value="{{ $userInfo->middle_name or '' }}" id="middle_name" placeholder="Please enter middle name">
                                </div>
                            </div></div>
                           <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $userInfo->last_name or '' }}" id="last_name" placeholder="Please enter last name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Birthday<i class="requiredInput text-red">*</i></label>
                                   <input type="date" name="date_of_birth" class="form-control" @if(isset($userInfo) && $userInfo->date_of_birth) value="{{ date($userInfo->date_of_birth) }}" @endif>
                                
                                </div>
                            </div></div>

                         <div class="row no-margin">    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $userInfo->phone or '' }}" id="phone" placeholder="Please enter phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State </label>
                                    <input type="text" class="form-control" name="state" value="{{ $userInfo->state or '' }}" id="state" placeholder="Please enter state">
                                </div>
                            </div></div>
                         <div class="row no-margin">    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City </label>
                                    <input type="text" class="form-control" name="city" value="{{ $userInfo->city or '' }}" id="city" placeholder="Please enter city">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip </label>
                                    <input type="text" class="form-control" name="zip" value="{{ $userInfo->zip or '' }}" id="zip" placeholder="Please enter zip">
                                </div>
                            </div></div>
                          <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $userInfo->address_line_1 or '' }}" name="address_line_1" id="address_line_1" placeholder="Please enter address line 1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 2 </label>
                                    <input type="text" class="form-control" name="address_line_2" value="{{ $userInfo->address_line_2 or '' }}" id="address_line_2" placeholder="Please enter address line 2">
                                </div>
                            </div> </div>
                            
                            <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Location </label>
                                    <input type="text" class="form-control" value="{{ $userInfo->location or '' }}" name="location" id="location" placeholder="Please enter location">
                                </div>
                            </div>
                            </div>
                            
                            @if(isset($userInfo->email))
                          <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User Email ID </label>
                                    <input type="text" class="form-control" value="{{ $userInfo->email or '' }}"  id="inmateemail" placeholder="Userid@theaxxstablet.com" disabled="disabled">
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User Password</label>
                                    <input type="text" class="form-control" name="Userpassword" value="{{ $Inmateemail->password or '' }}" id="inmatepassword" placeholder="Please enter password of email ID">
                                </div>
                            </div> --> </div>
                            @endif
                            @can('Create Email Address')
                            @if(isset($userInfo) && !isset($userInfo->email))
                            <div class="row no-margin">   
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="exampleInputEmail1">Create User Email Address<small class="text-muted">(Email Address is not created yet)</small></label>
                                   
                                        
                                        <input type="hidden" id="user_id" name="user_id" value="{{$userInfo->id}}">
                                        <input type="hidden" id="in_id" name="username" value="{{$userInfo->username}}">
                                        <input type="hidden" name="admin_id" id="admin_id1" value="{{$userInfo->admin_id}}">
                                        <input type="button" id="gen_email" class="btn btn-primary form-control" value="Click to generate email address" >
                                    
                                    
                                    </div>
                                </div>
                             </div>
                             @endif
                                
                            @endcan
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <div class="col-md-12">
                                <a href="{{ route('inmate.inmatelist') }}" class="btn btn-primary">Cancel</a>
                                <button type="submit" class="btn btn-primary sendInmateData" id="<?php if (isset($userInfo)) { ?>inmateEditDataSend<?php } else { ?>inmateAddDataSend<?php } ?>">Submit</button>
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
<script src="{{ asset('/assets/js/customJS/inmate.js') }}" type="text/javascript"></script>
@stop