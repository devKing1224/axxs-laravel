@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<meta name="csrf-token" content="<?php echo csrf_token() ?>" />
<style type="text/css">
    .viewnotify {
        color: white;
  -webkit-animation: glowing 1500ms infinite;
  -moz-animation: glowing 1500ms infinite;
  -o-animation: glowing 1500ms infinite;
  animation: glowing 1500ms infinite;
}
@-webkit-keyframes glowing {
  0% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; -webkit-box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
}

@-moz-keyframes glowing {
  0% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; -moz-box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
}

@-o-keyframes glowing {
  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
}

@keyframes glowing {
  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Users Disputed Login Report</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('allusers')}}">User</a></li>
            <li class="active">Users Disputed Login Report</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S. no.</th>
                                   @if(!Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff']))
                                    <th>Facility Name</th>
                                    @endif
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Middle Name</th>
                                    <th>Birthday</th>
                                    <th>Status</th>
                                    <th>Report Time</th>
                                     <td>Active Time</td>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1)
                                @if(count($inmateReportHistoryList)>0) 
                                @foreach($inmateReportHistoryList as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    @if(!Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff']))
                                    <th>{{ $val->facility_name }}</th>
                                    @endif
                                   
                                    <td>{{ ucwords($val->first_name)}} </td>
                                   <td>{{ ucwords($val->last_name)}} </td>
                                   <td>{{ ucwords($val->middle_name)}} </td>
                                   <td>{{ ucwords($val->date_of_birth)}} </td>
                                   
                                   <td>@if($val->status == 1){{ 'New' }} @else {{'Completed'}} @endif</td>
                                    <td>{{ $val->report_time }}</td>
                                    <td>{{ $val->active_time }}</td>
                                    <td>
                                    <button class="btn @if( $val->view == 0)viewnotify @else btn-primary @endif reportResetPassword" type="button" data-toggle="tooltip" title="Reset password"
                                        id="{{$val->id}}"><i class="fa fa-lock"> Reset Password</i></button>
                                        
                                    </td>
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
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/inmate.js') }}" type="text/javascript"></script>
@stop