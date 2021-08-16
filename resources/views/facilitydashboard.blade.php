@extends('layouts.default')
@section('content')
<!-- Content Wrapper. Contains page content -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Toaster  -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/toaster/toaster.css')}}">
<script src="{{ asset('assets/toaster/toaster.js')}}"></script>
<style>
    .ui-autocomplete { 
        cursor:pointer; 
        height:120px; 
        overflow-y:scroll;
    }
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }  

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{$roleInfo->name}} Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
             <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ count($userList) }}<sup style="font-size: 20px"></sup></h3>

                        <p>User</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{action('InmateController@inmateListUI')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{count($freeServiceInfo) }}</h3>
                        <p>Free Services</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{action('ServiceController@serviceListUI')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{count($paidServiceInfo) }}</h3>

                        <p>Paid services</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{action('ServiceController@serviceListUI')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{count($deviceInfo) }}</h3>
                        <p>Devices Allot</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{action('DeviceController@deviceListUI')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
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
                                    <a data-toggle="collapse" href="#freeServiceCharge">Set Maximum Number Of Contact For All Users</a>
                                </h4>
                            </div>
                            <div id="freeServiceCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="maxContact">
                                        
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Limit for Email Addresses</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                     <input type="hidden" class="form-control" name="user_id" value="{{ $auth_id }}">
                                                    @if(!$maxcontactInfo)
                                                    <input type="text" class="form-control" name="max_email" value="" placeholder="Please enter maximum limit of Email Ids">
                                                    @else
                                                     <input type="text" class="form-control" name="max_email" value="{{ $maxcontactInfo->max_email }}" placeholder="Please enter maximum limit of Email Ids">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Limit for Text Numbers</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    @if(!$maxcontactInfo)
                                                    <input type="text" class="form-control" name="max_phone" value="" placeholder="Please enter maximum limit for Number">
                                                    @else
                                                    <input type="text" class="form-control" name="max_phone" value="{{ $maxcontactInfo->max_phone }}" placeholder="Please enter maximum limit for Number">
                                                    @endif
                                                
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendMaxLimit" >Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">User can ask only number of email and contact number which you will set here. </div>
                            </div>
                        </div>
                    </div>
        </div>
         @role('Facility Admin')
                        <div style="float: left;">
                        <kbd>Device ON/OFF</kbd>
                        <input id="toggle-one" name="devicetoggle" type="checkbox"  data-toggle="toggle" data-style="ios" data-onstyle="success" data-offstyle="danger" value="{{$deviceStatus or '1'}}" data-on="ON" data-off="OFF" data-size="small" @if(isset($deviceStatus) && $deviceStatus == 1) checked @endif>
                        <input id="facility_id" type="hidden" name="facility_id" value="{{Auth::user()->id}}">
                        <input type="hidden" id="device_status" name="device_status" value="{{$deviceStatus or ''}}">
                        </div>
                        @endrole
    </section>
    <!-- /.content -->
</div>

<script src="{{ asset('/assets/js/customJS/superadmin.js') }}" type="text/javascript"></script>
@stop
