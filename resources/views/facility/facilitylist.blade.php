@extends('layouts.default')
@section('content') 
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Toaster  -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/toaster/toaster.css')}}">
<script src="{{ asset('assets/toaster/toaster.js')}}"></script>
<style>
    .ui-autocomplete { 
        cursor:pointer; 
        height:120px; 
        overflow-y:scroll;
    }    
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }  
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Facility List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('facilities')}}">Facility</a></li>
            <li class="active">Facility List</li>
        </ol>
    </section>

    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-right">
                        @role('Super Admin')
                        <kbd>Export/Import Service</kbd>&nbsp;
                        <span  onclick="export_service()"><i data-toggle="tooltip" data-placement="top" title="Export Service" class="fa fa-arrow-circle-up fa-lg" aria-hidden="true"></i></span>
                        <span onclick="import_service()"><i data-toggle="tooltip" data-placement="top" title="Import Service" class="fa fa-arrow-circle-down fa-lg" aria-hidden="true"></i></span>&nbsp;&nbsp;
                        |
                        &nbsp;&nbsp;
                        <kbd>Tablet Charge</kbd>
                        <input id="toggle-three" name="devicetoggle" type="checkbox"  data-toggle="toggle" data-style="ios" data-onstyle="success" data-offstyle="danger" value="{{$device_off->content or '1'}}" data-on="ON" data-off="OFF" data-size="small" @if(isset($tb_charge->content) && $tb_charge->content == 1) checked @endif>
                        <input id="tb_charge" type="hidden" name="tb_charge" value="{{$tb_charge->content or '1'}}">
                        <input type="hidden" id="tb_charge_id" name="tb_charge_id" value="{{$tb_charge->id or ''}}">
                        &nbsp;&nbsp;
                        |
                        &nbsp;&nbsp;
                        <kbd>Automatic Email Creation</kbd>
                        <input id="toggle-two" name="devicetoggle" type="checkbox"  data-toggle="toggle" data-style="ios" data-onstyle="success" data-offstyle="danger" value="{{$device_off->content or '1'}}" data-on="ON" data-off="OFF" data-size="small" @if(isset($email_create->content) && $email_create->content == 1) checked @endif>
                        <input id="email_create" type="hidden" name="email_create" value="{{$email_create->content or '1'}}">
                        <input type="hidden" id="emailCreate_id" name="emailcreate_id" value="{{$email_create->id or ''}}">
                        &nbsp;&nbsp;
                        |
                        &nbsp;&nbsp;
                        <kbd>Device ON/OFF</kbd>
                        <input id="toggle-one" name="devicetoggle" type="checkbox"  data-toggle="toggle" data-style="ios" data-onstyle="success" data-offstyle="danger" value="{{$device_off->content or '1'}}" data-on="ON" data-off="OFF" data-size="small" @if(isset($device_off->content) && $device_off->content == 1) checked @endif>
                        <input id="device_off" type="hidden" name="alldevice_off" value="{{$device_off->content or '1'}}">
                        <input type="hidden" id="deviceoff_id" name="devicoff_id" value="{{$device_off->id or ''}}">
                        <!-- <a href="{{action('ExcelController@facilityReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Facilities</a> -->
                        <a href="{{action('FacilityController@addFacilityUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Facility</a>
                        @else
                        @can('Download Facility List Report')
                        <!-- <a href="{{action('ExcelController@facilityReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Facilities</a> -->
                        @endcan
                        @can('Manage Facility')
                        <a href="{{action('FacilityController@addFacilityUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Facility</a>
                        @endcan
                        @endrole                       

                    </div>

                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Facility Name</th>
                                    <th>Email</th>
                                    <th>Facility Admin</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1)
                                @if(count($facilityList)>0)
                                @foreach($facilityList as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $val->facility_name }}</td>
                                    <td>{{ $val->email }}</td>
                                    <td>@if(isset($val->fa_admin)){{ $val->fa_admin['fa_name'] }} @else N/A @endif</td>
                                    <td>{{ $val->first_name }}</td>
                                    <td>{{ $val->last_name }}</td>
                                    <td>{{ $val->phone }}</td>
                                    <td>{{ $val->city }}</td>
                                    <td>

                                        @role('Super Admin')
                                        <a href="{{route('facility.view', ['id' => $val->id] )}}"<i class="fa fa-eye" data-toggle="tooltip" title="View"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{route('facility.add', ['id'=> $val->id] )}}"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('facility.servicedetails', ['id' => $val->facility_user_id]) }}" data-toggle="tooltip" title="Service Details" ><i class="fa fa-server"></i>&nbsp;&nbsp;&nbsp;</a>

                                        <a href='javascript:;' token="{{ csrf_token() }}" class="facilityDelete" id="{{$val->id}}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="facilitymonthlyReport" facility_id="{{$val->id}}">
                                            <i class="fa fa-files-o" aria-hidden="true" data-toggle="tooltip" title="Download Monthly Report"></i> &nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('facility_service_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Service Report"> <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('perfacilityreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Facility Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('facilityusersreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Users Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('inactiveuser_facilityreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Inactive Users Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="javascript:;" data-toggle="tooltip" title="Download User's Service History Report" onclick="ush_report({{$val->facility_user_id}})" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="javascript::"  onclick="ushd_report({{$val->facility_user_id}})" data-toggle="tooltip" title="Download User's Service History Detail" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="javascript:;" data-toggle="tooltip" title="Download Hourly Service Report" onclick="hourlyReportDownload({{$val->id}})"> <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        @can('Manage Facility')
                                        <a href="{{route('facility.view', ['id' => $val->id] )}}"<i class="fa fa-eye" data-toggle="tooltip" title="View"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{route('facility.add', ['id'=> $val->id] )}}"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;&nbsp;</a>                                       
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="facilityDelete" id="{{$val->id}}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Enable Services For Facility')
                                        <a href="{{ route('facility.servicedetails', ['id' => $val->facility_user_id]) }}" data-toggle="tooltip" title="Service Details" <i class="fa fa-server"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download Facility Service Report')
                                        <a href="{{ route('facility_service_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Service Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download Facility Report')
                                        <a href="{{ route('perfacilityreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Facility Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download Facility Users Report')
                                        <a href="{{ route('facilityusersreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Users Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download Inactive Facility List Report')
                                        <a href="{{ route('inactiveuser_facilityreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Inactive Users Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download Users Service History')
                                        <a href="{{ route('all_user_service_history_report', ['id' => $val->facility_user_id]) }}" data-toggle="tooltip" title="Download Users Service Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download Hourly Service Report')
                                        <a href="javascript:;" data-toggle="tooltip" title="Download Hourly Service Report" onclick="hourlyReportDownload({{$val->id}})"> <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @endrole 
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- download hourly report modal -->
        <!-- Modal -->
        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Download Hourly Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="downloadhourlyreport" method="get" action="{{url('estimateserviceuses')}}">
                    <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Select Date</label>
                    <div class="col-sm-5">
                      <input type="date" class="form-control" name="date" id="date_h" required="">
                      <input type="hidden" name="facility_id" id="fac_id">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Download Report</button>
                  </div>
                  
                </form>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="ush_report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Download User's Service History Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="downloadushReport" method="get" action="{{url('estimateserviceuses')}}">
                    <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Select Date</label>
                    <div class="col-sm-5">
                      <input type="date" class="form-control" name="date" id="date_s" required="">
                      <input type="hidden" name="facility_id" id="fac_ids">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Download Report</button>
                  </div>
                  
                </form>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
              </div>
            </div>
          </div>
        </div>
<!-- user service history details modal -->
        <div class="modal fade" id="ushd_report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Download User's Service History Details Report</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="downloadushdReport" method="get" autocomplete="off" >
                    <div class="form-group row">
                    <label for="inputPassword" class="col-sm-1 col-form-label">From</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" readonly='true' name="s_date" id="start_date" required=""><input type="hidden" name="facility_id" id="fac_idss">
                    </div>
                    <label for="inputPassword" class="col-sm-1 col-form-label">To</label>
                    <div class="col-sm-3">
                      
                      <input type="text" class="form-control" readonly='true' name="e_date" id="end_date" required="">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Download Report</button>
                  </div>
                  
                </form>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
        <div class="modal fade" id="ReportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Generate Report</h4>
                    </div>
                    <div class="modal-body" >
                        <form role="form" method="post" action="javascript:;" id="Report_Fetch">
                            <input type="hidden" name="facility_id" >
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="row no-margin">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Select Report Type<i class="requiredInput text-red">*</i></label>
                                        <select class="form-control" name="report_type" id="report_type">
                                            <option value="rental">Rental(Android charges)</option>
                                            <option value="emailing"  >E-mailing</option> 
                                            <option value="texting"  >Texting</option> 
                                            <option value="vendor"> Paid Services </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 vendor_detail ui-widget" style="display:none;">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Services Name<i class="requiredInput text-red">*</i></label>
                                        <input  name="vendor_name"  placeholder="Search Services" value="All" class="form-control vendor_name" >
                                        <input type="hidden" name="service_id" class="service_id" >
                                        <div id="browsers">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-margin">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Start Date <i class="requiredInput text-red">*</i></label>
                                        <input type="text" readonly="true" autocomplete="off" name="start_date" class="form-control datepicker_start" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="exampleInputEmail1">End Date <i class="requiredInput text-red">*</i></label>
                                       <input type="text" readonly="true" autocomplete="off" name="end_date" class="form-control datepicker_end" > 

                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="download_report" >Download Report</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabeltext">Export Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Select Facility</label>
            <select id="facility_list" name="facility_id" class="form-control form-control-sm" required="required">
              <option value="">Select Facility</option>
            </select>
            <span style="color: red;display: none;" id="facility_error">Facility is required</span>
          </div>
         
            <form  enctype="multipart/form-data">
              <div class="form-group" id="uploadinput">
             <label for="exampleFormControlFile1">Upload File</label>
             <br>
               <input type="file" id="importFile" name="importfile" class="form-control-file" accept="application/json" >
            </div>
            </form>
         
          
          <button type="button" id="exportbtn" onclick="download_service()" class="btn btn-primary">Export</button>
          <div class="row">
              <div class="col-md-2"> <button type="button" id="importbtn" onclick="importService()" class="btn btn-primary">Import</button></div>
              <img id="loader" src="{{asset('images/load.gif')}}" height="36px;" hidden="">
          </div>
          
           
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    //function to show export service modal and 
    //get the facility list
    function export_service(){
        $.ajax({

            url : '{{url('/get_facilitylist')}}',
            type : 'GET',
            dataType:'json',
            success : function(data) {
                $.each(data,function(index,value){
                    $('#facility_list').append('<option value="'+value.facility_user_id+'">'+value.facility_name+'</option>')
                });
                $("#uploadinput").css('display','none');
                $("#exampleModalLabeltext").text('Export Service');
                $("#exportbtn").css('display','block');
                $("#importbtn").css('display','none');
                $("#exampleModal").modal('show');
            },
            error : function(request,error)
            {
                alert("Request: "+JSON.stringify(request));
            }
        });
        
}

//function to show import service modal
function import_service(){
    $("#facility_list").val([]);
    $('#importFile').val('')
       $.ajax({

           url : '{{url('/get_facilitylist')}}',
           type : 'GET',
           dataType:'json',
           success : function(data) {
               $.each(data,function(index,value){
                   $('#facility_list').append('<option value="'+value.facility_user_id+'">'+value.facility_name+'</option>')
               });
               $("#uploadinput").css('display','block');
                $("#exampleModalLabeltext").text('Import Service');
                $("#exportbtn").css('display','none');
                $("#importbtn").css('display','block');
                $("#exampleModal").modal('show');
           },
           error : function(request,error)
           {
               alert("Request: "+JSON.stringify(request));
           }
       });
        
}

//function to download the service in json
function download_service(){
    var facility_id = $("#facility_list").val();
    console.log(facility_id);
    if (facility_id == '') {
        toastr.error('Facility is required');
        return false;
    }
    
    var url = '{{url('/download_service')}}/'+facility_id;
    window.location.assign(url);
    
}

//function to import service

function importService(){
    var facility_id = $("#facility_list").val();
    
    if (facility_id == '') {
        toastr.error('Facility is required');
        return false;
    }

    var nme = document.getElementById("importFile");
    var ext = $('#importFile').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['json']) == -1) {
        toastr.error('invalid extension!'); 
        return false;
    }
    if(nme.value.length < 4) {
        toastr.error('Must select any of your json for upload !'); 
        nme.focus();
        return false;
    }
    var data = new FormData();
    data.append('facility_id',facility_id)
    data.append('import_file', $('#importFile').prop('files')[0]);
    $("#loader").css('display','block');
    $("#importbtn").attr("disabled", true);
    $.ajax({
        type: 'POST',               
        processData: false, // important
        contentType: false, // important
        data: data,
        url: '{{url('/import_service')}}',
        dataType : 'json',  
        // in PHP you can call and process file in the same way as if it was submitted from a form:
        // $_FILES['input_file_name']
        success: function(jsonData){
            if (jsonData.Code == 200) {
                toastr.success(jsonData.Message);
                $('#exampleModal').modal('hide');
            } else{
             toastr.error(jsonData.Message);   
            }
            
            $("#loader").css('display','none');
            $("#importbtn").attr("disabled", false);
        }
    }); 

}
function hourlyReportDownload($facility_id){
    $("#fac_id").val($facility_id);
    $('#reportModal').modal('show');
}
$("#downloadhourlyreport").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    var facility_id = $("#fac_id").val();
    var date = $("#date_h").val();
    var url = '{{url('/estimateserviceuses')}}/'+facility_id+'/'+date;
    window.location.assign(url);
});
function ush_report($facility_id){
    $("#fac_ids").val($facility_id);
    $('#ush_report').modal('show');
}
function ushd_report($facility_id){
    $("#fac_idss").val($facility_id);
    $('#ushd_report').modal('show');
}
$("#downloadushReport").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    var facility_id = $("#fac_ids").val();
    var date = $("#date_s").val();
    var url = '{{url('/all_users_service_history_report')}}/'+facility_id+'/'+date;
    window.location.assign(url);
});
$( function() {
    $( "#start_date" ).datepicker({
         maxDate : 'now',
         dateFormat: "yy-mm-dd" 
    });
    $("#start_date").on("change",function(){
            var selected = $(this).val();
            $("#end_date").datepicker("destroy");
            $( "#end_date" ).datepicker({
                     maxDate : 'now',
                     dateFormat: "yy-mm-dd",
                     minDate: selected
                });


        });
    $("#end_date").on("change",function(){
            var selected = $(this).val();
            $("#start_date").datepicker("destroy");
            $( "#start_date" ).datepicker({
                     maxDate : selected,
                     dateFormat: "yy-mm-dd"
                });


        });
  } );
$("#downloadushdReport").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    var facility_id = $("#fac_idss").val();
    var s_date = $("#start_date").val();
    var e_date = $("#end_date").val();
    if (s_date > e_date) {
        sweetAlert('Error!', 'End date must me greater than start date', 'error');
        
    }
    var url = '{{url('/all_user_service_history_details')}}/'+facility_id+'/'+s_date+'/'+e_date;
    window.location.assign(url);
});
</script>
<script src="{{ asset('/assets/js/customJS/facility.js') }}" type="text/javascript"></script>
@stop