@extends('layouts.default')

@section('title', '| Create Permission')

@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">


                    <h1><i class='fa fa-key'></i> Add Permission</h1>
                    <br>
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
                    {{ Form::open(array('url' => 'permissions')) }}

                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', '', array('class' => 'form-control')) }}
                    </div><br>
                    @if(!$roles->isEmpty()) //If no roles exist yet
                    <h4>Assign Permission to Roles</h4>

                    @foreach ($roles as $role) 
                    {{ Form::checkbox('roles[]',  $role->id ) }}
                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                    @endforeach
                    @endif
                    <br>
                    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </section>
</div>

@endsection