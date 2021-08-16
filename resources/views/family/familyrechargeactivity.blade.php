@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Family recharge activity</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Family</a></li>
            <li class="active">Family recharge activity history</li>
        </ol>
    </section>
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
                                    <th>S. No.</th>
                                    <th>Payment Status</th>
                                    <th>Transaction Id</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count=1)
                                @if(count($payemtnInformation)>0)
                                @foreach($payemtnInformation as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $val->payment_status }}</td>
                                    <td>{{ $val->transaction_id }}</td>
                                    <td>{{ $val->client_email }}</td>
                                    <td>{{ $val->amount }}</td>
                                    <td>{{ $val->created_at }}</td>
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
<script src="{{ asset('/assets/js/customJS/service.js') }}" type="text/javascript"></script>
@stop