@extends('layouts.default')

<!--@section('title', '| Add User')-->

@section('content')
<div class="content-wrapper">
    <section class="content-header">
       
        <h1>
             @if (isset($preapprovedemail)) Edit
                            @else Add
                        @endif  Email
        </h1>
       

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Pre Approved Email</a></li>
            <li class="active">
                @if (isset($preapprovedemail)) Edit
                      @else Add
                     @endif Email
            </li>
        </ol>
    </section>
      @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                 <div class="box-header with-border">
                        <h3 class="box-title">@if (isset($preapprovedemail)) Edit
                            @else Add
                            @endif Email
                        </h3>
                    </div>
                <div class="box box-primary">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <script>
                        $('.alert-danger').delay(5000).fadeOut('fast');
                    </script>
               
                     <form role="form" method="post" autocomplete="off" action="javascript:;" id="blacklistData">
                    {{ csrf_field() }}
                      <input type="hidden" class="form-control" name="id" value="{{
                       $preapprovedemail[0]->id or '' }}">
                <div class="box-body">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="BlacklistedWord">Add Email<i class="requiredInput text-red">*</i></label>
                            <input type="text" class="form-control" name="email_phone" 
                            value= "{{$preapprovedemail[0]->email_phone or '' }}" id="email_phone" placeholder="Please enter email id">
                        </div>
                           <div class="form-group">
                                <label for="name">Name<i class="requiredInput text-red">*</i></label>
                                <input type="text" class="form-control" name="name" 
                                value= "{{$preapprovedemail[0]->name or '' }}" id="name" placeholder="Please enter name">
                        </div>

                    <div class="col-md-12">
                        <a href="{{action('PreApprovedEmailController@allPreEmail')}}" class="btn btn-primary" >Cancel</a>
                        <button type="submit" class="btn btn-primary" id = "{{ isset($preapprovedemail) ? 'preEmailEditDataSend' : 'AddEmailDataSend'}}">Submit</button>
                    </div>
  

                        </div>                       
                </div>
                   
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('/assets/js/customJS/facility.js') }}" type="text/javascript"></script>
@endsection


