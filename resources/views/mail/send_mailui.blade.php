@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js">
      
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
  
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color:#3584e6;
    }

</style>

<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Send Mail</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/get_user_email')}}">Email</a></li>
            <li class="active">Send Email</li>
        </ol>
    </section>
 
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    @if(Session::has('error'))
    <p id="error" class="alert {{ Session::get('alert-class', 'alert-danger') }} " >{{ Session::get('error') }}<button type="button" class="close" data-dismiss="alert">x</button></p>
    @endif
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <!--  -->
                    </div>
                    
                     <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="contact-form" method="POST" action="{{url('/send_mailto')}}" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}
    <div class="messages"></div>

    <div class="controls">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="form_name">To *</label>
                    <select name="inmate_mail[]" id="chkveg" multiple="multiple" class="form-control js-email-basic-multiple" required="required">

                        @foreach($user as $users)

                        <option value="{{$users->id}}">{{$users->username}}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">CTRL – allows you to click and select multiple files.</small>
                    <br>
                    <br>
                    <input class="btn btn-info btn-sm" type="button" id="select_all" name="select_all" value="Select All">
                    <input class="btn btn-danger btn-sm" type="button" id="deselect_all" name="select_all" value="Deselect All" style="display: none;">
                    <!-- <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
                    <div class="help-block with-errors"></div> -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="form_lastname">Subject *</label>
                    <input id="form_lastname" type="text" name="subject" class="form-control" placeholder="Please enter your subject *" required="required" data-error="Lastname is required.">
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="form_email">Attachment *</label>
                    <div class="custom-file">
                      <input name="file[]" style="border: 0px;" type="file" class="custom-file-input" id="attach" multiple accept=".pdf,.jpeg,.png">
                      <!-- <label class="custom-file-label" for="customFile">Choose file</label> -->
                    </div>

                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="form_message">Message *</label>
                    <textarea id="form_message" name="message" class="form-control" placeholder="Message for me *" rows="4" required="required" data-error="Please, leave us a message."></textarea>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-12">
                <input type="submit" class="btn btn-success " value="Send message">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="text-muted">
                    <strong>*</strong> These fields are required.</p>
            </div>
        </div>
    </div>

</form>
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
    <!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title ">View Email</h4>
      </div>
      <div class="modal-body">
        <div id="email_html"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>


<script type="text/javascript">
    var base_url = window.location.origin;
    $(function() {
        $('#select_all').click(function() {
        $('#chkveg option').prop('selected', true);
        $('#deselect_all').show()
        });

        $('#deselect_all').click(function() {
        $('#chkveg option').prop('selected', false);
        $('#deselect_all').hide()
        });

        $('#email-table').DataTable({
             language: {
            processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
            },
            processing: true,
            serverSide: true,
            ajax: base_url+'/getrejectedemaildata',

            columns: [
            {data: 'from'},
            {data: 'subject'},
            {data: 'to'},
            {data: 'html'}
        ],
        order: [[1, 'asc']]
        });

    });

    function viewemail($id) {
        $.ajax({
                url: base_url+'/view_useremail/'+$id,
                type: "post",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: { email_id : $id },
                success: function(data){
                    $('#myModal').modal('show');
                    $('#email_html').html(data);
                }
            });
    };

     function approve_email($id,$value) {

        $.ajax({
                url: base_url+'/approve_email/'+$id,
                type: "post",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data : {value : $value},
                success: function(data){
                    if (data.status == 'success') {
                        toastr.success(data.msg);
                        $('#email-table').DataTable().ajax.reload();
                    }else{
                        toastr.error(data.msg);
                    }

                }
            });
    };

    $("#attach").on('change', function(){
        var filename = $("#attach").val();
        var extension = filename.replace(/^.*\./, '');
        console.log(extension);
 })
$('.js-email-basic-multiple').select2({
    placeholder: "Select Inmates",
    allowClear: true
});


    

</script>
@stop