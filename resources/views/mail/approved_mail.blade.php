@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>

  .dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;

}
a {
  text-decoration: none !important;
}

.dataTables_wrapper .dataTables_processing {
position: absolute;
top: 30%;
left: 50%;
width: 30%;
height: 40px;
margin-left: -20%;
margin-top: -25px;
padding-top: 20px;
text-align: center;
font-size: 1.2em;
background:none;
}



</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Users Approved Email</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/get_user_email')}}">Email</a></li>
            <li class="active">Approved Email List</li>
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
                        <table id="email-table" class="table table-striped table-condensed">
                            <thead>
                                  <tr>
                                    <th>From</th>
                                    <th>Subject</th>
                                    <th>To</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
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

<script src="<?php echo asset('/'); ?>assets/js/customJS/device.js" type="text/javascript"></script>
<script type="text/javascript">
    var base_url = window.location.origin;
    $(function() {
        $('#email-table').DataTable({
             language: {
            processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
            },
            processing: true,
            serverSide: true,
            ajax: base_url+'/getapprovedemaildata',

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



    

</script>
@stop