@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Family User
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Family</a></li>
            <li class="active">View Family User</li>
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
                    <form role="form" method="post" action="javascript:;" >
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User ID</label>
                                    <input type="text" class="form-control" name="inmate_id" value="{{ $familyInmateInfo->inmate_id }}" id="inmate_id" placeholder="User ID" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User Name </label>
                                    <input type="text" class="form-control" name="name" value="{{ $familyInmateInfo->first_name.' '.$familyInmateInfo->last_name }}" id="name" placeholder="User full name" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $familyInmateInfo->phone }}" id="phone" placeholder="User phone number" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Available Balance ($)</label>
                                    <input type="text" class="form-control" value="{{ $familyInmateInfo->balance or '' }}" name="balance" id="balance" placeholder="User available balance" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a href="#" class="btn btn-primary paymentRechargeButton" data-toggle="modal" 
                            @if($cpc_funding == true)data-target="#cpc_popup" @else data-target="#inmateAccountRecharge"@endif >Recharge User Account </a>
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

<!-- Payment Form section -->
<div id="inmateAccountRecharge"  data-backdrop="static" class="modal fade example-modal" role="dialog"> 
    <div class="modal-content modal-dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="font-size:-webkit-xxx-large;" id="patientImageUploadModalCancelBtn">&times;</button>
            <h4 class="modal-title">User account recharge</h4>
        </div>
        <div class="modal-body">
            <form method="post" action="{{route('inmatepayment.screen')}}" >
                {{csrf_field()}}
                <div class="row">                        
                    <div class="col-xs-12">
                        <div class="upload-profile-popup clearfix">                               
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Amount</label>
                                        <input type="text" class="form-control" name="amount" value="" id="amount" placeholder="Please enter recharge amount">
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="btn btn-primary" id="check" value="Submit" type="submit"> 
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div> 
            </form>
            <!-- This Screen FOr Card Details FOr Patient-->
        </div>
    </div>
</div>

<!--cpc pop up modal-->
<!-- Modal -->
<div class="modal fade" id="cpc_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span style="color: red">Please add funds on CPC site.</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('/assets/js/customJS/family.js') }}" type="text/javascript"></script>
@stop