@extends('layouts.default')

<!--@section('title', '| Add User')-->

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class='fa fa-pencil'></i> Edit Staff</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/staffs')}}">Facility Staff</a></li>
            <li class="active"> Edit Staff
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">

                    
                    {{ Form::model($user, array('route' => array('staffs.update', $user->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}
                    <div class="box-body">
                        <div class="row no-margin">
                            @if(!Auth::user()->hasRole('Facility Admin'))
                            <div class="col-md-6">
                                <div class='form-group'>
                                    {{ Form::label('facility', 'Facility') }}<i class="requiredInput text-red">*</i>
                                    <select class="form-control " name="facility" >

                                        @if (count($facilities) > 0)
                                        @foreach ($facilities as $val)
                                        <option value="{{ $val->facility_user_id }}" @if(isset($user) && $user->admin_id == $val->facility_user_id ) selected @endif >{{ $val->name }}</option>                                                  
                                        @endforeach
                                        @else
                                        <option>Null</option>
                                        @endif

                                    </select>
                                    <p class="text-red">{{ $errors->first('facility') }}</p>
                                    <!--{!! Form::select('facility', $facilities, null,['placeholder' => 'Null', 'class' => 'form-control']) !!}-->
                                </div>
                            </div>
                            @else
                            {{ Form::hidden('facility', Auth::user()->id, array('class' => 'form-control')) }}
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('first_name', 'First Name') }}<i class="requiredInput text-red">*</i>
                                    {{ Form::text('first_name', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('first_name') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('last_name', 'Last Name') }}<i class="requiredInput text-red">*</i>
                                    {{ Form::text('last_name', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('last_name') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('username', 'Username') }}<i class="requiredInput text-red">*</i>
                                    {{ Form::text('username', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('username') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('email', 'Email') }}<i class="requiredInput text-red">*</i>
                                    {{ Form::email('email', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('email') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('phone', 'Phone') }}
                                    {{ Form::text('phone', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('phone') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address_line_1', 'Address line 1') }}
                                    {{ Form::text('address_line_1', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('address_line_1') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address_line_2', 'Address line 2') }}
                                    {{ Form::text('address_line_2', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('address_line_2') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('city', 'City') }}
                                    {{ Form::text('city', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('city') }}</p>
                                </div>
                            </div>                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('state', 'State') }}
                                    {{ Form::text('state', null, array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('state') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('zip', 'Zip') }}
                                {{ Form::text('zip', null, array('class' => 'form-control')) }}
                                <p class="text-red">{{ $errors->first('zip') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('password', 'Password') }}<i class="requiredInput text-red">*</i><br>
                                    {{ Form::password('password', array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('password') }}</p>

                                </div>
                            </div>
                        <div class="row no-margin">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('password', 'Confirm Password') }}<i class="requiredInput text-red">*</i><br>
                                    {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                                    <p class="text-red">{{ $errors->first('password_confirmation') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer text-right">
                            <a href="{{action('StaffController@index')}}" class="btn btn-primary">Cancel</a>
                            {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                        </div>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $('.text-red').delay(5000).fadeOut('slow');
</script>
@endsection
