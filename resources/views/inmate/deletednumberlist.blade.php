@extends('layouts.mobilesms')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
      <div class="row">

        <div class="col-md-12 col-sm-12 inboxmail">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Trashed Contact List</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Number">
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
                <a href="{{ URL::current()}}" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
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
                            <th>Number</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                  <tbody>
                        @if(count($inmate_numbers)>0)
                        @foreach($inmate_numbers as $index =>$number)
                         <tr>
                            <td >{{ ++$index }}</td>
                           
                            <td class="mailbox-name" ><a href="{{ route('viewdeletedchat', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'number_id' => $number->id]) }}">{{ $number->name }}</a></td>
                            <td class="mailbox-subject" ><b>{{ $number->email_phone }}</b></td>
                            <td class="mailbox-date">{{ $number->type }}</td>
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