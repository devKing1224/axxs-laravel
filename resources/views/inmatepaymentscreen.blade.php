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
                    <form role="form" method="post" action="https://demo.globalgatewaye4.firstdata.com/payment" >
                        <input name="x_login" value="{{ $paymentInformationArray['login'] }}" type="hidden">
                        <input name="x_amount" value="{{ $paymentInformationArray['amount'] }}" type="hidden"> 
                        <input name="x_fp_sequence" value="{{ $paymentInformationArray['sequence'] }}" type="hidden"> 
                        <input name="x_fp_timestamp" value="{{ $paymentInformationArray['time'] }}" type="hidden"> 
                        <input name="x_fp_hash" value="{{ $paymentInformationArray['hash'] }}" type="hidden"> 
                        <input name="x_show_form" value="PAYMENT_FORM" type="hidden"> 
                        <input name="x_relay_response" value="TRUE" type="hidden"> 
                        <input name="x_relay_url" value="" type="hidden"> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount</label>
                                <input type="text" class="form-control" name="" value="{{ $paymentInformationArray['amount'] }}" disabled="disabled">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <input type="submit" class="btn btn-primary" value="Make Payment">
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
<script src="{{ asset('/assets/js/customJS/family.js') }}" type="text/javascript"></script>
@stop