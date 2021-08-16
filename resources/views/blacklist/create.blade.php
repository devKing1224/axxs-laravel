@extends('layouts.default')

<!--@section('title', '| Add User')-->

@section('content')
<div class="content-wrapper">
    <section class="content-header">
       
        <h1>
             @if (isset($blacklistedinfo)) Edit
                            @else Add
                        @endif Blacklisted Keywords
        </h1>
       

        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Settings</a></li>
            <li class="active">
                @if (isset($blacklistedinfo)) Edit
                      @else Add
                     @endif Blacklisted Keywords
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
                        <h3 class="box-title">@if (isset($blacklistedinfo)) Edit
                            @else Add
                            @endif Blacklisted Keywords
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
                       $blacklistedinfo[0]->id or '' }}">
                <div class="box-body">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="BlacklistedWord">Blacklisted Keywords<i class="requiredInput text-red">*</i></label>
                            <input type="text" class="form-control" name="blacklisted_words" 
                            value= "{{$blacklistedinfo[0]->blacklisted_words or '' }}" id="blacklisted" placeholder="Please enter blacklisted keyword">
                            <input type="hidden" name="addedbyuser_id" @if(Auth::user()->role_id == 2) value="{{ Auth::user()->id }}"> @endif
                        </div>

                    <div class="col-md-12">
                        <a href="{{route('blacklist.create')}}" class="btn btn-primary" >Cancel</a>

                        <button type="submit" class="btn btn-primary" id = "{{ isset($blacklistedinfo) ? 'blacklistedEditDataSend' : 'blacklistedAddDataSend'}}">Submit</button>
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


