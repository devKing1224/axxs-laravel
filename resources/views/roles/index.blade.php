@extends('layouts.default')

@section('title', '| Roles')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> User Roles 
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
                    <!-- <div class="box-header text-right">
                        <a href="{{ URL::to('roles/create') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Role</a>
                    </div> -->
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
                                    <th>S.no.</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($roles as $index => $role)
                                @if($role->name != 'Inmate')
                                <tr>
                                    <td>{{ ++ $index }}</td>
                                    <td>{{ $role->name }}</td>

                                    <td style="width: 65%;">{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                    <td>
                                        <a href="{{ URL::to('roles/'.$role->id.'/edit') }}"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @if($role->name == 'Facility Admin' || $role->name == 'Super Admin' || $role->name == 'Family Admin' || $role->name == 'Facility Staff')
                                        @else
                                        <a href='javascript:;' token="{{ csrf_token() }}" deletename="role" class="CommonDelete" id="{{$role->id}}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                    </td>
                                </tr>
                                @endif
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