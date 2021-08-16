<!DOCTYPE html>
<html>
<head>
	<title>Axxs News</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<style type="text/css">
    body{
      background: #343a40;
    }
  </style>
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <!-- <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li> -->
        <li class="nav-item active">
          <!-- <a class="nav-link" href="{{url('/axxs_news/top_headlines')}}">Top HeadLines</a> -->
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Everything</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Source</a>
        </li> -->
      </ul>
     
    </div>
  </nav>

  <section>
<br>
    <div class="container">
      <div class="row">
        <button class="btn btn-primary  btn-sm" onclick="goBack()" ><i class="fa fa-arrow-left bck" aria-hidden="true"></i> Back</button>
      </div>
      <br>
      
      
    </div>
  	<div class="container jumbotron">
  		<!-- image container -->

      <div class="row">
        <div class="col-md-6">
          <img src="{{$data['img']}}" class="img-fluid img-thumbnail" alt="Responsive image">
        </div>

            <div class="col-md-6">
              <div class="col-md-12">
                <h3>{{$data['title']}}</h3>
              </div>

              <div class="col-md-12">
                <h6>{{$data['description']}}</h6>
              </div>
              
            </div>
        
        <div class="col-md-12">
          <p>{{$data['content']}}</p>         
        </div>

      </div>
  	</div>

<script type="text/javascript">
  function goBack() {
  window.history.back();
}
</script>
</body>
</html>