@extends('layouts.default')

@section('title', '| Add Role')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class='fa fa-key'></i> Add Roles 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setting</a></li>
            <li class="active">Manage Roles</li>
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

                    {{ Form::open(array('url' => 'roles')) }}
                    <div class="box-body">
                        <div class="form-group">
                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name', null, array('class' => 'form-control')) }}
                        </div>

                        <h5 class="text-blue"><b>Assign Permissions</b></h5>
                        <hr>
                        <div class='form-group'>
                            @foreach ($permissions as $permission)
                            <div class="col-md-4">
                                {{ Form::checkbox('permissions[]',  $permission->id ) }}
                                {{ Form::label($permission->name, ucfirst($permission->name), array('style' => 'margin-right:10px;')) }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
</div>

@endsection