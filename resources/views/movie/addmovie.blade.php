@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($serviceInfo)) Edit
                @else Add
            @endif Movie
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/services')}}">Movie</a></li>
            <li class="active">@if (isset($serviceInfo)) Edit
                @else Add
                @endif Movie
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content service">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@if (isset($serviceInfo)) Edit
                @else Add
                @endif Movie</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="serviceData">
                        <input type="hidden" class="form-control" name="service_id" value="{{ $serviceInfo->id or '' }}">
                         {{ csrf_field() }}
                        <div class="box-body">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Type <i class="requiredInput text-red">*</i></label>
                                    <select class="form-control" name="type" id="serviceType">
                                        @if (isset($serviceInfo))
                                            <option value="1" {{ $serviceInfo->type == 1 ? 'selected':'' }} >Paid</option>
                                            <option value="0" {{ $serviceInfo->type == 0 ? 'selected':'' }} >Free</option>
                                    <option value="2" {{ $serviceInfo->type == 2 ? 'selected':'' }} >Flat Rate</option>
                                        @else <option value="1">Paid</option> <option value="0">Free</option>
                                        <option value="2">Flat Rate</option> @endif
                                    </select>
                                </div>
                            </div> -->
                            <!--  -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Movie Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="name" value="{{ $serviceInfo->name or '' }}" id="first_name" placeholder="Please enter name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Movie URL<i class="requiredInput text-red">*</i></label>
                                    <textarea class="form-control" id="base_url" name="movie_url" placeholder="Please enter base url">{{ $serviceInfo->base_url or '' }}</textarea>
                          
                                </div>
                            </div>
                            <div class="col-md-6">
<!--                                <div class="form-group">-->
<!--                                    <label>Logo URL<i class="requiredInput text-red">*</i></label>-->
                                    <input type="hidden" class="form-control" id="logo_url" name="logo_url" value="{{ $serviceInfo->logo_url or '' }}">
<!--                                </div>-->
                                <div class="form-group uploadimg">
                                    <label>Logo Image </label >@if(isset($serviceInfo->logo_url))
                                    &ensp;<img class="addimageurl" style="border:.5px #1ab7ea dashed " src="{{ $serviceInfo->logo_url or '' }}" height="35" width="35"> @endif
                                    <input type="file" class="form-control" id="logo_urlfile" name="logo_urlfile" >
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label id="chargelabel">Charge/Hour ($)<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="charge" value="{{ $serviceInfo->charge or '' }}" id="charge" placeholder="Please enter charge">
                                </div>
                            </div> -->
                            <div class="col-md-12 text-info">
                               <div class="form-group"> 
                                   <b> Note: </b>Image size must be less than 100 KB and Extension should be JPG/JPEG/PNG</div>
                               
                           </div>
                           <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea id="popup_msg" name="msg" class="form-control" placeholder="Enter message here">{{ $serviceInfo->msg or '' }}</textarea>
                                    
                                </div>
                            </div> -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="<?php if(isset($serviceInfo)){ ?>serviceEditDataSend<?php } else { ?>movieAddDataSend<?php } ?>">Submit</button>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/movie.js" type="text/javascript"></script>
@stop