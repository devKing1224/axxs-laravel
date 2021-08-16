@extends('layouts.default')
@section('content')   

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if (isset($facilityInfo)) Edit
                @else Add
            @endif Organization
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('superadmin.index')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('fadmin.list')}}">Organization</a></li>
            <li class="active">@if (isset($company)) Edit
                @else Add
                @endif Organization
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
                            @endif Organization
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="companyData">
                         {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id" value="{{ $company->id or '' }}">
                        <div class="box-body">
                             <div class="row no-margin"> <div class="col-md-6">
                                <div class="col-md-3"></div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Organization Name <i class="requiredInput text-red">*</i></label>
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    
                                    <input type="text" class="form-control" name="name" value="{{ $company->name or '' }}" id="name" placeholder="Enter Organization Name">
                                </div>
                            </div>
                            
                        </div>
                            <div class="row no-margin">  <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-3"></div>
                                    <label for="exampleInputEmail1">Organization Description<i class="requiredInput text-red">*</i></label>
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                   <textarea name="description" class="form-control" id="description">{{ $company->description or '' }}</textarea>
                                </div>
                            </div> </div>   
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                             <div class="col-md-12">
                                <a href="{{action('CompanyController@addCompanyUI')}}" class="btn btn-primary" >Cancel</a>
                                <button type="submit" class="btn btn-primary" id="{{ isset($company) ? 'orgEditDataSend' : 'companyAddDataSend' }}">Submit</button>
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
  <script>
   $('#companyAddDataSend').click(function () {

           var form = $('#companyData');
           form.validate({
               errorElement: 'span',
               errorClass: 'form-error',
               highlight: function (element, errorClass, validClass) {

                   $(element).closest('.form-group').addClass("has-error");
               },
               unhighlight: function (element, errorClass, validClass) {
                   $(element).closest('.form-group').removeClass("has-error");
               },
               rules: {
                   name: 'required',
                   description: 'required'
                   
               },
               messages: {
                   name: 'Organization name is required.',
                   description: 'Please enter organization description.',
               }
           });
           if (form.valid() === true) {
               // if (telInput.intlTelInput("isValidNumber")) {
                   $.ajax({
                       type: 'post',
                       url:  'register_company',
                       data: $('#companyData').serialize(),
                       dataType: 'json',
                       success: function (result) {
                           if (result.code == 201) {
                               
                               window.location.href = baseURL + 'company_list';
                               return false;
                           } else if (result.Code === 400) {
                               swal('Error!!', result.Message, 'error');
                               return false;
                           }
                       },
                       error: function (jqXHR, exception) {
                           console.log('jqXHR' + jqXHR);
                           console.log('exception' + exception);
                           swal('Error!!', exception, 'error');
                       }
                   });
               // } else {
               //     swal('Error!!', 'Enter a valid Twilio number', 'error');
               // }
           }
       }); 

//edit oragnization data
$('#orgEditDataSend').on('click', function () {

        var form = $('#companyData');
        
        form.validate({
            errorElement: 'span',
            errorClass: 'form-error',
            highlight: function (element) {

                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            rules: {
                   name: 'required',
                   description: 'required'
                   
               },
               messages: {
                   name: 'Organization name is required.',
                   description: 'Please enter Organization description.',
               }
        });
        if (form.valid() === true) {
            $.ajax({
                type: 'post',
                url: baseURL + 'updateorganization',
                data: $('#companyData').serialize(),
                dataType: 'json',
                success: function (result) { 
                    if (result.Code === 200) {
                        window.location.href = baseURL + 'company_list';
                        return false;
                    } else if (result.Code === 400) {
                        swal('Error!!', result.Message, 'error');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
        }
    });
  </script>
@stop