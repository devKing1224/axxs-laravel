@extends('layouts.mobile')
@section('content') 
 <style type="text/css" media="all">
    .boldemailtext {
          color: black;
          font-weight:bold;
    }
  </style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12 col-sm-12" class='inboxmail'>
                @if(Session::has('message'))
                <div class="alert alert-success fade-message">
                    <p>{{ Session::get('message') }}</p>
                </div>

                @endif  
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inbox</h3>

                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->

                            <!-- /.btn-group -->
                            <button type="button" class="btn btn-info btn-sm" onClick="window.location.reload();"><i class="fa fa-refresh">&nbsp;</i>Receive New Email </button>
                            <div class="pull-right">
                                <span class="pagenumber"></span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm prev_btn"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm next_btn"><i class="fa fa-chevron-right"></i></button>
                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover mailtable table-striped">
                                <thead>
                                    <tr>
                                        <th>Sn</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Date Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($inboxmail)>0)
                                    @foreach($inboxmail as $index =>$mail)
                                            @if($mail->is_viewed == 0)
                                     <tr class="boldemailtext">                                        <td >{{ ++$index }}</td>

                                            <td class="mailbox-name" ><a href="{{ route('inmate.inboxemailview', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'mail_id' => $mail->id]) }}" style="text-decoration: none; border-bottom: 1px solid green;">{{ $mail->name }}</a></td>
                                            <td class="mailbox-subject" ><b>{{ $mail->subject }}</b></td>
                                            <td class="mailbox-date">{{ Carbon\Carbon::parse($mail->recieved_time)->setTimezone('EST')->format('m/d/Y H:i:s') }}</td>
                                        </tr>
                                        @else
                                         <tr>
                                            <td >{{ ++$index }}</td>

                                            <td class="mailbox-name" ><a href="{{ route('inmate.inboxemailview', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'mail_id' => $mail->id]) }}" style="text-decoration: none; border-bottom: 1px solid green;">{{ $mail->from_name }}</a></td>
                                            <td class="mailbox-subject" >{{ $mail->subject }}</td>
                                            <td class="mailbox-date">{{ Carbon\Carbon::parse($mail->recieved_time)->setTimezone('EST')->format('m/d/Y H:i:s') }}</td>
                                        </tr>

                                    @endif
                                    @endforeach
                                    @endif 
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-padding">
                        <div class="mailbox-controls">

                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>


            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

</div>
<script src="{{ asset('/assets/js/customJS/email.js') }}" type="text/javascript"></script>
<script>
$(function () {
    setTimeout(function () {
        $('.fade-message').slideUp();
    }, 4000);
});
</script>
@stop