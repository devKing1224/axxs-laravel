@extends('layouts.default')

@section('title', '| Permissions')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-key"></i> List Permissions
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Setting</a></li>
            <li class="active">List Permissions</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ URL::to('permissions/create') }}" class="btn btn-primary hidden"><i class="fa fa-plus" aria-hidden="true"></i> Add Permission</a>
                    </div>
                    <!-- Flash message show -->
                    <div class="alert alert-error" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message show end-->
                    <!-- Session message show -->
                    @if (Session::has('flash_message'))
                    <div class="alert alert-info" id="successMessage" data-dismiss="alert"><span>{{ Session::get('flash_message') }} </span></div>
                    @endif
                    <!-- Session message end -->
                    <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Permissions</th>

<!--                                        <th>Operation</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $index => $permission)
                                    <tr>
                                        <td>{{ ++$index }}</td> 
                                        <td>{{ $permission->name }}</td>                                        
<!--                                        <td>
                                            <a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}" ><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;&nbsp;</a>
                                        </td>-->
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
    </section>
</div>
<script src="{{ asset('/assets/js/customJS/superadmin.js') }}" type="text/javascript"></script>
@endsection