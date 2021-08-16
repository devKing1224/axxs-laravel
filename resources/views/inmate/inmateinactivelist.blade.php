@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inactive User List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/allusers')}}">User</a></li>
            <li class="active">Inactive User List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-left ">
                        <div class="row">
                            <div class="col-md-4">
                        <select  class="form-control" id="InmateActiveInactiveCall">
                            <option>Please select any option</option>>
                            <option value="1">Active</option>
                            <option value="2" selected>Inactive</option>
                        </select>
                    </div>
                    </div>
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
                                    <th>S.no.</th>
                                    @if(!Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff']))
                                    <th>Facility Name</th>
                                    @endif
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Birthday</th>
                                    <th>Phone</th>
                                 
                                    <th>Email</th>
                                    <th>Balance ($)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; if(count($userList)>0){ foreach($userList as $val) { ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                     @if(!Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff']))
                                    <td><b>{{ ucwords($val->inmateFacility['name']) }}</b></td>
                                    @endif
                                   <td>{{ ucwords($val->first_name)}} </td>
                                   <td>{{ ucwords($val->last_name)}} </td>
                                   <td>{{ ucwords($val->middle_name)}} </td>
                                   <td>{{ ucwords($val->date_of_birth)}} </td>
                                    <td>{{ phone_number_format($val->phone) }}</td>
                                   
                                     <td> @if(isset($val->inmateEmail)){{ $val->inmateEmail->email }} @else - @endif</td>
                                    <td><?php echo $val->balance; ?></td>
                                    <td>

                                        <a href="javascript:;" token="{{ csrf_token() }}" id="<?php echo $val->id; ?>" data-toggle="tooltip" title="Active" <i class="fa fa-thumbs-up inmateActiveButton"></i>&nbsp;&nbsp;&nbsp;</a>
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
<script type="text/javascript">
    
    /* Function for inactive inmate update to active inmate. */
    $('.inmateActiveButton').click(function () {
        var inmateID = this.id;
         token = $(this).attr('token');
        $.ajax({
            type: 'post',
           data: {inmate_id: inmateID, _token:token},
            url:  'activeuser',
            dataType: 'json',
            success: function (result) {
                if (result.Code === 200) {
                    sessionStorage.inmateActive = 'User activated successfully';
                    window.location.href = 'userinactivelist';
                    return false;
                } else if (result.Code === 400) {
                    swal("User Not inactivated", result.Message, "warning");
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                alert(jqXHR);
                console.log('jqXHR' + jqXHR);
                console.log('exception' + exception);
            }
        });
    });
</script>
<script src="<?php echo asset('/'); ?>assets/js/customJS/inmate.js" type="text/javascript"></script>
@stop