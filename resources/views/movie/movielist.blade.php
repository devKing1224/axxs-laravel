@extends('layouts.default')
@section('content')
<style type="text/css">
    .dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @php
            $url = request()->segment(count(request()->segments()));
        @endphp
        <h1>@if($url == 'inactivemovies') Inactive Movie List @else Movie List @endif </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/movies')}}">Movie</a></li>
            <li class="active">@if($url == 'inactivemovies') Inactive Movie List @else Movie List @endif </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-header text-right">
                        <a href="{{url('addmovie')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Movie</a>
                    </div>
                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->
               
                     <form method="POST"  action="{{action('ServicePermissionController@defaultPermissionByFacility')}}">
                        {{ csrf_field() }}
                       @role('Facility Admin')                        
                            <div class="text-right">
                            <a id="resetservice"  data-link="{{url('/')}}/resetuserservices" @if(count($serviceList) > 0) onclick="resetuserservices()" @endif class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Click this button to set default services for all users !" @if(count($serviceList) == 0)disabled @endif >Reset Services</a> 
                                 <input type="submit"  class="btn btn-primary registerPermissionPost" value="Save Changes"> 
                            </div>
                         @endrole        
                        <div class="box-body">
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
                    </script>        <!-- /.box-header -->
                    <div class="box-body">
                        <table id="movie-table" class="table table-striped table-condensed">
                            <thead>
                                  <tr>
                                    <th>S.No</th>
                                    <th>Movie</th>
                                    <th>Logo</th>
                                    <th>Url</th>
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
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Movie</h4>
          </div>
          <div class="modal-body">
            <form id="updateMovie" action="{{route('movie.update')}}" enctype='multipart/form-data' method="POST">
                <input type="hidden" name="movie_id" id="m_id">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
              <div class="form-group">
                <label for="email">Movie Name</label>
                <input type="text" name="movie_name" class="form-control" id="m_name" required="">
              </div>
              <div class="form-group">
                <label for="pwd">Movie URL:</label>
                <input type="text" name="movie_url" class="form-control" id="m_url" required="">
              </div>
              
              <div class="form-group">
                <label for="pwd">Logo Image:</label>
                <input id="m_image" type="file" name="logo_image" class="form-control" accept="image/*">
                <input type="hidden" id="old_imageurl" name="old_imageurl" value="">
                <img src="" id="m_logo" height="42" width="42">
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

<script type="text/javascript">
    $(function() {
        var base_url = window.location.origin;
        var pathname = window.location.pathname.split("/").pop();
        if (pathname == 'inactivemovies') {
            url = '/getinactivemovie';
        }else{
            url = '/getmovielist';
        }

        $('#movie-table').DataTable({
             language: {
            processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
            },
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: base_url+url,

            columns: [
            {data: 'DT_RowIndex'},
            {data: 'name'},
            {data: 'logo'},
            {data: 'movie_url'},
            {data: 'action'}
        ],
        language: {
        searchPlaceholder: "Search Movie"
    },
        order: [[1, 'asc']]
        });

    });

    //edit movie function
    function editMovie($id){
    $.ajax({
                type: "GET",
                url: '/movie/edit/'+$id,
                data: {movie_id: $id},
                success: function( data ) {
                    $("#m_id").val(data.id);
                    $("#m_name").val(data.name);
                    $("#m_url").val(data.movie_url);
                    $("#m_logo").attr("src", data.logo_url);
                    $("#old_imageurl").val(data.img_name);
                    $('#myModal').modal('show');

                    console.log(data);
                }
            });
    }

    $("#m_image").change(function(){
    readURL(this);
    });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#m_logo').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        //delete movie function
        function deleteMovie($id){
        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: "{{URL::to('movie/delete')}}"+'/'+$id, // This is the url we gave in the route
            data: {
        "_token": "{{ csrf_token() }}"}, // a JSON object to send back
            success: function(response){ // What to do if we succeed
                
                $('#movie-table').DataTable().ajax.reload(); 
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
      }

        //make movie active movie function
        function makeMovieActive($id){
        $.ajax({
            method: 'POST', // Type of response and matches what we said in the route
            url: "{{URL::to('movie/makeactive')}}"+'/'+$id, // This is the url we gave in the route
            data: {
        "_token": "{{ csrf_token() }}"}, // a JSON object to send back
            success: function(response){ // What to do if we succeed
                
                $('#movie-table').DataTable().ajax.reload(); 
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
      }  
    
</script>
@stop