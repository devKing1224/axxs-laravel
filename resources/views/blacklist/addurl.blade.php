@extends('layouts.default')

<!--@section('title', '| Add User')-->

@section('content')
<div class="content-wrapper">
    <section class="content-header">
       
        <h1>
             @if (isset($urlinfo)) Edit
                            @else Add
                        @endif URL
        </h1>
       

        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Settings</a></li>
            <li class="active">
                @if (isset($urlinfo)) Edit
                      @else Add
                     @endif URL
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
                        <h3 class="box-title">@if (isset($urlinfo)) Edit
                            @else Add
                            @endif URL
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
               
                     <form role="form" method="post" autocomplete="off" action="javascript:;" id="urlData">
                    {{ csrf_field() }}
                      <input type="hidden" class="form-control" name="id" value="{{
                       $urlinfo[0]->id or '' }}">
                <div class="box-body">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="url">Url<i class="requiredInput text-red">*</i></label>
                            <textarea placeholder="Please enter url" class="form-control" name="url" rows="5" id="url">{{$urlinfo[0]->url or '' }}</textarea>
                            <!-- <input type="text" class="form-control" name="url" 
                            value= "{{$urlinfo[0]->url or '' }}" id="url" placeholder="Please enter url"> -->
                        </div>

                    <div class="col-md-12">
                        <a href="{{route('urllist')}}" class="btn btn-primary" >Cancel</a>

                        <button type="submit" class="btn btn-primary" id = "{{ isset($urlinfo) ? 'urlEditDataSend' : 'urlAddDataSend'}}">Submit</button>
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


