<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<style type="text/css">
		.bg-dark {
    background-color: #212529 !important;
}
.btn-info {
    color: #fff;
     background-color: transparent !important; 
    border-color: #212529;
}
.toggle-handle{
	background-color: white !important;
}
input, input:before, input:after {
      -webkit-user-select: initial;
      -khtml-user-select: initial;
      -moz-user-select: initial;
      -ms-user-select: initial;
      user-select: initial;
     } 

.img-thumbnail {
    padding: 0;
    border: none;
    width: 170px;
}

#cover {
    background: url("http://www.aveva.com/Images/ajax-loader.gif") no-repeat scroll center center #FFF;
    position: absolute;
    height: 100%;
    width: 100%;
}
.gradient{
	background: linear-gradient(45deg, #2b2a2d82, #908a8a00);
    color: white;
}
input::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: white !important;
  opacity: 1; /* Firefox */
}
.spinning-circle {
  animation-name: spinning-circle;
  animation-duration: 10s;
  animation-iteration-count: infinite;
  width: 40px;
  height: 40px;
}

@-webkit-keyframes spinning-circle {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

.pause {
  position: relative;
  font-family: sans-serif;
  text-transform: uppercase;
  letter-spacing: 4px;
  overflow: hidden;
  background: linear-gradient(90deg, #000, #fff, #000);
  background-repeat: no-repeat;
  background-size: 80%;
  animation: animate 3s linear infinite;
  -webkit-background-clip: text;
  -webkit-text-fill-color: rgba(255, 255, 255, 0);
}

@keyframes animate {
  0% {
    background-position: -500%;
  }
  100% {
    background-position: 500%;
  }
}
/*body{background: linear-gradient(45deg, #3a6186, #89253e);}*/
body{
	background-color: black;
}
.eq-bar {
  transform: scale(1, -1) translate(0, -24px);
}

.eq-bar--1 {
  animation-name: short-eq;
  animation-duration: 0.5s;
  animation-iteration-count: infinite;
  animation-delay: 0s;
}

.eq-bar--1 {
  animation-name: short-eq;
  animation-duration: 0.5s;
  animation-iteration-count: infinite;
  animation-delay: 0s;
}

.eq-bar--2 {
  animation-name: tall-eq;
  animation-duration: 0.5s;
  animation-iteration-count: infinite;
  animation-delay: 0.17s;
}

.eq-bar--3 {
  animation-name: short-eq;
  animation-duration: 0.5s;
  animation-iteration-count: infinite;
  animation-delay: 0.34s;
}

@keyframes short-eq {
  0% {
    height: 8px
  }

  50% {
    height: 4px
  }

  100% {
    height: 8px
  }
}

@keyframes tall-eq {
  0% {
    height: 16px
  }

  50% {
    height: 6px
  }

  100% {
    height: 16px
  }
}

.shine:hover:after {
  opacity: 1;
  top: -30%;
  left: -30%;
  transition-property: left, top, opacity;
  transition-duration: 0.7s, 0.7s, 0.15s;
  transition-timing-function: ease;
}

.playthumb{
	background: linear-gradient(45deg, black, transparent);
    border-radius: 32px;
        margin-left: -51px;

}
.table td, .table th {
    border-top: 1px solid #03060812;
}
.bg-dark{
	    /*border-radius: 101px;*/
    /* background: none; */
   /* background: linear-gradient(45deg, #77A1D3, #E684AE);*/
}

@media (min-width:320px)  { /* smartphones, iPhone, portrait 480x320 phones */ }
@media (min-width:481px)  { /* portrait e-readers (Nook/Kindle), smaller tablets @ 600 or @ 640 wide. */
.img-thumbnail{
	height: 50px;
    width: 50px;
}
 }
 @media (min-width:768px)  { /* portrait e-readers (Nook/Kindle), smaller tablets @ 600 or @ 640 wide. */
.img-thumbnail{
	height: 50px;
    width: 50px;
}
.list_div{
 		min-height: 1154px !important;
 	}
 	.main_div{
 		height: auto !important;
 	}
 }
@media (min-width:641px)  { /* portrait tablets, portrait iPad, landscape e-readers, landscape 800x480 or 854x480 phones */
	.img-thumbnail{
	height: 80px;
    width: 80px;
}
.playthumb{
	margin-left: -58px;
	}

 }

@media (min-width:961px)  { /* tablet, landscape iPad, lo-res laptops ands desktops */ }
@media (min-width:1024px) { /* big landscape tablets, laptops, and desktops */ 
	.img-thumbnail{
	height: 60px;
    width: 60px;
}

.playthumb{
	margin-left: -46px;
	}
}
@media (min-width:1281px) { 
.img-thumbnail{
	height: 66px;
    width: 70px;
}
.playthumb{
	margin-left: -53px;
	}
}
@media (min-width:2048px) { 
.list_div{
 		min-height: 1381px !important;
 	}
 	.main_div{
 		height: auto !important;
 	}

}
#ex4Slider .slider-selection {
	background: #BABABA;
}
.slider.slider-vertical {
    height: 57px;
    width: 20px;
    margin-top: -49px;
}
.slider.slider-vertical {
    height: 57px;
    width: 20px;
    margin-top: -49px;
}
@-webkit-keyframes blink {
    from { color: black; }
    to { color: antiquewhite; }
  }
  @-moz-keyframes blink {
    from { color: black; }
    to { color: antiquewhite; }
  }
  @-ms-keyframes blink {
    from { color: black; }
    to { color: antiquewhite; }
  }
  @-o-keyframes blink {
    from { color: black; }
    to { color: antiquewhite; }
  }
  @keyframes blink {
    from { color: black; }
    to { color: antiquewhite; }
  }

 .blink{
 	color: black;
    -webkit-animation: blink 2s 3 alternate;
     -moz-animation: blink 2s 3 alternate;  
     -ms-animation: blink 2s 3 alternate;  
     -o-animation: blink 2s 3 alternate;  
     animation: blink 2s 3 alternate;
}
	</style>




</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
  <!-- <a class="navbar-brand" href="#">Navbar</a> -->
  <!-- <img src="https://theaxxstablet.com/bower_components/admin-lte/images/logo.png"> -->
  <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button> -->

 
    <!-- <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">TheAxxsTablet <span class="sr-only">(current)</span></a>
      </li>
    </ul> -->
   <div class="form-inline my-2 my-lg-0 ml-auto">
      <input class="form-control mr-sm-2 gradient"  type="text"  id="search_key" placeholder="Search" required="required" style="background-color: #020202;color:white;border-radius: 23px;" >
      <button class="btn btn-secondary my-2 my-sm-0" style="border-radius: 23px;background-color: black;" type="submit"  onclick="myFunction()"><i class="fa fa-search" aria-hidden="true"></i></button>
    </div>
   

  
</nav>
	<br><br><br>
	
<div class="container">
	
	<div class="container-fluid fixed-top " style="height: 102px;width: calc(100% - 260px); background:black;border-radius: 10px;border-style: solid;top: 56px;color:white;border-color: #212529"  >
		<div class="row" >

			<div class="col-md-10">
				<div class="col-sm-12">
					<marquee><span id="song_info" >Search and Play</span></marquee>
					<div style="height: 24px">
					<center><span class="pause" id="pause" style="display: none;">Paused</span></center>
					</div>
				</div>
				
					
				


				<div class="row" >
					<div class="col-md-5" style="top: -12px;"> <span id="demo1" class="demo" style="font-size: 30px;">00:00</span> <span id="ttime">/ 00:00</span><div class="progress" style="height: 6px;width: 105px">
					  <div class="progress-bar" role="progressbar" id="pro_bar" style="width: 0%;background-color: #17a2b8" aria-valuenow="25" aria-valuemin="0" aria-valuemax="20"></div>
					</div></div>
					<samp style="font-size: 13px;">Autoplay</samp> &nbsp;
					<input type="checkbox" style="zoom:1.5"  id="is_autoplay">
					
    			
    		
					<div class="col-md-1"><button class="btn btn-info" style="border-radius: 5px 18px;" onclick="playPre()">Prev</button></div>&nbsp;&nbsp;&nbsp;&nbsp;

					<div class="col-md-1"><button class="btn btn-info player" style="border-radius: 42px;"><i class="fa fa-play" aria-hidden="true"></i></button></div>&nbsp;

					<div class="col-md-1"><button class="btn btn-info" id="playbtn" onclick="playNext()" style="border-radius: 18px 5px;">Next</button></div>&nbsp;
					<div class="col-md-1" id="bar"  style="top: 12px;display: none;fill: white;margin-left: 9px;">

						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
						  <rect class="eq-bar eq-bar--1" x="4" y="4" width="3.7" height="8"/>
						  <rect class="eq-bar eq-bar--2" x="10.2" y="4" width="3.7" height="16"/>
						  <rect class="eq-bar eq-bar--3" x="16.3" y="4" width="3.7" height="11"/>
						</svg>
						
					</div>

				</div>
				
			</div>
			<!-- <div class="col-md-"></div> -->
			<div class="col-sm-2"><img id="song_img" class="spinning-circle"  style="height: 95px;
    width: 97px;float: right;border-radius: 62px;display: none;"></div>

    		
    		

			

		</div>
		
	</div>
<br><br><br>
	<div class="container-fluid">
	<div class="row">
	<div class="col-md-3 sticky-top"> <span id="player_msg" class="pause" style="position: fixed;"></span></div>
	<div class="col-md-12 main_div" style="top: 9px;float: left;
    width: 1000px;
    overflow-y: auto;
    height: 768px;">
		<div class="list_div"  style="border-radius: 10px;border-style: solid;min-height: 768px;">
		<table id="song_table" class="table  table-striped ">
		  <thead>
		    <tr>
		      
		      
		    </tr>
		  </thead>
		  <tbody>
		  </tbody>
		</table>
		</div>

	</div>


</div>
</div>
	

	

	
</div>
<div id="cover" style="display: none;"></div>

<script src="https://connect.soundcloud.com/sdk/sdk-3.3.2.js"></script>
<script>
SC.initialize({
  client_id: '{{$client_id}}'
});

var dur;
var audio_date;
var max_date = '{{$setting['s_uptodate']}}';
var id_array = [];
var bl_word = '{{$bl_word}}';
var blacklist = bl_word.split(',');
var bl_found;
var sub_key;
var is_bl_search;
function myFunction(){
	console.log(bl_word);
	$("#song_img").css('display','none');
	localStorage.removeItem('songid');
	$('.player').attr('sid','');
	id_array=[];
	$('.player').html('<i class="fa fa-play" aria-hidden="true"></i>');
	var key = $("#search_key").val();
	if (key.length == 0) {
		return false;
	}
	//check for blacklisted word
	sub_key = key.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'').toLowerCase();
	var array_subkey = sub_key.split(' ');
	console.log(array_subkey);
	
	 $.each(array_subkey,function(ind,val){/*
			console.log(v);*/
			is_bl_search = 0;
			var dd = blacklist.indexOf(val) > -1;
			if (dd == true) {
				is_bl_search = 1;
				return;
							}
		});
	 console.log(is_bl_search);
	 if (is_bl_search == 1 ) {
	 	return false;
	 }
	/*$('#cover').css('display','block');*/
	$('#menu li').remove();
	$('#menu br').remove();
	$("#song_table tbody> tr").remove();
	if ($.isFunction(stream.kill)) {
		stream.kill();
		$('#bar').css('display','none');
	}
	var genre = '{{$setting['genres']}}';
	var gen = genre.split(',');
	$.each(gen,function(index,gen){
		SC.get('/tracks', {
		  q: key,
		  limit : '{{$setting['s_limit']}}',
		  genres: gen
		}).then(function(tracks) {
			if (tracks.length > 0) {
			$.each(tracks,function(index,value){
				bl_found = 0;
				/* $("#menu").append('<li><a href="#">'+value.title+'</a>  <input class="btn btn-info" type="button" value="Play" id="'+value.id+'" onclick="playSong('+value.id+')" ></li><br>');*/
				/*id_array.push(value.id);*/
				if(value.artwork_url == null) {
					value.artwork_url = "{{url('/images/no_image.jpg')}}";
				}
				audio_date = moment(value.created_at).format("YYYY-MM-DD");
				if (audio_date <= max_date) {
					id_array.push(value.id);
					text = value.title;
					var duration = getTime(value.duration);
					text = text.substr(0,40);
					str_title = value.title.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
					/*console.log(str_title);*/
					var ar = str_title.toLowerCase().split(' ');
					$.each(ar,function(i,v){/*
						console.log(v);*/
						var dd = blacklist.indexOf(v) > -1;
						if (dd == true) {
							bl_found = 1;
							return;
											}
					});
					if (bl_found == 1) {
						return true;
					}
					 $('#song_table').append('<tr><td><img class="img-thumbnail"  id="sgi'+value.id+'" src="'+value.artwork_url+'"><button class="btn btn-info playthumb" type="button" value="Play" id="'+value.id+'" onclick="playSong('+value.id+')" ><i class="fa fa-play" aria-hidden="true"></i></button></td><td id="sn'+value.id+'" style="font-size:14px;color:white"><span id="snt'+value.id+'" >'+text+'</span><br><span style="top:21px;position:relative;color:grey">'+duration+'</span></td><td><span style="font-size: 11px;font-weight:600;color:grey">Genre <br> '+value.genre+'</span></td></tr>');
				}
				
				/*console.log(value.title);*/
				$('#cover').css('display','none');
			});
			$('.player').attr('sid',id_array[0])
		}


			

		  /*console.log(tracks);*/
		  $("#song_table >tbody >h3").empty();
		  
		});
	});
	/*console.log(id_array.length);*/
	/*if (id_array.length === 0) {
		  	$('#cover').css('display','none');
		  	$('#song_table').append('<h3 class="pause" id="no_song">No Results Found</h3>')
		  	$('#song_table').append('<img src="images/no_result.gif" style="display:block; margin: 0 auto;border-radius: 75px;background:white;" >')
		  }*/
	
};
function getTime($ms){
	var minutes = Math.floor($ms / 60000);
	  var seconds = (($ms % 60000) / 1000).toFixed(0);
	 return  minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
}

function playSong($songid){
	$("tr>td:nth-child(2)").removeClass('blink');
	$('tr>td>button').addClass('btn btn-info');
	$('td>button').addClass('btn btn-info');
	$('td>button').html('<i class="fa fa-play" aria-hidden="true">');
	var check = localStorage.getItem("songid");
	
	/*console.log($("#snt"+$songid).text());*/
	$('.player').attr('sid', $songid);

	$("#song_info").text($("#snt"+$songid).text());
	
	$("#song_img").attr('src',$("#sgi"+$songid).attr('src'));
	if (check == $songid) {
		if ($.isFunction(stream.isPlaying)) {
			is_play =stream.isPlaying();
			is_buffering = stream.isBuffering();
			if (is_play == true) {
				$('#pause').css('display','block');
				$('#bar').css('display','none');
				stream.pause();
				$('#'+$songid).html('<i class="fa fa-play" aria-hidden="true"></i>');
				$('.player').html('<i class="fa fa-play" aria-hidden="true"></i>');
			}else if(is_play == false){
				stream.play();
				$('#bar').css('display','block');
				$('#pause').css('display','none');
				$('#'+$songid).attr('class','btn btn-success playthumb');
				$('#'+$songid).html('<i class="fa fa-pause" aria-hidden="true"></i>');
				$('.player').html('<i class="fa fa-pause" aria-hidden="true"></i>');
			}
			/*console.log(stream.getVolume());*/
		}
			
			
	}else {
	SC.stream('/tracks/'+$songid).then(function(player){
		stream = player;
		/*console.log(player.getDuration());*/
		  player.play().then(function(){
		  	$('#bar').css('display','block');

		  	localStorage.setItem("songid", $songid);
		  	$('#'+$songid).attr('class','btn btn-success playthumb');
		  	$('#sn'+$songid).addClass("blink");
		  	$('#'+$songid).html('<i class="fa fa-pause" aria-hidden="true"></i>');
		  	$('.player').html('<i class="fa fa-pause" aria-hidden="true"></i>');
		  	 dur = stream.getDuration();
		  	getDuration = getTime(dur);
		  	$("#ttime").html('/ '+getDuration);
		  	stream.on('finish', function(){
		  		var is_autoplay = $('#is_autoplay').is(':checked'); ;
		  		if (is_autoplay == true) {
		  					$("#playbtn").trigger("click");
		  				}else {
		  					$('#bar').css('display','none');
		  					$('#'+$songid).html('<i class="fa fa-play" aria-hidden="true"></i>');
		  				}
		  	});
   		 console.log('Playback started!');
	  }).catch(function(e){
	   		 console.error('Playback rejected. Try calling play() from a user interaction.', e);
	  });
});
	}

	$("#song_img").css('display','block');

	window.setInterval(function(){
		is_playing =stream.isActuallyPlaying();
		state = stream.getState();
		

		if(is_playing){
			
			getsongcurrent = stream.currentTime();
			var seek = Math.trunc((getsongcurrent/dur)*100);
			$("#pro_bar").css('width',''+seek+'%');
			var time = getTime(getsongcurrent);	  	
		   $("#demo1").html(time);
		};
	}, 1000);

	/*window.setInterval(function(){
		is_buffering = stream.isBuffering();
		console.log('is_buffering'+is_buffering);
		state = stream.getState();
		console.log(state);
		var is_autoplay = $("#is_autoplay").val();
		if (state == 'ended' && is_autoplay == 1) {
			console.log(id_array);
			$("#playbtn").trigger("click");
		
		}
	}, 10000);*/
	
}


$('.player').click(function(event) {
		if (id_array.length > 1) {
			console.log('sid added to play button');
			playSong($(this).attr("sid"));
		}
               
});

function playNext(){
	var sid = $('.player').attr('sid');
	console.log('current_song_id'+sid);
	/*console.log(check);*/
	var song_index = id_array.indexOf(parseInt(sid));
	var next_songid = id_array[song_index+1];
	console.log('next'+next_songid);
	if (id_array.length > 1) {
	if (next_songid === undefined) {
		$("#player_msg").html('PlayList End');
		$('#player_msg').css('display','block');
		$('#player_msg').delay(5000).fadeOut('slow');
		return false;
	}
	playSong(next_songid);
	}

}

function playPre(){
	var sid = $('.player').attr('sid');
	var song_index = id_array.indexOf(parseInt(sid));
	var next_songid = id_array[song_index-1];
	if (id_array.length > 1) {
	if (next_songid === undefined) {
		$("#player_msg").html('First Song');
		$('#player_msg').css('display','block');
		$('#player_msg').delay(5000).fadeOut('slow');
		return false;
	}
	playSong(next_songid);
}



}
 $(function() {
 $('#toggle-event').change(function() {
      var is_autoplay = $("#is_autoplay").val();
      if (is_autoplay == 1) {
      	$("#is_autoplay").val('0');
      } else {
      	$("#is_autoplay").val('1');
      }

    })

});

/*$("#search_key").keypress(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });*/
 // SC.on("finish", function(){ alert("Hello World!"); });

</script>
</body>
</html>

