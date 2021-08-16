@extends('layouts.default')
@section('title', '|Whitelisted Email')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<style type="text/css">
       .dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
.dataTables_wrapper .dataTables_processing {
position: absolute;
top: 30%;
left: 50%;
width: 30%;
height: 40px;
margin-left: -20%;
margin-top: -25px;
padding-top: 20px;
text-align: center;
font-size: 1.2em;
background:none;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-check-square"></i> Whitelisted Email 
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Setting</a></li>
            <li class="active">Whitelisted Email</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header text-right">
                          <div class="box-header text-right">
                        <a href="JavaScript:Void(0);" id="add_whurl" class="btn btn-primary "><i class="fa fa-plus" aria-hidden="true"></i> Add Whitelisted Email</a>
                    </div>
                 
                    </div>
                    <!-- Flash message show -->
                   <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message show end-->
                    <!-- Session message show -->
                    @if (Session::has('flash_message'))
                    <div class="alert alert-info" id="successMessage" data-dismiss="alert"><span>{{ Session::get('flash_message') }} </span></div>
                    @endif
                    <!-- Session message end -->

                    <div class="box-body">
                        <table id="wh-email" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="49px;">S.no.</th>
                                    <th>Provider</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
<!-- Modal -->
<div id="wh_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-title">Add Whitelisted Email</h4>
      </div>
      <div class="modal-body">

        <form id="whForm" action="{{url('addwhemail')}}">
          {!! csrf_field() !!}
          <div class="form-group">
            <label for="email">Provider:</label>
            <input type="name" name="provider" class="form-control" id="provider" placeholder="Enter provider eg: Facebook ,Gmail,GED" required>
          </div>
          <div class="form-group">
            <label for="pwd">Email Address:</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email address" required>
          </div>

          <button type="submit" class="btn btn-primary" id="wh_submit">Submit</button>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>
<script src="{{ asset('/assets/js/customJS/whitelistedemail.js') }}" type="text/javascript"></script>

@endsection