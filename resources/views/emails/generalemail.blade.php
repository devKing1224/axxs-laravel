@extends('layouts.mobile')

@section('styles')
   <link href="{{ asset("/assets/css/custom.css")}}" rel="stylesheet" type="text/css" />
   
@stop
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
                 <!-- Flash message -->
                                <div class="col-md-12 col-sm-12 alert alert-success" id="alertDiv" style="display:none">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                    <span id="alert"></span>
                                </div>
                                <div class="col-md-12  col-sm-12 alert alert-danger" id="alertDangerDiv" style="display:none">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                    <span id="alertDanger"></span>
                                </div>
                                <!-- Flash message -->
              <div class="mailbox-read-info">
                <input type="hidden" value="{{ $maildetails->id }}" name="email_id" id="email_id" >
                <h3>{{ $maildetails->subject }}</h3>
                <h5>From: {{ $maildetails->from}} ) 
                  <span class="mailbox-read-time pull-right">{{ Carbon\Carbon::parse($maildetails->recieved_time)->setTimezone('EST')->format('jS, F Y,  h:i A') }}</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border makepadding">
                {!! $maildetails->html !!}

              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                  @if(count($maildetails->attachments) > 0)
                <button type="button" class="btn btn-primary"> Download Attachments <span class="badge badge-pill badge-success">{{count($maildetails->attachments)}}</span></button>
                <br>
                @foreach($maildetails->attachments as $key=>$attac)
                <a href="{{$attac->link}}"  target="_blank" style="text-decoration: none;">Attachment {{$key+1}}</a> &nbsp;
                 @endforeach
              @endif
              @if(count($maildetails->fac_attach) > 0)
                <button type="button" class="btn btn-primary"> Download Attachments <span class="badge badge-pill badge-success">{{count($maildetails->fac_attach)}}</span></button>
                <br>
                @foreach($maildetails->fac_attach as $key=>$attac)
                <a href="{{$attac->link}}"  target="_blank" style="text-decoration: none;">Attachment {{$key+1}}</a> &nbsp;
                 @endforeach
              @endif
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer -->
            <div class="box-footer">
                <!-- @if($maildetails->is_deleted == 1)
                <a href="{{ route('deleteinboxmail', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'mail_id' => $maildetails->id , 'delete' => '0']) }} " class="btn btn-default"><i class="fa fa-trash-o"></i> Restore </a>
                @else
                <a href="{{ route('deleteinboxmail', ['inmate_id'=> $inmate_id,'service_id' => $service_id,'mail_id' => $maildetails->id, 'delete' => '1']) }}" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</a>
                @endif -->
                <!-- @if(count($maildetails->fac_attach) == 0)
                <button class="forward_mail" userid="{{ $inmate_id}}" mailid="{{ $maildetails->id }}"  class="btn btn-default"><i class="fa fa-share"></i> Forward</button>

                  <button class="reply_mail" userid="{{ $inmate_id}}" mailid="{{ $maildetails->id }}" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                  @endif -->
                
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        
        </div>
          
        <!-- /.col -->
      </div>
      <!-- /.row -->
      
       <div class="modal fade" id="emailForwardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog Emailforwardcss" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Forward Email to</h4>
                    </div>
                      <div class="modal-body">
                         
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="form-group " id="forwardbody">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <textarea id="compose-textarea" name="body" class="form-control" style="height: 200px">  
                                    </textarea>
                                  Content attached
                                  <p>Note: this action will deduct ${{$emailCharge->value}}</p>
                                  
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary ForwardEmalMessage" userid="{{ $inmate_id}}">Forward</button>
                        </div>
                </div>
            </div>
        </div>

<!---Reply email model -->

  <div class="modal fade" id="emailReplyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog Emailforwardcss" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Reply Email to</h4>
                    </div>
                      <div class="modal-body">
                           <div class="box-body">
                                <div class="col-md-12">
                                    <div class="form-group " id="replybody">
                                    </div>
                                </div>
                                  <div class="col-md-12">
                                      <textarea id="repcompose-textarea" name="repbody" class="form-control" style="height: 200px">  
                                      </textarea>
                                    Content attached
                                    <p>Note: this action will deduct ${{$emailCharge->value}}</p>
                                    
                                  </div>
                          </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary replyEmalMessage" userid="{{ $inmate_id}}">Reply</button>
                        </div>
                </div>
            </div>
        </div>


    </section>
    <div class="loader loader-default" data-text="Sending..." data-blink></div>
    <!-- /.content -->

</div>
<script src="{{ asset('/assets/js/customJS/email.js') }}" type="text/javascript"></script>
<script>
  $(function () {
    //Add text editor
    $("#repcompose-textarea").wysihtml5();
  });
</script>
@stop