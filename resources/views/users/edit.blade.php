@extends('layouts.default')

<!--@section('title', '| Add User')-->

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class='fa fa-pencil'></i> Edit Specialist</h1>
            <ol class="breadcrumb">
                <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{url('configuration')}}">Settings</a></li>
                <li class="active"> Edit Specialist
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
                        {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('first_name', 'First Name') }}
                                    {{ Form::text('first_name', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('last_name', 'Last Name') }}
                                    {{ Form::text('last_name', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('username', 'Username') }}
                                    {{ Form::text('username', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('email', 'Email') }}
                                    {{ Form::email('email', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address_line_1', 'Address line 1') }}
                                    {{ Form::text('address_line_1', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address_line_2', 'Address line 2') }}
                                    {{ Form::text('address_line_2', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('city', 'City') }}
                                    {{ Form::text('city', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('state', 'State') }}
                                    {{ Form::text('state', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('zip', 'Zip') }}
                                    {{ Form::text('zip', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group'>
                                    {{ Form::label('role', 'Roles') }}
                                    {!! Form::select('role_id', $roles, null,['placeholder' => 'Null', 'class' => 'form-control']) !!}

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('password', 'Password') }}<br>
                                    {{ Form::password('password', array('class' => 'form-control')) }}

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('password', 'Confirm Password') }}<br>
                                    {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

                                </div>
                            </div>

                            <div class="box-footer text-right">
                                <a href="{{action('UserController@index')}}" class="btn btn-primary">Cancel</a>
                                {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
