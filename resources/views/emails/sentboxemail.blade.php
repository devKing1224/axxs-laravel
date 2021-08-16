@extends('layouts.mobile')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-11">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Read Mail</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>{{ $maildetails->subject }}</h3>
                <h5>Sent To: {{ $maildetails->to }}
                  <span class="mailbox-read-time pull-right">{{ Carbon\Carbon::parse($maildetails->created_at)->setTimezone('EST')->format('jS, F Y,  h:i A') }}</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border makepadding">
                {!! $maildetails->body !!}

              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
      
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
            <div class="box-footer">
              <div class="pull-right">
              </div>
              
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        
        </div>
          
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

</div>

@stop