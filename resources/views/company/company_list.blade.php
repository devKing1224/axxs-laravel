@extends('layouts.default')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<style type="text/css">
    .dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice { background-color:#3584e6;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Organization List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Organization</a></li>
            <li class="active">Organization List</li>
        </ol>
    </section>
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    <!-- Main content -->
    <section class="content">

        
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                  
                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->
               
                     
                    @if(Session::has('flash_message'))
                        <div class="alert alert-success fade-message">
                            {{ Session::get('flash_message') }}
                        </div>
                    @endif
                    @if ($errors->any())
                         @foreach ($errors->all() as $error)
                             <div class="alert alert-danger fade-message">
                                 {{$error}}
                             </div>
                         @endforeach
                     @endif
                    <script>
                    $(function(){
                    setTimeout(function() {
                    $('.fade-message').slideUp();
                    }, 4000);
                    });
                    </script>
                    <div class="box-header text-right">
                    <a href="{{route('cmpy.add')}}" class="btn btn-info" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i>
                    Add Organization</a>
                  </div>
                    
                    <div class="box-body">

                        <table id="cmpny-table" class="table table-striped table-condensed">
                            <thead>
                                  <tr>
                                    <th>S.No</th>
                                    <th>Organization</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                    
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                            
                        </table>
                    </div>

                   </form>
         
                    
                               
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <!-- Movie Edit Modal -->
    <!-- Trigger the modal with a button -->


<!-- Modal -->
<div id="assignfacility" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Facility</h4>
      </div>
      <div class="modal-body">
        <form id="updateMovie" action="{{route('assign.facility')}}" enctype='multipart/form-data' method="POST">
            <input type="hidden" name="fa_id" id="fa_id">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="email">Facility Admin Name</label>
            <input type="text" name="fa_name" class="form-control" id="fa_name" required="" disabled="">
          </div>
          <div class="form-group">
              <label for="exampleFormControlSelect1">Select Facility</label>
              <br>
              <select style="width: 100%" class="form-control js-states  js-example-basic-multiple" id="facility_select" name="facility_id[]" multiple="multiple" >
              </select>
            </div>
          
          
          <button type="submit" class="btn btn-default">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>
<script src="{{ asset('/assets/js/customJS/facilityadmin.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        var base_url = window.location.origin;
        $('#cmpny-table').DataTable({
             language: {
            processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
            },
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: base_url+'/getcompanylist',

            columns: [
            {data: 'DT_RowIndex'},
            {data: 'name'},
            {data: 'created_at'},
            {data: 'action'},


        ],
        language: {
        searchPlaceholder: "Search Company"
    },
        order: [[1, 'asc']]
        });

    });
     
        $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip({
        trigger : 'hover'
    }); 
         }); 
</script>
@stop