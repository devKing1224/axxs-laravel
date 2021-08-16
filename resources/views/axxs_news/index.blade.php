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
.newsdiv{
  cursor: pointer;
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
.font-increase { font-size: 24px; }

/*Pagination CSS*/
.simple-pagination {
  margin-bottom: 0%;
  font-size: 24px;
}
.simple-pagination ul {
  margin: 0 0 20px;
  padding: 10px;
  list-style: none;
  text-align: center;
}

.simple-pagination li {
  display: inline-block;
  margin-right: 5px;
}

.simple-pagination li a,
.simple-pagination li span {
  color: #666;
  padding: 5px 10px;
  text-decoration: none;
  border: 1px solid #EEE;
  background-color: #FFF;
  box-shadow: 0px 0px 10px 0px #EEE;
}

.simple-pagination .current {
  color: #FFF;
  background-color: #007bff;
  border-color: #007bff;
}

.simple-pagination .prev.current,
.simple-pagination .next.current {
  background: #007bff;
}
.b-m-r{
  margin:0px 0px 0px 4px;
}
</style>
</head>
<body>

  

  <section >
    <div class="container">
      <!-- Top News -->
      <div class="card">
        <div class="card-body">
          <center><h3>{{$title}}</h3>
            @if(isset($msg))
              <h4 style="color: red;">{{$msg}}</h4>
          @elseif(count($article) < 1 && empty($data))
            <h4 style="color: red;">Sorry No results found</h4>
          @elseif(!empty($data))
            <h4 style="color: red;">Sorry {{$data['code']}}</h4>
          @endif
          </center>
        </div>
      </div>

      <!-- Filter and search section start here -->
      <div class="row">
        @if($allow_search == 1)
        <!-- Filter by category section-->
        
          <div class="col-md-6">
            <div class="filter-group text-right">
              <form method="post" name="news_filter" id="news_filter">
                <input type="hidden" name="n_flag" value="1"/>
                <select class="form-control font-increase" id="news_category" name="news_by">
                  <option value="">Select the category </option>
                  @if(count($assign_category) > 0)
                    @foreach($assign_category as $category)
                      <option value="{{$category}}" @if( ucwords($category) == ucwords($search_cat)) selected @endif> {{ucwords($category)}} </option>
                    @endforeach
                  @endif
                </select>
              </form> 
            </div>
          </div>
          
        <!-- Search section-->
          <div class="col-md-6 text-right">
            <form class="form-inline" method="Post" action="{{url('api/axxs_news/top_headlines/')}}/{{$facility_id}}">
              <input class="form-control col-md-10 font-increase" type="search" placeholder="Search" aria-label="Search" name="key" required="" @if(isset($search_key) && !empty($search_key)) value="{{$search_key}}" @endif>
              <button class="btn btn-outline-info my-2 my-sm-0 b-m-r font-increase" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
          </div>
          @endif
        </div>
      <p class="clearfix"></p>
      <!-- Filter and search section end here -->
      <div class="row main-row">
        @foreach($article as $key=>$articles)
          <div class="col-md-4 newsdiv main-sec" onclick="showNews('news{{$key}}')">
            <div class="card ">
              <img class="card-img-top" height="161" width="288" id="imgnews{{$key}}" src="{{ $articles['urlToImage'] ? $articles['urlToImage'] : asset('images/no-image.png') }}" alt="Card image cap">
              <div class="card-body" style="min-height: 120px;">
                <h6 class="card-text" id="titlenewss{{$key}}" >{{substr($articles['title'],0,40)}}</h6>
                <input type="hidden" name="" id="titlenews{{$key}}" value="{{$articles['title']}}">
                <div class="row">
                  <div class="col-md-12"><i class="fa fa-calendar" aria-hidden="true"></i> {{ ($articles['publishedAt']) ? date('m/d/Y', strtotime($articles['publishedAt'])) : "N/A" }} </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="" id="cntntnews{{$key}}" value="{{$articles['content']}}">
            <input type="hidden" name="" id="dscpntnews{{$key}}" value="{{$articles['description']}}">
          </div>  
        @endforeach
      </div>
      @if(count($article) > 0 )
      <div id="pagination-container"></div><br>
      @endif
    </div>

 

  <!-- hidden form to open new page -->
  <form method="POST" action="{{url('/getnews')}}" id="myForm">
    <input type="text" hidden="" name="img" id="n_img">
    <input type="text" hidden="" name="title" id="n_title">
    <input type="text" hidden="" name="description" id="n_desc">
    <input type="text" hidden="" name="content" id="n_cnt">
  </form>
  </section>
  <script type="text/javascript">
    function showNews($id){
     // $("#myModal").modal();
      $("#n_img").val($('#img'+$id).attr('src'));
      $("#n_title").val($('#title'+$id).val());
      $("#n_cnt").val($('#cntnt'+$id).val());
      $("#n_desc").val($('#dscpnt'+$id).val());
      $("#myForm").submit();

    }
    //Add the jquery pagination
    var items = $(".main-sec");
    var numItems = items.length;
    console.log(numItems);
    var perPage = parseInt('<?php echo ($news_per_page) ? $news_per_page : '9';?>');
    
    items.slice(perPage).hide();
    $('#pagination-container').pagination({
        items: numItems,
        itemsOnPage: perPage,
        prevText: "&laquo;",
        nextText: "&raquo;",
        onPageClick: function (pageNumber) {
            var showFrom = perPage * (pageNumber - 1);
            var showTo = showFrom + perPage;
            

            items.hide().slice(showFrom, showTo).show();
        }
    });
    //Submit the form using the jquery on onchnage event
    $(document).ready(function(){
        $('#news_category').change(function(){
          this.form.submit();
        });
    });
  </script>
</body>
</html>