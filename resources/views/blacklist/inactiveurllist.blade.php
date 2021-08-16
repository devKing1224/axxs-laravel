@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inactive Email List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Inactive Url</a></li>
            <li class="active">Inactive Url</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
           
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                    <th>Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; if(count($urls)>0){ foreach($urls as $val) { ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $val->url; ?></td>
                        
                                    <td>
                                    
                                        <a href="javascript:;"  token="{{ csrf_token() }}" id="<?php echo $val->id; ?>" data-toggle="tooltip" title="Active" <i class="fa fa-thumbs-up urlActiveButton"></i>&nbsp;&nbsp;&nbsp;</a>
                                    </td>
                                </tr>
                                <?php $i++; } } ?>
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