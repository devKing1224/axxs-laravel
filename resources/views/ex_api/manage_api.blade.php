@extends('layouts.default')
@section('content')    
<!-- Content Wrapper. Contains page content -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha256-ENFZrbVzylNbgnXx0n3I1g//2WeO47XxoPe0vkp3NC8=" crossorigin="anonymous" />
<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color:#3584e6;
  }
  .b-m-l {
    margin-left: 8%;
  }
  .select2-selection__rendered {
    padding: 0 0px !important;
  }
  .tooltip.show span {
   text-align:left;
 }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            News API
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">API</a></li>
            <li class="active">
                News API
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content service">
        <div class="row">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">News API</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    
                        
                        <form class="form-horizontal" role="form" method="POST" action="{{url('/update_api')}}"><br>
                            {{ csrf_field() }}
                            <input type="hidden" name="api_name" value="newsapi">
                           <div class="form-group">
                              <label for="name" class ="control-label col-sm-3">API Key</label>
                            <div class="col-sm-4">
                              <input type="name" class="form-control" id="news_apikey" placeholder="Enter API KEY" required="" name="api_key" value="{{$api_key->api_key or ''}}">
                            </div>
                            <div class="col-sm-2">
                              <input type="button" class="btn btn-success" onclick="validateKey()" value="Validate Key">
                            </div>
                            </div>
                          </form>
                          <form class="form-horizontal" id="add_news_config" method="Post" action="{{url('/addnews_config')}}">
                            <div class="form-group">
                               <label for="address" class ="control-label col-sm-3">Facility</label>
                             <div class="col-sm-4">
                               <select class="form-control" name="facility_id" id="fac_select" required>
                                 <option value="" disabled selected>Select Facility</option>
                                 @foreach($facility as $fac)
                                   <option value="{{$fac['id']}}">{{$fac['facility_name']}}</option>
                                   @endforeach
                               </select>
                             </div>
                             </div>
                           <div class="form-group">
                              <label for="address" class ="control-label col-sm-3">Country</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="country" required id="country">
                                  <option value="ae">ae</option>
                                  <option value="ar">ar</option>
                                  <option value="at">at</option>
                                  <option value="au">au</option>
                                  <option value="be">be</option>
                                  <option value="bg">bg</option>
                                  <option value="br">br</option>
                                  <option value="ca">ca</option>
                                  <option value="ch">ch</option>
                                  <option value="cn">cn</option>
                                  <option value="co">co</option>
                                  <option value="cu">cu</option>
                                  <option value="us">us</option>
                              </select>
                            </div>
                            </div>
                           <div class="form-group">
                              <label for="email" class ="control-label col-sm-3">Category</label>
                            <div class="col-sm-4">
                              <select class="form-control js-example-basic-multiple" name="category[]" required id="news_cat" multiple="multiple">
                                  @foreach($categories as $category)
                                  <option value="{{$category->name}}">{{$category->name}}</option>
                                  @endforeach
                              </select>
                              <small>Note* The order in which you select the category is the order in which it appears to the inmates.</small>
                              
                            </div>
                            
                            </div>
                           <div class="form-group">
                              <label for="pwd" class ="control-label col-sm-3">Total API News</label>
                                <div class="col-sm-4">
                                  <input type="number" class="form-control" name="n_limit" id="n_limit" placeholder="Enter the total number of news from API" value="" required="required" min="1" max="100">
                                </div>
                            </div>
                            <div class="form-group">
                              <label for="pwd" class ="control-label col-sm-3">Per Page</label>
                                <div class="col-sm-4">
                                  <input type="number" class="form-control" name="news_per_page" id="news_per_page" placeholder="Enter the number of news per page" value="" required="required">
                                </div>
                            </div>
                            <!--<div class="form-group">
                              <label for="pwd" class ="control-label col-sm-3">Total API News</label>
                                <div class="col-sm-4">
                                  <input type="number" class="form-control" name="total_api_news" id="total_api_news" placeholder="Enter Total Number of News From API" value="" required>
                                </div>
                            </div>-->
                            <div class="form-group">
                              <label for="pwd" class ="control-label col-sm-3" >Allow Search</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="allow_search" id="n_search" required="required">
                                  <option value="1" >Yes</option>
                                  <option value="0" >No</option>
                              </select>
                            </div>
                            </div>
                           <div class="box-footer text-center b-m-l">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="<?php if(isset($serviceInfo)){ ?>serviceEditDataSend<?php } else { ?>movieAddDataSend<?php } ?>">Submit</button>
                            </div>
                        </div>
                        </form>
                    
                    
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script type="text/javascript">

    function validateKey(){
        var api_key = $("#news_apikey").val();
        var pUrl = 'https://newsapi.org/v2/everything?q=bitcoin&apiKey='+api_key;
        $.ajax({
              type : 'GET',
              url : pUrl,
              dataType : 'json',
              success : function(data){                                               
                  alert("Success - Key Validated");
              },
            error: function(xhr, status, error){
                /*var errorMessage = xhr.status + ': ' + xhr.statusText*/
                 alert('Error - Invalid Key');
            }   
        });
    }

        $(document).ready(function(){
            $("#fac_select").change(function(){
            $("#news_cat option").prop("selected", false);
             $("#n_limit").val('');
             $("#news_per_page").val('');
            var facility_id = $(this).children("option:selected").val();
              $.ajax({
                      type : 'GET',
                      url : '{{url("/scfacilitynewssetting")}}'+'/'+facility_id,
                      dataType : 'json',
                      success : function(data){
                                                              
                          // Splict the news cat and show into the select box
                          var cat = data.category.split(',');
                          
                            
                          $.each(cat, function(index, value){
                            $("#news_cat option[value='"+value+"']").remove();
                            $("#news_cat").append('<option selected value="'+value+'">'+value+'</opttion>');
                          });
                          // End splict section
                          $("#n_limit").val(data.n_limit);
                          $("#news_per_page").val(data.news_per_page);
                          if (data.allow_search == 1) {
                            search = 'Yes';
                          }
                          else{
                            search = 'No';
                          }
                          $("#n_search option[value='"+data.allow_search+"']").remove(); 
                          $("#country option[value='"+data.country+"']").remove();           
                          $("#country").append('<option selected value="'+data.country+'" >'+data.country+'</option>')
                          
                          $("#n_search").append('<option selected value="'+data.allow_search+'" >'+search+'</option>')
                      },
                  error: function(xhr, status, error){
                          /*var errorMessage = xhr.status + ': ' + xhr.statusText*/
                          /* alert('Error - Invalid Key');*/
                  }   
                  });
                })
        });

$("#add_news_config").submit(function(e) {
  e.preventDefault(); // avoid to execute the actual submit of the form.
  var form = $(this);
  var url = form.attr('action');
  
  $.ajax({
     type: "POST",
     url: url,
     data: form.serialize(), // serializes the form's elements.
     success: function(data)
     {
          toastr.success('Configuration Updated!', 'Success');// show response from the php script.
     }
  });
});
//Add the multiple selecttable dropdown
$('.js-example-basic-multiple').select2({
    placeholder: "Please select the category",
    allowClear: true
});

/*$('#news_cat').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var latest_value = $("option:selected:last",this).val();
    var valueSelected = this.value;
    var notSelected = $("#news_cat").find('option').not(':selected');
     unselected = notSelected.map(function () {
        return this.value;
    }).get();
     selected.push(latest_value);
    const filteredArray = selected.filter(function(x) { 
      return unselected.indexOf(x) < 0;
    });
    
    console.log(selected);
   
    
  

});*/



$(".js-example-basic-multiple").on("select2:select", function (evt) {
  var element = evt.params.data.element;
  var $element = $(element);
  
  $element.detach();
  $(this).append($element);
  $(this).trigger("change");
});


</script>
@stop