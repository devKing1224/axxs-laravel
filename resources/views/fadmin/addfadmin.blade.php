@extends('layouts.default')
@section('content')   
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($facilityInfo)) Edit
                @else Add
            @endif Facility Admin
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('superadmin.index')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('fadmin.list')}}">Facility Admin List</a></li>
            <li class="active">@if (isset($facilityInfo)) Edit
                @else Add
                @endif Facility
            </li>
        </ol>
    </section>
    
     @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@if (isset($facilityInfo)) Edit
                            @else Add
                            @endif Facility Admin
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="fAData">
                         {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id" value="{{ $facilityInfo->id or '' }}">
                        <div class="box-body">
                             <div class="row no-margin"> <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Facility Admin ID <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="fa_id" value="{{ $facilityInfo->fa_id or $fac_id }}" maxLength = "11" id="fa_id" placeholder="Please enter first id"   readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $facilityInfo->first_name or '' }}" id="first_name" placeholder="Please enter first name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $facilityInfo->last_name or '' }}" id="last_name" placeholder="Please enter last name">
                                </div>
                            </div>
                        </div>
                            <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Total Facility<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="total_facility" value="{{ $facilityInfo->total_facility or '' }}" id="total_facility" placeholder="Please enter total User number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email<i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="email" value="{{ $facilityInfo->email or '' }}" id="email" placeholder="Please enter email" >
                                </div>
                            </div> </div>
                           <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Username <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="username" value="{{ $facilityInfo->username or '' }}" id="username" placeholder="Please enter user name" @isset($facilityInfo) disabled @endisset>
                                </div>
                            </div>
                           
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone </label>
                                    <input type="text" class="form-control" name="phone" value="{{ $facilityInfo->phone or '' }}" placeholder="Please enter phone">
                                </div>
                            </div> </div>
                            <div class="row no-margin">   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Facility Admin Name <i class="requiredInput text-red">*</i></label>
                                    <input type="text" class="form-control" name="fa_name" value="{{ $facilityInfo->fa_name or '' }}" id="a_name" placeholder="Please enter facility admin name" >
                                </div>
                            </div>
                           
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Location </label>
                                    <input type="text" class="form-control" name="location" value="{{ $facilityInfo->location or '' }}" placeholder="Please enter location">
                                </div>
                            </div> </div>
                           <div class="row no-margin">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State </label>
                                    <input type="text" class="form-control" name="state" value="{{ $facilityInfo->state or '' }}" id="state" placeholder="Please enter state">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">City </label>
                                    <input type="text" class="form-control" name="city" value="{{ $facilityInfo->city or '' }}" id="city" placeholder="Please enter city">
                                </div>
                            </div> 
                        </div>
                           <div class="row no-margin"> 
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Zip </label>
                                    <input type="text" class="form-control" name="zip" value="{{ $facilityInfo->zip or '' }}" id="zip" placeholder="Please enter zip">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address Line 1 </label>
                                    <input type="text" class="form-control" value="{{ $facilityInfo->address_line or '' }}" name="address_line" id="address_line" placeholder="Please enter address line ">
                                </div>
                            </div> </div>
                           
                           
                            <div class="row no-margin">
                                  @if(!isset($facilityInfo->id))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password<i class="requiredInput text-red">*</i></label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Please enter password">
                                </div>
                            </div> 
                         @endif
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Organization<i class="requiredInput text-red">*</i></label>
                                    <select  class="form-control js-company-basic-multiple js-states" name="fa_company" id="fa_company">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            
                            
                            </div>
                             
                         <input type="hidden" name="cmpny_id" value="{{ $facilityInfo->company_id or '' }}" id="cmpny_id">   
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                             <div class="col-md-12">
                                <a href="{{action('FacilityController@facilityListUI')}}" class="btn btn-primary" >Cancel</a>
                                <button type="submit" class="btn btn-primary" id="{{ isset($facilityInfo) ? 'facilityAdminEditDataSend' : 'facilityAdminAddDataSend' }}">Submit</button>
                            </div>
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
<script src="{{ asset('/assets/js/customJS/facilityadmin.js') }}" type="text/javascript"></script>
<script src="<?php echo asset('/'); ?>assets/build/js/intlTelInput.js" type="text/javascript"></script>
  <script type="text/javascript">
    $('.js-company-basic-multiple').select2({
    placeholder: "Select Organization",
    allowClear: true
});
    $(document).ready(function(){
    $.ajax({
                    type: "GET",
                    url: '/getcompany_list',
                    success: function( data ) {
                        var cmpny_id = $("#cmpny_id").val();
                        $.each(data.company_data, function(key, value) {
                            if (cmpny_id == value.id) {
                                $('#fa_company').append($('<option selected data-toggle="tooltip" value="'+ value.id +'" title="'+ value.description +'">', { value : value.id }).text(value.name));
                            } else{
                                $('#fa_company').append($('<option data-placement="left" data-toggle="tooltip" value="'+ value.id +'" title="'+ value.description +'">', { value : value.id }).text(value.name));
                            }
                             
                        });
                        $('#assignfacility').modal('show');

                    }
                });


    });



  </script>


@stop