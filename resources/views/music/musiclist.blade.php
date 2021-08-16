@extends('layouts.default')
@section('content')
<style type="text/css">
.dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}
.alert{
    text-align:center;
}
#old_musicfile{
    margin-left: 20px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @php
            $url = request()->segment(count(request()->segments()));
        @endphp
        <h1>@if($url == 'inactivemusics') Inactive Music List @else Music List @endif </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/musics')}}">Music</a></li>
            <li class="active">@if($url == 'inactivemusics') Inactive Music List @else Music List @endif </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-header text-right">
                        <a href="{{url('addmusic')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Music</a>
                    </div>
                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                     <div class="alert alert-danger" id="alertDivErr" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alertErr"></span>
                    </div>
                    <!-- Flash message -->
                    {{ csrf_field() }}
                           
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
                        <table id="music-table" class="table table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Song</th>
                                    <th>Artist</th>
                                    <th>Genre</th>
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
    <!-- Modal -->
 
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Music</h4>
          </div>
          <div class="modal-body">
            <form id="updateMusic" action="{{route('music.update')}}" enctype='multipart/form-data' method="POST">
                <input type="hidden" name="music_id" id="m_id">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <label for="pwd">Uploded Music File : </label>
                   <audio controls id="old_musicfile">
                    <source src="" type="audio/mpeg" >
                  </audio>
                  <br/>
                </div>

                <div class="form-group">
                    <label for="email"> Song : </label>
                    <input type="text" name="song" class="form-control" id="m_name" required="">
                </div>

                <div class="form-group">
                    <label for="pwd"> Artist :</label>
                    <input type="text" name="artist" class="form-control" id="m_artist" required="">
                </div>

                <div class="form-group">
                    <label for="pwd"> Genre :</label>
                    <select class="form-control" name="genre" id="m_genre" >
                                        <option value="" disabled="" selected="">Please select genre</option>
                                        @foreach($music_genre as $genre)
                                        <option value="{{$genre->genres}}">{{$genre->genres}}</option>
                                        @endforeach
                                    </select>
                    <!-- <input type="text" name="genre" class="form-control" id="m_genre" required=""> -->
                </div>
              
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
</div>
<script>    
    var apiurl = {!! json_encode($api_url) !!};
</script>
<script src="<?php echo asset('/'); ?>assets/js/customJS/music.js" type="text/javascript">
</script>
@stop