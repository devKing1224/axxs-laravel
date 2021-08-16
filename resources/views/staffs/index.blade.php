@extends('layouts.default')
@section('title', '| Users')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> All Facility Staff
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/staffs')}}">Facility Staff</a></li>
            <li class="active">All Staff</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ route('staffs.create') }}" class="btn btn-primary "><i class="fa fa-plus" aria-hidden="true"></i> Add Staff</a>
                    </div>
                    <!-- Flash message show -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
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
                                    <th>Facility</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Date/Time Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ ++ $index }}</td>
                                    <td>{{ ucwords($user->staffFacility->facility_name) }}</td>
                                    <td>{{ ucwords($user->first_name) }} {{ ucwords($user->last_name) }}</td>
                                    <td>{{  $user->email }}</td>
                                    <td>{{  $user->username }}</td>
                                    <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
                                    <td>
                                        <a href="{{ route('staffs.edit', $user->id) }}"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;&nbsp;</a>
                                         <a href="{{ route('staffs.activity', $user->id) }}"><i class="fa fa-eye" data-toggle="tooltip" title="Staff Activity"></i>&nbsp;&nbsp;&nbsp;</a>
                                       
                                        <a href='javascript:;' token="{{ csrf_token() }}" deletename="staff" class="CommonDelete" id="{{$user->id}}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @if($user->is_deleted == 0)
                                        <a href='javascript:;' token="{{ csrf_token() }}" activename="deactivate" class="CommonAction" id="{{$user->id}}"><i class="fa fa-thumbs-down" data-toggle="tooltip" title="De-Activate"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else 
                                         <a href='javascript:;' token="{{ csrf_token() }}" activename="activate" class="CommonAction" id="{{$user->id}}"><i class="fa fa-thumbs-up" data-toggle="tooltip" title="Activate"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                    </td>
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