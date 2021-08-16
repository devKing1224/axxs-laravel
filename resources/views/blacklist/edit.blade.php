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
                    {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('blacklist word', 'Blacklist Word') }}
                                {{ Form::text('blacklist_word', null, array('class' => 'form-control')) }}
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
