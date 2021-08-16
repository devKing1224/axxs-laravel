@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Email List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/allusers')}}">User</a></li>
            <li class="active">User Email List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--<div class="box-header">
                        <a href="{{action('InmateController@addInmateUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> User</a>
                    </div>  -->                
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
                                    <th>To/From</th>
                                    <th>Subject</th>
                                    <th>Date Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($alleamil)>0)
                                    @foreach($alleamil as $index=> $val)
                                       
                                        <tr >
                                            <td>{{ ++$index }}</td>
                                            @if($val->type == 1)
                                                <td>{{ $val->emailid }} <i class="fa fa-arrow-up text-primary" aria-hidden="true"></i> </td>
                                            @else
                                                <td>{{ $val->emailid }} <i class="fa fa-arrow-down text-success" aria-hidden="true"></i></td>
                                            @endif
                                            <td>{{ $val->subject }}</td>
                                            <td>{{ $val->created_at }}</td>
                                            <td>

                                            @if(isset($val->blacklisted))
                                            @if($val->blacklisted == 1 ) 
                                                <button class="btn btn-danger emailView" type="button" data-toggle="modal"  etype="{{ $val->type }}" id="{{ $val->id }}" data-id="{{ $val->id }}" value="{{ $val->id }}"><i class="fa fa-envelope-o"> View</i></button>
                                                <input type="hidden" name="" value="0">
                                            @else
                                                   <button class="btn btn-primary emailView" type="button" data-toggle="modal"  etype="{{ $val->type }}" id="{{ $val->id }}"  value="{{ $val->id }}"><i class="fa fa-envelope-o"> View</i></button>
                                                   <input type="hidden" name="" value="1">
                                             @endif
                                             @endif

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

<div id="IgnoreModal"  data-backdrop="static" class="modal fade example-modal" role="dialog"> 
    <div class="modal-content modal-dialog ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="font-size:-webkit-xxx-large;" id="patientImageUploadModalCancelBtn">&times;</button>
            <h4 class="modal-title">Email Content</h4>
        </div>
        <div class="modal-body">

            <div class="row">                        
                <div class="col-sm-12">
                    <div class="upload-profile-popup clearfix">                                                                
                        <div name="body" id="body"></div>
                            <div > 
                                <form role="form" method="post" action="javascript:;" 
                                id="IgnoreModalData">
                                    <input type="hidden" id="ignore_id" name="id" >
                                    {{ csrf_field() }}
                                </form>
                           </div>
                    </div>
                </div>
            </div>    
            <!-- This Screen FOr Card Details FOr Patient-->
        </div>
        <div class="modal-footer">
               <button type="button" id ="Ignore_word" class="btn btn-warning" data-dismiss="modal">Ignore</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
           
         </div>
    </div>
</div>
<script src="<?php echo asset('/'); ?>assets/js/customJS/inmate.js" type="text/javascript"></script>
<script>

          $("body").on("click", "#Ignore_word", function (e) {
            id = $('#ignore_id').val();
            $.ajax({
                type: 'post',
                url:  baseURL + 'ignoreblacklisted',
                data: $('#IgnoreModalData').serialize(),
                dataType: 'json',
                   success: function (result) {
                    if (result.Code === 201) {
                        sessionStorage.insert = 'Ignore blacklisted word successfuly';
                        location. reload(true);
                        //window.location.href = baseURL + 'allusers';
                        return false;
                    } else if (result.Code === 400) {
                         sessionStorage.insert = 'Blacklisted word not in email text';
                         location. reload(true);
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR' + jqXHR);
                    console.log('exception' + exception);
                    swal('Error!!', exception, 'error');
                }
            });
      
        });

      $("body").on("click", ".emailView", function (e) {
           var id = $(this).attr('id');
           var getid = $(this).attr("data-id");
           $("input[name=id]").val(id);
           $('#IgnoreModal').modal();
               if(typeof getid === "undefined") {
                    $('#Ignore_word').hide();
                }else{
                    $('#Ignore_word').show();
                }
      });

</script>

@stop