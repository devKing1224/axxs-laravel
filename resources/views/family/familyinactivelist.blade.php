@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Family Member Inactive List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Family</a></li>
            <li class="active">Family Member Inactive List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-left ">
                        <select class="" id="familyActiveInactiveCall">
                            <option>Please select any option</option>>
                            <option value="1+{{Request::route('inmate_id')}}">Active</option>
                            <option value="2+{{Request::route('inmate_id')}}">Inactive</option>
                        </select>
                    </div>
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S. no.</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i=1)
                                @if(count($familyList)>0) 
                                @foreach($familyList as $val) 
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $val->first_name }}</td>
                                    <td>{{ $val->phone }}</td>
                                    <td>{{ $val->city }}</td>
                                    <td>{{ $val->email }}</td>
                                    <td>
                                        <a href="javascript:;" token="{{ csrf_token() }}" id="<?php echo "$val->id+$inmate_id"; ?>" data-toggle="tooltip" title="Active" <i class="fa fa-thumbs-up familyActiveButton"></i>&nbsp;&nbsp;&nbsp;</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        <!-- /.col -->
        </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/family.js') }}" type="text/javascript"></script>
@stop