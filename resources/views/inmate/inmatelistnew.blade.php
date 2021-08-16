@extends('layouts.default')
@section('content') 
 <style type="text/css">
     #loader {
  border: 7px solid #f3f3f3;
  border-radius: 50%;
  border-top: 8px solid #3498db;
  width: 40px;
  height: 40px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  margin-left:250px;
  margin-top:250px;
  display:block;
margin: 0 auto;


} 
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
 </style>
<script type="text/javascript">
    var base_url = window.location.origin;
    jQuery.fn.addSortWidget = function(options){
    var defaults = {
        img_asc: base_url+"/bower_components/img/no_sort.gif",
        img_desc: base_url+"/bower_components/img/no_sort.gif",
        img_nosort: base_url+"/bower_components/img/no_sort.gif",
    };
    
    var options = $.extend({}, defaults, options),
        $destElement = $(this),
        is_asc = true;
        
    $("th", $destElement).each(function(index){ // to each header cell (index is useful while sorting)

        $("<img>")                              // create image that allows you to sort by specific column 
            .attr('src', options.img_nosort)
            .addClass('sorttable_img')
            .css({
                cursor: 'pointer',
                'margin-left': '10px',
            })
            .on('click', function(){
                
                $(".sorttable_img", $destElement).attr('src', options.img_nosort); 
                $(this).attr('src', (is_asc) ? options.img_desc : options.img_asc);
                is_asc = !is_asc;
                
                var rows = $("tr", $destElement).not(":has(th)").get();

                 // save all rows (tr) into array (.get())
                rows.sort(function(a, b){               
                    // sort array with table rows
                    var m = $("td:eq(" + index + ")", a).text(); // get column you needed by using index of th element (closure)
                    var n = $("td:eq(" + index + ")", b).text();
                    if (is_asc)
                        return m.localeCompare(n); // asc
                    else
                        return n.localeCompare(m); // desc
                });
                
                var tbody = ($destElement.has("tbody")) ? "tbody" : ""; // check if table has tbody
                for (var i=0; i<rows.length; i++){
                    $(tbody, $destElement).append(rows[i]); // add each row to table (elements do not duplicate, just place to new position)
                }
            })
            .appendTo(this); // add created sort image with click event handler to current th element
    });
    
    return $destElement;

}
</script>     
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
        <h1>User List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/allusers')}}">User</a></li>
            <li class="active">User List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-4">
                            <!-- <select class="form-control" id="InmateActiveInactiveCall">
                                <option>Please select any option</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select> -->
                            @hasrole('Facility Admin')
                            <input type="hidden" name="facility_id" id="fac_id" value="{{Auth::User()->id}}">
                            @endrole
                            @can('Manage Users')
                                @if(isset($facility) && !empty($facility))
                                <select class="form-control" id="facility_user">
                                    <option> Select Facility</option>
                                    @if(count($facility) > 0)
                                        @foreach($facility as $fac)
                                            <option value="{{$fac->facility_user_id}}" @if($fac->facility_user_id == $facility_id) selected @endif>{{$fac->facility_name}}</option>
                                        @endforeach

                                    @endif
                                </select>
                            @endif
                            @endcan
                        </div>
                        <div class="col-sm-8 text-right">

                        @role('Facility Admin')
                         <a href="{{ route('all_user_service_history_report', ['id' => Auth::user()->id]) }}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i>User's Service History Report</a>
                        @endrole
                      @role('Facility Staff')
                      <input type="hidden" name="facility_id" id="fac_id" value="{{Auth::User()->admin_id}}">
                         <a href="{{ route('all_user_service_history_report', ['id' => Auth::user()->admin_id]) }}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i>User's Service History Report</a>
                        @endrole
                        @role('Super Admin')
                        <a href="{{action('ExcelController@familyReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> User's Family List</a>
                        <a href="{{action('InmateController@addInmateUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> User</a>
                        @else
                        @can('Download User Family List Report')
                        <a href="{{action('ExcelController@familyReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> User's Family List</a>
                        @endcan
                        @can('Manage Users')
                        <a href="{{action('InmateController@addInmateUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> User</a>
                        @endcan
                        @endrole  


                    </div>  
                    </div>
                    

                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->

                        <br>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-sm-12">
                        <table id="userlist-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                     @hasanyrole('Facility Admin|Facility Staff')
                                    @else
                                    <th>Facility Name</th>
                                    <th>Location</th>
                                    @endhasanyrole
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Birthday</th>
                                    <th>Username</th>
                                    <th>Balance ($)</th>
                                    <th>Logging Enabled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                               
                            
                        </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- Modal -->
<div id="blockservicemodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Block Services</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="bs_form" action="{{url('blockuserservice')}}">
        {{ csrf_field() }}
        <input type="hidden" name="inmate_id" value="" id="inmate_id">
        <div class="form-group">
             <label for="name" class ="control-label col-sm-4">Suspension Start Date</label>
            <div class="col-sm-8">
              <input type="text" name="start_date" class="form-control" id="datepicker" placeholder="Select Start Date" required="required">
            </div>
        </div>

        <div class="form-group">
             <label for="name" class ="control-label col-sm-4">Suspension End Date</label>
            <div class="col-sm-8">
              <input type="text" name="end_date" class="form-control" id="datepicker2" placeholder="Select End Date" required="required">
            </div>
        </div>
        <span id="date_error" style="color: red" hidden>* End Date should be greater than Start Date</span>
         <button style="float:right;" type="submit" class="btn btn-info">Submit</button>
        
        </form>
        <br>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>

  </div>
</div>
    </section>
    <!-- /.content -->
</div>

<div id="imageUploadModal"  data-backdrop="static" class="modal fade example-modal" role="dialog"> 
    <div class="modal-content modal-dialog modal-primary">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="font-size:-webkit-xxx-large;" id="patientImageUploadModalCancelBtn">&times;</button>
            <h4 class="modal-title">Email Content</h4>
        </div>
        <div class="modal-body">

            <div class="row">                        
                <div class="col-xs-12">
                    <div class="upload-profile-popup clearfix">                               
                        <form action="javascript:;" method="post" id="InmateSetMaxForm">
                            {{ csrf_field() }}

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="max_email" for="exampleInputEmail1">Maximum Email limit</label>

                                        <input type="hidden" class="form-control" name="user_id" id="user_id">
                                        <input type="text" class="form-control" name="max_email" value="0" id="max_email" placeholder="Please enter Max Limit">
                                    </div>
                                </div>  
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="max_phone" for="exampleInputEmail1">Maximum Contact Number limit</label>
                                        <input type="text" class="form-control" name="max_phone" value="0" id="max_phone" placeholder="Please enter Max Limit">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="button" class="btn btn-success setmaxlimitbtn" value="Set Limit">
                                    </div>
                                </div>
                            </div>
                        </form>                                 
                    </div>
                </div>
            </div>    
            <!-- This Screen FOr Card Details FOr Patient-->
        </div>
    </div>
</div>
<script src="{{ asset('/assets/js/customJS/inmatenew.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $('#facility_user').on('change', '', function (e) {
        var optionSelected = $("option:selected", this);
        var facility_user_id = this.value;
        sessionStorage.setItem("facility_id", facility_user_id);
        if (facility_user_id ==  'Select Facility') {
            sessionStorage.removeItem("facility_id");
            return false;
        } 
        var base_url = window.location.origin;
        $('#userlist-table').DataTable({
             language: {
            processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
            },
            oLanguage: {sProcessing: "<div id='loader'></div>"},
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: base_url+'/getfacilityuser/'+facility_user_id,

            columns: [
            {data: 'DT_RowIndex'},
            {data: 'facility_name'},
            {data: 'location'},
            {data: 'first_name'},
            {data: 'last_name'},
            {data: 'date_of_birth'},
            {data: 'username'},
            {data: 'balance'},
            {data: 'log_check'},
            {data: 'action',name: 'action',orderable: false, searchable: false},


        ],
        language: {
        searchPlaceholder: "Search Users"
    },
        order: [[3, 'asc']]
        });

   

   /*window.location.href = '{{URL::to('allusers')}}'+'/'+facility_user_id;*/
});
</script>

<script type="text/javascript">
    
    $("#userlist-table").addSortWidget();
    $(document).ready(function(){
        var is_admin = '{{Auth::User()->role_id}}'
        var session_fi = sessionStorage.getItem("facility_id");
        var fac_id = $("#fac_id").val();
        var base_url = window.location.origin;
        if (fac_id != null) {
            var base_url = window.location.origin;
                $('#userlist-table').DataTable({
                     language: {
                    processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
                    },
                    oLanguage: {sProcessing: "<div id='loader'></div>"},
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: base_url+'/getfacilityuser/'+fac_id,

                    columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'first_name'},
                    {data: 'last_name'},
                    {data: 'date_of_birth'},
                    {data: 'username'},
                    {data: 'balance'},
                    {data: 'log_check'},
                    {data: 'action',name: 'action',orderable: false, searchable: false},


                ],
                language: {
                searchPlaceholder: "Search Users"
            },
                order: [[3, 'asc']]
                });
        }
        if (session_fi != null && fac_id == null && is_admin == 1) {
            $('#facility_user').val(session_fi).change();
                
                $('#userlist-table').DataTable({
                     language: {
                    processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
                    },
                    oLanguage: {sProcessing: "<div id='loader'></div>"},
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: base_url+'/getfacilityuser/'+session_fi,

                    columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'facility_name'},
                    {data: 'location'},
                    {data: 'first_name'},
                    {data: 'last_name'},
                    {data: 'date_of_birth'},
                    {data: 'username'},
                    {data: 'balance'},
                    {data: 'log_check'},
                    {data: 'action',name: 'action',orderable: false, searchable: false},


                ],
                language: {
                searchPlaceholder: "Search Users"
            },
                order: [[3, 'asc']]
                });
        }
      });
    $("#bs_form").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var start_date = $("#datepicker").val();
        var end_date = $("#datepicker2").val();

        if (start_date > end_date) {
            $("#date_error").show();
             setTimeout(function() {
               $('#date_error').hide();
             }, 3000);

            return false;
        }

        var form = $(this);
        var url = form.attr('action');
        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               success: function(data)
               {    
                   
                   $('#blockservicemodal').modal('hide');
                   if (data.status == 'success') {
                       swal(data.status, data.msg, data.status);
                       $('#datepicker').datepicker('setDate', null);
                       $('#datepicker2').datepicker('setDate', null);
                   }else if (data.status == 'error') {
                       swal(data.status, data.msg, data.status);
                   }
                   // show response from the php script.
               }
             });


    });
  function blockService($id){
    var APP_URL = window.location.origin;
        $.ajax({
               type: "GET",
               url: APP_URL+'/getbsdetails/' + $id,
               success: function(data)
               {    
                   
                   /*$('#blockservicemodal').modal('hide');*/
                   if (data.status == 'success') {
                       if (data.data != null) {
                       $('#datepicker').datepicker('setDate', data.data.start_date);
                       $('#datepicker2').datepicker('setDate', data.data.end_date);
                       }else{
                           $('#datepicker').datepicker('setDate', null);
                       $('#datepicker2').datepicker('setDate', null);
                       }
                       $('#blockservicemodal').modal('show');
                        $('#inmate_id').val($id);
                       
                   }else if (data.status == 'error') {
                       swal(data.status, data.msg, data.status);
                   }
                   // show response from the php script.
               }
             }); 
}

function boxDisable($user_id) {
    var log;
    if($('#lc'+$user_id).is(":checked")){
          log = 1
      }else {
          log = 0;
      }
      var APP_URL = window.location.origin;
      $.ajax({
             type: "POST",
             url: APP_URL+'/update_logcheck',
             data: {'user_id' : $user_id,'is_log': log }, // serializes the form's elements.
             success: function(data)
             {      
                /*console.log(data);
                 if (data.status == 'success') {
                     swal(data.status, data.msg, data.status);
                 }else if (data.status == 'error') {
                     swal(data.status, data.msg, data.status);
                 }*/
                 // show response from the php script.
             }
           });

}
</script>
@stop