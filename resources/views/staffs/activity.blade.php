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
            <li class="active">Staff Activity</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
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
                                
                                    <th>Action</th>
                                    <th>Page</th>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($details) >0)
                                @foreach ($details as $index => $activity)
                                <tr>
                                    <td>{{ ++ $index }}</td>
                                
                                    <td>{{ ucwords($activity->action) }}</td>
                                    <td>{{  $activity->page }}</td>
                                      <td>{{  $activity->action_id }}</td>
                                    <td>{{  $activity->description }}</td>
                                    <td>{{ $activity->datetime }}</td>
                                   
                                </tr>
    
                                @endforeach
                              
                                @endif
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