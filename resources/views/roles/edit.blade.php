@extends('layouts.default')
@section('title', '| Edit Role')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class='fa fa-pencil'></i> Edit Roles : {{$role->name}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Setting</a></li>
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


                    {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}
                    <div class="box-body">
                        <div class="form-group">
                            {{ Form::label('name', 'Role Name') }}
                            @if($role->name == 'Super Admin' || $role->name == 'Family Admin' || $role->name == 'Facility Admin' || $role->name == 'Inmate' || $role->name == 'Facility Staff')
                            {{ Form::text('name', null, array('class' => 'form-control', 'readonly'=>'readonly')) }}
                            @else 
                            {{ Form::text('name', null, array('class' => 'form-control')) }}
                            @endif
                        </div>

                        <h5 class="text-blue"><b>Assign Permissions</b></h5>
                        <hr>
                        <div class='form-group'>
                            @foreach ($permissions as $permission)
                            <div class="col-md-4">
                                {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
                                {{Form::label($permission->name, ucfirst($permission->name), array('style' => 'margin-right:10px;')) }}<br>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="box-footer text-right">
                        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
                    </div>
                    {{ Form::close() }}    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection