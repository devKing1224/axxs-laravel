@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
  .form-group.art {margin-top: 15px;}
  .ajax-loader {
    position: absolute;
    left: 50%;
    top: 50%;
    height: 48px;
    margin-left: -32px; /* -1 * image width / 2 */
    margin-top: -32px; /* -1 * image height / 2 */
  }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Add Music
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/musics')}}">Music</a></li>
            <li class="active">Add Music</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content service">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="mydiv">

                          
                    
                    <div class="box-header with-border">
                        <h3 class="box-title"> </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="javascript:;" id="serviceData" enctype="mutipart/form-data">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group uploadimg">
                                    <label>Music File <i class="requiredInput text-red">*</i></label> 
                                   <span> </span>
                                <input type="file" class="form-control" id="music_file" name="music_file" accept="audio/mp3, audio/mpeg" >

                                <input type="hidden" class="form-control" id="music_file_url" name="music_file_url" value="">

                                </div>
                                <span class="text-info"><b> Note: </b>Music file extension should be MP3</span>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> Song <i class="requiredInput text-red">*</i></label> 
                                    <input type="text" class="form-control" name="song_name" value="" id="song_name" placeholder="Please enter song name">
                                </div>
                                <br/>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group art">
                                    <label for="exampleInputEmail1"> Artist <i class="requiredInput text-red">*</i></label> 
                                    <input type="text" class="form-control" name="artist_name" value="" id="artist" placeholder="Please enter artist">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group art">
                                    <label for="exampleInputEmail1"> Genre <i class="requiredInput text-red">*</i></label> 
                                    <select class="form-control" name="genre_name" id="genre" >
                                        <option value="" disabled="" selected="">Please select genre</option>
                                        @foreach($music_genre as $genre)
                                        <option value="{{$genre->genres}}">{{$genre->genres}}</option>
                                        @endforeach
                                    </select>
                                    <!-- <input type="text" class="form-control" name="genre_name" value="" id="genre" placeholder="Please enter genre"> -->
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="MusicAddDataSend">Submit</button>
                            </div>
                        </div>
                    </form>
                    <img src="{{asset('assets/images/music_loader.gif')}}" class="ajax-loader" hidden="" /> 
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script>    
    var apiurl = {!! json_encode($api_url) !!};
</script>
<script src="<?php echo asset('/'); ?>assets/js/customJS/music.js" type="text/javascript"></script>

<script>
$('#music_file').change(function() {
    var musicfile = $('#music_file').prop('files')[0]; 
    var form2 = new FormData();
    form2.append('music_file', musicfile);
    var $loading = $('.ajax-loader').show();
    $.ajax({
        type: 'post',
        url: 'getfiledetails',
        data: form2,
        contentType: false,
        processData: false, 
    success: function (result) {
        $loading.hide();
        if (result.data!='') {
            $('#song_name').val(result.data.name);
            $('#artist').val(result.data.artist);
            $('#genre').append('<option value="'+result.data.genre+'" selected>'+result.data.genre+'</option>')
            //$('#genre').val(result.data.genre); 
        }else{
            $('#song_name').val('');
            $('#artist').val('');
            $('#genre').val('');
        } 
    }
   });
});

</script>
@stop

