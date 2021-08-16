<!DOCTYPE html>
<html>
<head>
  <title>Axxs News</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.js"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <style type="text/css">
  body {
    /*background: linear-gradient(45deg, black, transparent);*/
    background: lightslategray;
}

input, input:before, input:after {
      -webkit-user-select: initial;
      -khtml-user-select: initial;
      -moz-user-select: initial;
      -ms-user-select: initial;
      user-select: initial;
     }

.card {
    margin: 0 auto; /* Added */
    float: none; /* Added */
    margin-bottom: 10px; /* Added */
    background-color: #dee2e6;
}



body {
  background-color: #343a40;
}
.bg-light{
  background-color: black !important;
}
.navbar-brand{
  color:white !important;
}


</style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" >Movies</a>
  </div>
</nav>

  
    <div class="container-fluid ">
      

      <!-- Filter and search section start here -->
      
      <p class="clearfix"></p>
      <!-- Filter and search section end here -->
      <div class="row main-row">
        @foreach($movielist as $key=>$mlist)
          <div class="col-md-3 newsdiv main-sec" onclick="showNews('news{{$key}}')">
            <div class="card ">
              <img class="card-img-top" height="161" width="288" id="imgnews{{$key}}" src="{{$mlist->logo_url}}" alt="Card image cap">
              <div class="card-body" style="min-height: 120px;">
                <h6 class="card-text" id="titlenewss{{$key}}" >{{strtoupper($mlist->name)}}</h6>
                <a href="{{$mlist->movie_url}}" class="btn btn-primary">Watch Movie</a>
                <div class="row">
                  
                </div>
              </div>
            </div>
            
          </div>  
        @endforeach
      </div>
      
    </div>

 

  
  
</body>
</html>