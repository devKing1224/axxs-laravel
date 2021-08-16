<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
     <title>{{ $page_title or "The Axxs Tablet" }}</title>
    
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->

    <!-- Theme style -->
  
    <meta name="_token" content="{{ csrf_token() }}" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
   
    <!--sweat alert css>-->
  
    <!-- End -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 
 
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body onload="window.scrollTo(0,0)">

	
	<div class="container">
		<!-- <iframe  style="width: 100%;
    height: 500px;"  src="{{$movie->movie_url}}"></iframe> -->
    <iframe width="560" height="315" src="{{$movie->movie_url}}" allowfullscreen></iframe>
		</div>

	<a href="{{route('movie.play')}}"><input type="button" value="Back" class="btn btn-info"></a>



</body>
</html>