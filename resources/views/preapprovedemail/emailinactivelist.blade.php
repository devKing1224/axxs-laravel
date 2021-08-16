@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inactive Email List</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Pre-Approved Contacts</a></li>
            <li class="active">Inactive Email List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                    <th>Name</th>
                                    <th>Email Addresses</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; if(count($emailList)>0){ foreach($emailList as $val) { ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $val->name; ?></td>
                                    <td><?php echo $val->email_phone; ?></td>
                                    <td><?php if(isset($val->status) && $val->status == 1){ echo 'Inactive'; }?></td>
                                    <td>
                                        <!--<a href="viewservice/{{$val->id  or ''}}" data-toggle="tooltip" title="View" <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>-->
                                        <a href="javascript:;"  token="{{ csrf_token() }}" id="<?php echo $val->id; ?>" data-toggle="tooltip" title="Active" <i class="fa fa-thumbs-up emailActiveButton"></i>&nbsp;&nbsp;&nbsp;</a>
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
<script src="<?php echo asset('/'); ?>assets/js/customJS/service.js" type="text/javascript"></script>
@stop