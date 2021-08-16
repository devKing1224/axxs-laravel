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
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            SoundCloud API
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">API</a></li>
            <li class="active">
                SoundCloud API
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
                        <h3 class="box-title">SoundCloud API</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    
                        <div class="row">
                          <div class="col-md-6">
                             <form class="form-horizontal" id="add_sc_config" method="Post" action="{{url('/sc_config')}}">
                           <div class="form-group">
                              <label for="address" class ="control-label col-sm-3">Facility</label>
                            <div class="col-sm-8">
                              <select class="form-control" name="facility_id" id="fac_select" required>
                                <option value="" disabled selected>Select Facility</option>
                                @foreach($facility as $fac)
                                  <option value="{{$fac['id']}}">{{$fac['facility_name']}}</option>
                                  @endforeach
                              </select>
                            </div>
                            </div>
                           <div class="form-group">
                              <label for="email" class ="control-label col-sm-3">Genre</label>
                            <div class="col-sm-8">
                              <select class="form-control js-states  js-example-basic-multiple" name="genres[]" id="sc_genre" multiple="multiple" required>
                                
                                  @foreach($genre as $genres)
                                  <option value="{{$genres->genres}}">{{$genres->genres}}</option>
                                  @endforeach
                              </select>
                            </div>
                            </div>
                            <div class="form-group">
                              <label for="email" class ="control-label col-sm-3">Date Validation</label>
                            <div class="col-sm-8">
                            <input type="date" class="form-control" name="s_uptodate" id="sc_date" required="required">
                            </div>
                            </div>
                           <div class="form-group">
                              <label for="pwd" class ="control-label col-sm-3">Page Size</label>
                            <div class="col-sm-8">
                              <input type="number" class="form-control" name="s_limit" id="sc_limit" placeholder="Enter Page Size" required="required" >
                            </div>
                            </div>
                            <div class="form-group">
                              <label for="pwd" class ="control-label col-sm-3" >Allow Search</label>
                            <div class="col-sm-8">
                              <select class="form-control" name="allow_search" id="sc_search">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </div>
                            </div>
                           <div class="box-footer text-center">
                            <div class="col-md-7">
                                <button type="submit" class="btn btn-primary" id="<?php if(isset($serviceInfo)){ ?>serviceEditDataSend<?php } else { ?>movieAddDataSend<?php } ?>">Submit</button>
                            </div>
                        </div>
                        </form>
                          </div>
                          <div class="col-md-6">
                            <form class="form-horizontal" role="form" method="POST" action="{{url('/update_api')}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="api_name" value="music">
                           <div class="form-group">
                              <label for="name" class ="control-label col-sm-3" style="text-align: left;">API Key</label>
                            <div class="col-sm-10">
                              <input type="name" class="form-control" id="news_apikey" placeholder="Enter API KEY" required="" name="api_key" value="{{$api_key->api_key or ''}}">
                            </div>
                            <div class="col-sm-4">
                              <input type="button" class="btn btn-success" onclick="validateKey()" value="Validate Key">
                            </div>
                            </div>
                          </form>

                          <!-- Blacklistedform -->
                          
                            <form class="form-horizontal" role="form" method="POST" action="{{url('/update_bl_word')}}" id="updateBLForm">
                            {{ csrf_field() }}
                            <input type="hidden" name="api_name" value="newsapi">
                           <div class="form-group">
                              <label for="name" class ="control-label col-sm-4" style="text-align: left;">Blacklisted Keywords</label>
                            <div class="col-sm-10">
                               <select class="form-control js-states  js-example-blist-multiple" name="bl_word[]" id="bl_word" multiple="multiple" >
                              </select>
                            </div>
                            <div class="col-sm-1">
                              <i class="fa fa-plus-circle fa-2x" onClick="addBlword()" aria-hidden="true"></i>
                            </div>
                            <br><br>
                            <div class="col-sm-4">
                              <input type="hidden" name="facility_id" id="fac_id" >
                              <input type="submit" style="display: none;" id="update_blbtn" class="btn btn-success" value="Update">
                            </div>
                            </div>
                          </form>
                          </div>
                        </div>
                        
                         
                    
                    
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
   

<!-- add blacklisted word -->
<div class="modal fade" id="addBlwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Add Blacklisted Keyword</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{url('/addbl_word')}}" id="addBLForm">
          {!! csrf_field() !!}
        <div class="row">
          <div class="col-md-8">
        <input type="text" id="bl_keyword" class="form-control" name="bl_word" placeholder="Enter Blacklisted Keyword" required="required" autocomplete="off" placeholder="Enter Blacklisted Keyword" onkeyup="return forceLower(this);" onkeypress="return AvoidSpace(event)">
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
        </div>
        <span>Note:Add multiple values seperated by "," <br> eg: a,b,c</span>
        
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

    </div>
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
        $("#bl_word option").remove();
        $("#sc_genre option").prop("selected", false);
      var facility_id = $(this).children("option:selected").val();
      $("#update_blbtn").css('display','none');
        $("#fac_id").val(facility_id);
          $.ajax({
                  type : 'GET',
                  url : '{{url("/scfacilitysetting")}}'+'/'+facility_id,
                  dataType : 'json',
                  success : function(response){    
                      var data = response.data;                                           
                      if (response.code == 200) {
                      var gen = data.genres.split(',');
                      $.each(gen,function(index,value){
                        $("#sc_genre option[value='"+value+"']").remove();
                        $("#sc_genre").append('<option selected value="'+value+'">'+value+'</option>');
                      });
                      $("#sc_date").val(data.s_uptodate);
                      $("#sc_limit").val(data.s_limit);
                      if (data.allow_search == 1) {
                        search = 'Yes';
                      }
                      else{
                        search = 'No';
                      }
                      $("#sc_search option[value='"+data.allow_search+"']").remove();
                      $("#sc_search").append('<option  selected value="'+data.allow_search+'">'+search+'</option>');
                    } else{
                      $("#sc_date").val('');
                      $("#sc_limit").val('');
                      $('#sc_genre option:selected').remove();
                      //ajax call to get genres
                       $.ajax({
                  type : 'GET',
                  url : '{{url("/getgenres")}}',
                  dataType : 'json',
                  success : function(genres){
                    $('#sc_genre').find('option').remove().end()
                      $.each(genres,function(ind,val){
                          $("#sc_genre").append('<option  value="'+val.genres+'">'+val.genres+'</option>');
                      });
                  }})

                    }
                  },
              error: function(xhr, status, error){
                      /*var errorMessage = xhr.status + ': ' + xhr.statusText*/
                      /* alert('Error - Invalid Key');*/
       }   
                        });

          //get blacklisted word ajaxcall
          getBlacklistedword();
          
            })
        
    });

    $('.js-example-basic-multiple').select2({
    placeholder: "Select Genres",
    allowClear: true
});

    $('#bl_word').select2({
    placeholder: "Blacklisted keyword appears here ",
    allowClear: true
});
    

    
    $("#add_sc_config").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
               toastr.success('Configuration Updated!', 'Success'); // show response from the php script.
           }
         });


});

    /*$( "#sc_date" ).datepicker({
        minDate: 0,
        dateFormat: 'yy-mm-dd',
    });*/

    

    $("#addgenreForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
         type: "POST",
         url: url,
               data: form.serialize(), // serializes the form's elements.
               success: function(response)
               {  

                 if (response.code == 200) {
                  $("#addBlwordModal").modal('hide');
                  toastr.success(response.msg, 'Success');
                  $.ajax({
                    type : 'GET',
                    url : '{{url("/getgenres")}}',
                    dataType : 'json',
                    success : function(genres){
                      $('#sc_genre').find('option').remove().end()
                      $.each(genres,function(ind,val){
                        $("#sc_genre").append('<option  value="'+val.genres+'">'+val.genres+'</option>');
                      });
                    }})

                } else{
                  toastr.error(response.msg, 'Error');
                }
              }
            });


    });

    function addBlword(){
      var facility_id = $("#fac_select").val();
      if (facility_id == null) {
        toastr.info('Please select facility first', 'Info');
        return false;
      }
      $("#bl_keyword").val('');
      $("#addBlwordModal").modal('show');
    }

    $("#addBLForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var facility_id = $("#fac_select").val();
        if (facility_id == null) {
          toastr.error('Please select facility first', 'Error');
          return false;
        }
        var form = $(this);
        var url = form.attr('action');
        var is_duplicate = 0;
        var entered_keyword =  $("#bl_keyword").val();
        $.ajax({
         type : 'GET',
         url : '{{url("/get_blword")}}'+'/'+facility_id,
         dataType : 'json',
         success : function(response){
           if (response.Data != null) {   
            db_keyword = response.Data.split(',');
            entered_keyword = entered_keyword.split(',');
            $.each(entered_keyword,function(i,v){
              if ($.inArray(v, db_keyword) >= 0) {
                toastr.error(v+' '+'already exists', 'Error');
                is_duplicate = 1;
                return ;
              }
            });
          } 

          if (is_duplicate == 0) {
            ajaxCall2();
          }

        },
        error: function(xhr, status, error){
         /*var errorMessage = xhr.status + ': ' + xhr.statusText*/
         /* alert('Error - Invalid Key');*/
       }   
     });

        

        function ajaxCall2(){
          $.ajax({
           type: "POST",
           url: '{{url("/addbl_word")}}'+'/'+facility_id,
           data: form.serialize(),
           success: function(response)
           {  

             if (response.Code == 200) {
              $("#addBlwordModal").modal('hide');
              toastr.success(response.Message, 'Success');
              getBlacklistedword();

            } else{
              toastr.Error(response.Message, 'Error');
            }
          }
        });
        }
        


    });

  function getBlacklistedword(){
    var facility_id = $("#fac_select").val();

    $.ajax({
     type : 'GET',
     url : '{{url("/get_blword")}}'+'/'+facility_id,
     dataType : 'json',
     success : function(response){    
       console.log(response.Data);
       var data = response.Data;
       if (data != null) {
        if (response.Code == 200) {
          var bl_word = data.split(',');
          $.each(bl_word,function(index,value){
            $("#bl_word option[value='"+value+"']").remove();
            $("#bl_word").append('<option selected value="'+value+'">'+value+'</option>');
          });  
        }
        $("#update_blbtn").css('display','block');
      } else{
        $("#update_blbtn").css('display','none');
      }                                       


    },
    error: function(xhr, status, error){
     /*var errorMessage = xhr.status + ': ' + xhr.statusText*/
     /* alert('Error - Invalid Key');*/
   }   
 });

  }

  $("#updateBLForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
         type: "POST",
         url: url,
               data: form.serialize(), // serializes the form's elements.
               success: function(response)
               {  
                if (response.Code == 200) {
                  getBlacklistedword();
                  toastr.success(response.Message, 'Success');
                } else{
                  toastr.error(response.msg, 'Error');
                }
              }
            });
      });

  function uniqueValidation(){
    var facility_id = $("#fac_select").val();

    $.ajax({
     type : 'GET',
     url : '{{url("/get_blword")}}'+'/'+facility_id,
     dataType : 'json',
     success : function(response){    
       return response.Data;                                        
     },
     error: function(xhr, status, error){
       /*var errorMessage = xhr.status + ': ' + xhr.statusText*/
       /* alert('Error - Invalid Key');*/
     }   
   });
  }

  function forceLower(strInput) 
  {
    strInput.value=strInput.value.toLowerCase();
  }
  function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}
</script>
@stop