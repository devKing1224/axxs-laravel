@extends('layouts.default')

@section('title', '| Edit Permission')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">

                    
                    <h1><i class='fa fa-key'></i> Edit {{$permission->name}}</h1>
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
                    {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

                    <div class="form-group">
                        {{ Form::label('name', 'Permission Name') }}
                        {{ Form::text('name', null, array('class' => 'form-control')) }}
                    </div>
                    <br>
                    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </section>
</div>

@endsection