@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Facility Inactive List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Facility</a></li>
            <li class="active">Facility Inactive List</li>
        </ol>
    </section>

    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-right">
                        @role('Super Admin')
                        <a href="{{action('ExcelController@inactiveFacilityReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Inactive Facilities</a>
                        @else 
                        @can('Download Inactive Facility List Report')
                        <a href="{{action('ExcelController@inactiveFacilityReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Inactive Facilities</a>
                        @endcan
                        @endrole  
                    </div>
                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1;
                                if (count($facilityList) > 0) {
                                    foreach ($facilityList as $val) { ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $val->name; ?></td>
                                            <td><?php echo $val->email; ?></td>
                                            <td><?php echo $val->phone; ?></td>
                                            <td><?php echo $val->city; ?></td>
                                            <td>
                                                <a href="javascript:;" token="{{ csrf_token() }}" id="<?php echo $val->id; ?>" data-toggle="tooltip" title="Active" <i class="fa fa-thumbs-up facilityActiveButton"></i>&nbsp;&nbsp;&nbsp;</a>
                                            </td>
                                        </tr>
        <?php $count++;
    }
} ?>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/facility.js" type="text/javascript"></script>
@stop