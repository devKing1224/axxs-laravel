@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Family
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Family</a></li>
            <li class="active">View Family</li>
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
                    <form role="form" method="" action="javascript:;" id="">
                        <input type="hidden" class="form-control" name="id" value="{{ $familyInfo->id or '' }}">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $familyInfo->first_name or '' }}" id="first_name" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $familyInfo->last_name or '' }}" id="last_name" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $familyInfo->phone or '' }}" id="phone" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $familyInfo->address_line_1 or '' }}" name="address_line_1" id="address_line_1" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 2 </label>
                                    <input type="text" class="form-control" name="address_line_2" value="{{ $familyInfo->address_line_2 or '' }}" id="address_line_2" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State</label>
                                    <input type="text" class="form-control" name="state" value="{{ $familyInfo->state or '' }}" id="state" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ $familyInfo->city or '' }}" id="city" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip</label>
                                    <input type="text" class="form-control" name="zip" value="{{ $familyInfo->zip or '' }}" id="zip" placeholder="" disabled>
                                </div>
                            </div>
                              
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <a href="{{route('family.list', $inmate_id)}}" class="btn btn-primary" >Cancel</a>
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