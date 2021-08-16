@extends('layouts.default')

<!--@section('title', '| Add User')-->

@section('content')
<style>
    .required:after{ 
        content:'*'; 
        color:red; 
        padding-left:5px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class='fa fa-user-plus'></i> Add Specialist</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Settings</a></li>
            <li class="active"> Add Specialist
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
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
                    {{ Form::open(array('url' => 'users', 'role' => 'form')) }}
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('first_name', 'First Name',array('class' => 'required') ) }}
                                {{ Form::text('first_name', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('last_name', 'Last Name',array('class' => 'required')) }}
                                {{ Form::text('last_name', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('username', 'Username',array('class' => 'required')) }}
                                {{ Form::text('username', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('email', 'Email',array('class' => 'required')) }}
                                {{ Form::email('email', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('address_line_1', 'Address line 1') }}
                                {{ Form::text('address_line_1', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('address_line_2', 'Address line 2') }}
                                {{ Form::text('address_line_2', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('city', 'City') }}
                                {{ Form::text('city', '', array('class' => 'form-control')) }}
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('state', 'State') }}
                                {{ Form::text('state', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('zip', 'Zip') }}
                                {{ Form::text('zip', '', array('class' => 'form-control')) }}
                            </div>
                        </div>
                         
                        <div class="col-md-6">
                            <div class='form-group'>
                                {{ Form::label('role', 'Roles') }}
                                {{ Form::select('role_id', $roles, '',['placeholder' => 'Null', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('password', 'Password',array('class' => 'required')) }}<br>
                                {{ Form::password('password', array('class' => 'form-control')) }}

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('password', 'Confirm Password') }}<br>
                                {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                          <a href="{{action('UserController@index')}}" class="btn btn-primary">Cancel</a>
                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                    </div>
                    {{ Form::close() }}


                </div>
            </div>
        </div>
    </section>
</div>
@endsection