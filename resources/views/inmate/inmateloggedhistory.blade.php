@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Logged List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/allusers')}}">User</a></li>
            <li class="active">Logged History</li>
        </ol>
    </section>
 
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Login date Time</th>
                                    <th>Logout date Time</th>
                                    <th>Charges($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1)
                                @if(count($inmateLoggedList)>0)
                                @foreach($inmateLoggedList as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $val->start_date_time }}</td>
                                    <td>{{ $val->end_date_time }}</td>
                                    <td>{{ $val->charges }}</td>
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
<script src="{{ asset('/assets/js/customJS/facility.js') }}" type="text/javascript"></script>
@stop