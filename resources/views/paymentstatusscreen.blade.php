@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Payment Screen
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Family</a></li>
            <li class="active">Payment screen</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    @if($response == 0)
                        <h2>Your recharge successfully done with amount ${{ $amount }} </h2>
                        <h3>For more details please check account history</h3>
                    @elseif($response == 1)
                        <h2>Your recharge failed done with amount $ {{ $amount }} </h2>
                        <h3>For more details please check account history</h3>
                    @elseif($response == 2)
                        <h2>Your recharge pending with amount $ {{ $amount }} </h2>
                        <h3>For more details please check account history</h3>
                    @endif
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@stop