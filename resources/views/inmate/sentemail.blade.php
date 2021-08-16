@extends('layouts.mobile')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12 col-sm-12" class='inboxmail'>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Sent Mail</h3>

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
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>&ensp;Refresh</button>
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
                <table class="table">
                     <thead>
                        <tr>
                            <th>Sn</th>
                            <th>Email Address</th>
                            <th>Subject</th>
                            <th>Body</th>
                            <th>Date Time</th>
                        </tr>
                    </thead>
                  <tbody>
                        @if(count($inmateSentEmails)>0)
                        @foreach($inmateSentEmails as $index =>$mail)
                      
                          <tr>
                            <td >{{ ++$index }}</td>
                            <td class="mailbox-name" ><a href="{{ route('inmate.sentemailview', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'mail_id' => $mail->id]) }}">{{ $mail->to }}</a></td>
                            <td class="mailbox-name" ><a href="#">{{ $mail->subject }}</a></td>
                            <td class="mailbox-subject" ><b>{{  mb_strimwidth(strip_tags($mail->body), 0, 20, "...") }}</b></td>
                            <td class="mailbox-date">{{ Carbon\Carbon::parse($mail->created_at)->setTimezone('EST')->format('m/d/Y H:i:s') }}</td>
                         </tr>
    
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
@stop