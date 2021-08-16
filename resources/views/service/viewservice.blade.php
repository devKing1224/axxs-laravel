@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Service
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/services')}}">Service</a></li>
            <li class="active">View Service</li>
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
                    <form role="form" method="post" action="javascript:;">
                        <input type="hidden" class="form-control" name="id" value="{{ $serviceInfo->id or '' }}">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Type</label>
                                    <input type="text" class="form-control" name="inmate_id" value="@if(isset($serviceInfo->type) && $serviceInfo->type == 0) Free  @elseif($serviceInfo->type == 1) Paid @else Flat Rate @endif" id="inmate_id" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Category Name</label>
                                    <input type="text" class="form-control" name="inmate_id" value="{{ $serviceInfo->Service_category_name ? $serviceInfo->Service_category_name : 'None' }}" id="inmate_id" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Name</label>
                                    <input type="text" class="form-control" name="inmate_id" value="{{ $serviceInfo->name or '' }}" id="inmate_id" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="baseURL">Base URL</label>
                                    <textarea class="form-control" id="base_url" name="base_url" placeholder="" disabled>{{ $serviceInfo->base_url or '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logoURL">Logo URL</label>
                                    <textarea class="form-control" id="logo_url" name="logo_url" placeholder="" disabled>{{ $serviceInfo->logo_url or '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Charge/Hour </label>
                                    <input type="text" class="form-control" name="charge" value="{{ $serviceInfo->charge or '' }}" id="charge" placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <a href="{{action('ServiceController@serviceListUI')}}" class="btn btn-primary" >Cancel</a>
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