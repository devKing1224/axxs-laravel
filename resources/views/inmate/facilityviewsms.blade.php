@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inmate SMS List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Inmate</a></li>
            <li class="active">Inmate SMS List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--<div class="box-header">
                        <a href="{{action('InmateController@addInmateUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Inmate</a>
                    </div>  -->                
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                       
                        <h5>Note: Received SMS are in Blue shade and Sent are displayed in White.</h5>
                    
                        <table id="example1" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                    <th>Name</th>
                                    <th>Relation</th>
                                    <th>Date</th>
                                    <th>Text SMS</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inmate_sms as $index =>$sms)
                                @if($sms->contactperson)
                                    @if($sms->bound =='in')
                                    <tr class="bg-primary">
                                    @else
                                    <tr >
                                    @endif
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $sms->contactperson->name }}</td>
                                        <td>{{ $sms->contactperson->relation }}</td>
                                        <td>{{ Carbon\Carbon::parse($sms->created_at)->format('jS, F Y,  h:i A') }}</td>
                                        <td id="{{ $sms->id }}">{{ $sms->message }}</td>
                                        <td> @if($sms->blacklisted == 1 && $sms->is_ignored == 0) 
                                         <button class="btn btn-danger emailSms" type="button" data-toggle="modal"  id="{{ $sms->id }}" data-id="{{ $sms->id }}" value="{{ $sms->id }}"><i class="fa fa-mobile"> View</i></button>
                                        @else <button class="btn btn-primary emailSms" type="button" data-toggle="modal"  id="{{ $sms->id }}"  value="{{ $sms->id }}"><i class="fa fa-mobile"> View</i></button>
                                       @endif
                                    </td>
                                    </tr>
                                @endif
                                @endforeach
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
            <h4 class="modal-title">Text SMS</h4>
        </div>
        <div class="modal-body">

            <div class="row">                        
                <div class="col-sm-12">
                    <div class="upload-profile-popup clearfix">
                        <div name="body" id="body"></div>
                            <div> 
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
               <button type="button" id ="Ignore_sms" class="btn btn-warning" data-dismiss="modal">Ignore</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>        
            </div>
    </div>
</div>
<script src="<?php echo asset('/'); ?>assets/js/customJS/inmate.js" type="text/javascript"></script>
<script>
    
      $("body").on("click", "#Ignore_sms", function (e) {
    id = $('#ignore_id').val();
    $.ajax({
    type: 'post',
     url:  baseURL + 'ignoreblacklistedsms',
    data: $('#IgnoreModalData').serialize(),
    dataType: 'json',
       success: function (result) {
        if (result.Code === 201) {
            sessionStorage.insert = 'Ignore blacklisted word successfully';
            location. reload(true);
            //window.location.href = baseURL + 'allusers';
            return false;
        } else if (result.Code === 400) {
             sessionStorage.insert = 'Blacklisted word not in sms text';
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
    $("body").on("click", ".emailSms", function (e) {
        var id = $(this).attr('id');
        var getid = $(this).attr("data-id");
        var mes_txt = $("#"+id).text();
        $("#body").text(mes_txt);
        $("input[name=id]").val(id);
        $('#IgnoreModal').modal();
        
        if(typeof getid === "undefined") {
            $('#Ignore_sms').hide();
        }else{
            $('#Ignore_sms').show();
        }

    });
</script>


@stop