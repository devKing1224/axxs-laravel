@extends('layouts.default')
@section('content') 
<style type="text/css">
    .dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
div.dataTables_paginate {
    margin: 0;
    white-space: nowrap;
    text-align: right;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Activity History List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">User</a></li>
            <li class="active">User Activity History List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    <!--<div class="box-header">
                        <a href="{{action('InmateController@addInmateUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> User</a>
                    </div>-->
                   
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Service Name</th>
                                    <th>Start Date Time</th>
                                    <th>End Date Time</th>
                                    <th>Duration (seconds)</th>
                                    <th>Per Minute Type</th>
                                    <th>Per Minute Rate</th>
                                    <th>Charge ($.$$)</th> 
                                    <th>Free Minutes Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1 )
                                @if(count($inmateActivityInformation)>0)
                                @foreach($inmateActivityInformation as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->start_datetime }}</td>
                                    <td>{{ $val->end_datetime }}</td>
                                    <td>@if(!empty($val->duration)) {{ $val->duration }} @else -- @endif </td>
                                    <td>@if($val->type == 0) Free @elseif($val->type == 1) Facility Rate @elseif($val->type == 2) Premium Rate @else -- @endif </td>
                                    <td>@if($val->type == 0) 0.00 @elseif($val->type == 1) {{ $val->rate }} @elseif($val->type == 2) {{ $val->rate }} @else -- @endif </td>
                                    <td>@if(!empty($val->charges) && $val->end_datetime !== '') {{ $val->charges }} @elseif($val->type == 0) Free @else -- @endif </td>
                                    <td>@if(!empty($val->free_minutes_used)) {{ $val->free_minutes_used }} @elseif($val->type == 0) 0 @else -- @endif </td>
                                </tr>
                                @endforeach
                                @endif
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
      <section class="content-header">
                <h1>Deposit History</h1>
             </section>
             <br>
        <div class="row">
            
             <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    
                                    <th>Transaction ID</th>
                                    <th>Deposit By</th>
                                    <th>Amount</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <!--<th>Action</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1 )
                                @foreach($depo_history as $dep)
                                <tr><td>{{$count++}}</td>
                                    <td>{{$dep->transaction_id}}</td>
                                    <td>{{$dep->client_name}}</td>
                                    <td>{{$dep->amount}}</td>
                                    <td>{{$dep->client_email}}</td>
                                    <td>{{$dep->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>
</div>
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/inmate.js') }}" type="text/javascript"></script>
@stop