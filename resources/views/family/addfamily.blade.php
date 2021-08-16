@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($familyInfo)) Edit
                @else Add
            @endif Family
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">User</a></li>
            <li class="active">@if (isset($familyInfo)) Edit
                @else Add
                @endif Family
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
                        <h3 class="box-title">@if (isset($familyInfo)) Edit
                            @else Add
                            @endif Family
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="familyData">
                         {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id" value="{{ $familyInfo->id or '' }}">
                        <input type="hidden" class="form-control" name="inmate_id" id="inmate_id" value="{{ $inmate_id or '' }}">
                        <div class="box-body">
                           <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $familyInfo->first_name or '' }}" id="first_name" placeholder="Please enter first name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $familyInfo->last_name or '' }}" id="last_name" placeholder="Please enter last name">
                                </div>
                            </div> </div>
                            @if(!isset($familyInfo->id))
                           <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Username <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="username" value="{{ $familyInfo->usernama or '' }}" id="username" <?php if(isset($familyInfo->usernama)){ ?> disabled="disabled" <?php } ?> placeholder="Please enter user name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password<i class="requiredInput text-red">*</i></label>
                                    <input type="password" class="form-control" name="password" placeholder="Please enter password">
                                </div>
                            </div> </div>
                            @endif
                            <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email <i class="requiredInput text-red">*</i></label>
                                    <input type="email" class="form-control" name="email" value="{{ $familyInfo->email or '' }}" id="email" placeholder="Please enter email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $familyInfo->phone or '' }}" id="phone" placeholder="Please enter phone">
                                </div>
                            </div> </div>
                            <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State </label>
                                    <input type="text" class="form-control" name="state" value="{{ $familyInfo->state or '' }}" id="state" placeholder="Please enter state">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City </label>
                                    <input type="text" class="form-control" name="city" value="{{ $familyInfo->city or '' }}" id="city" placeholder="Please enter city">
                                </div>
                            </div> </div>
                           <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip </label>
                                    <input type="text" class="form-control" name="zip" value="{{ $familyInfo->zip or '' }}" id="zip" placeholder="Please enter zip">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $familyInfo->address_line_1 or '' }}" name="address_line_1" id="address_line_1" placeholder="Please enter address line 1">
                                </div>
                            </div></div>
                          <div class="row no-margin">    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 2 </label>
                                    <input type="text" class="form-control" name="address_line_2" value="{{ $familyInfo->address_line_2 or '' }}" id="address_line_2" placeholder="Please enter address line 2">
                                </div>
                            </div> </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-primary" id="<?php if(isset($familyInfo)){ ?>familyEditDataSend<?php } else { ?>familyAddDataSend<?php } ?>">Submit</button>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/family.js" type="text/javascript"></script>
@stop