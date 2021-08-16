@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Activity List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">User</a></li>
            <li class="active">User Activity List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <a href="{{action('InmateController@addInmateUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> User</a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Activity Date</th>
                                    <th>Type</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Charge ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($userList)>0)
                                @foreach($userList as $val)
                               <tr>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->date }}</td>
                                    <td>{{ $val->type }}</td>
                                    <td>{{ $val->start_time }}</td>
                                    <td>{{ $val->end_time }}</td>
                                    <td>{{ $val->charge }}</td>
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