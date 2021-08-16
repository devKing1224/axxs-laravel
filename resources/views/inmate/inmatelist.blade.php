@extends('layouts.default')
@section('content')        
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
                            <select class="form-control" id="InmateActiveInactiveCall">
                                <option>Please select any option</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                        <div class="col-sm-8 text-right">

                        @role('Facility Admin')
                         <a href="{{ route('all_user_service_history_report', ['id' => Auth::user()->id]) }}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i>User's Service History Report</a>
                        @endrole
                      @role('Facility Staff')
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
                    @role('Super Admin|Facility Administrator')
                    <div class="col-sm-2">
                         <br>
                        <select class="form-control" id="facility_user">
                            <option> Select Facility</option>
                            @if(count($facility) > 0)
                                @foreach($facility as $fac)
                                    <option value="{{$fac->facility_user_id}}" @if($fac->facility_user_id == $facility_id) selected @endif>{{$fac->facility_name}}</option>

                                @endforeach
                                @role('Facility Administrator')
                                    <option value="all" @if($all == true) selected @endif> All Users</option>
                                    @endrole
                            @endif
                        </select>
                    </div>
                    @endrole

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
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                     @hasanyrole('Facility Admin|Facility Staff')
                                    @else
                                    <th>Facility Name</th>
                                    <th>Location</th>
                                    @endhasanyrole
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Birthday</th>
                                    <th>User ID</th>
                                    <!--<th>Phone</th>-->
                                    <!--<th>City</th>-->
                                    <!--<th>Username</th>-->
                                    <th>Balance ($)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 1) 
                                @if(count($userList)>0)
                                @foreach($userList as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    @hasanyrole('Facility Admin|Facility Staff')
                                    @else
                                     <td><b>{{ $val->facility_name }}</b></td>
                                     <td>{{ $val->location }}</td>
                                    @endhasanyrole
                                    <td>{{ $val->last_name }}</td>
                                    <td>{{ $val->first_name }}</td>
                                    
                                   <td> @if(!empty($val->date_of_birth))
                                    {{  date('m-d-Y', strtotime($val->date_of_birth )) }}
                                    @endif
                                    </td>
                                    <!--<td>{{ phone_number_format($val->phone) }}</td>-->
                                    <!--<td>{{ $val->city }}</td>-->
                                    <!--<td>{{ $val->username }}</td>-->
                                    <td>{{ $val->inmate_id }}</td>
                                    <td>{{ $val->balance }}</td>
                                     <td>
                                    @role('Super Admin|Facility Admin')
                                    <!-- condition for sorting icon on red color -->
                                    @if(App\SentInmateEmail::getBlacklistedbyinmate($val->id) || App\InmateSMS::getBlacklistedsmsbyinmate($val->id) || App\InmateContacts::getVarificationInmate($val->id) || $val->is_view == 1)
                                    <input type="hidden" name="sorting" value="1">
                                    @endif
                                        <a href="{{ route('inmate.view', ['id' => $val->id]) }}" data-toggle="tooltip" title="View" ><i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('inmate.add', ['id' => $val->id]) }}" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="inmateID" id="{{ $val->id }}" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>
                                        
                                        <a href="{{ route('inmate.servicedetails', ['id' => $val->id]) }}" data-toggle="tooltip" title="Service Details" ><i class="fa fa-server"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('family.list', ['id' => $val->id]) }}" data-toggle="tooltip" title="Family Details" ><i class="fa fa-home"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @if(App\SentInmateEmail::getBlacklistedbyinmate($val->id))
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope" style="color:red"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        @if(App\InmateSMS::getBlacklistedsmsbyinmate($val->id)) 
                                        <a href="{{ route('inmate.sms', ['id' => $val->id]) }}" data-toggle="tooltip" title="SMS Details" ><i class="fa fa-mobile" style="color:red"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                          <a href="{{ route('inmate.sms', ['id' => $val->id]) }}" data-toggle="tooltip" title="SMS Details" ><i class="fa fa-mobile"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        <a href="{{ route('inmate.inmateloggedhistory', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Logged Details" ><i class="fa fa-hdd-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                       <a href="{{ route('inmate.inmateactivity', ['id' => $val->id]) }}" data-toggle="tooltip" title="Activity History Details" <i class="fa fa-history"></i>&nbsp;&nbsp;&nbsp;</a>
                                       
                                       @if(App\InmateContacts::getVarificationInmate($val->id)) 
                                        <a href="{{ route('contactlist', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Contact Person" ><i class="fa fa-book" style="color:maroon"></i>&nbsp;&nbsp;&nbsp;</a>
                                       @else
                                        <a href="{{ route('contactlist', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Contact Person" ><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;</a>
                                         @endif
                                        
                                        <a href="{{ route('userreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Service Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('user_email_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Email List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('user_contact_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Mobile List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('user_service_history_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Activity History Details Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @can('Download Fund Report')
                                        <a href="{{ route('user_fund_history_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Fund Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User Service Report')
                                        <a href="{{ route('user_service_report_details', ['id' => $val->id]) }}" data-toggle="tooltip"  title="Download User Service Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @else
                                        @can('Manage Users')
                                        <a href="{{ route('inmate.view', ['id' => $val->id]) }}" data-toggle="tooltip" title="View" <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('inmate.add', ['id' => $val->id]) }}" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="inmateID" id="{{ $val->id }}" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>
                                        
                                        <a href="{{ route('inmate.inmateloggedhistory', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Logged Details" ><i class="fa fa-hdd-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                     
                                        @endcan
                                        @can('Enable Service Permission For Users')
                                        <a href="{{ route('inmate.servicedetails', ['id' => $val->id]) }}" data-toggle="tooltip" title="Service Details" ><i class="fa fa-server"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Manage Family')
                                        <a href="{{ route('family.list', ['id' => $val->id]) }}" data-toggle="tooltip" title="Family Details" ><i class="fa fa-home"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('View User Email')
                                        @if(App\SentInmateEmail::getBlacklistedbyinmate($val->id))
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope" style="color:red"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        @endcan
                                        @can('View User SMS')
                                        <a href="{{ route('inmate.sms', ['id' => $val->id]) }}" data-toggle="tooltip" title="SMS Details" ><i class="fa fa-mobile"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Manage User Contacts')
                                        <a href="{{ route('contactlist', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Contact Person" ><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User With Services Report')
                                        <a href="{{ route('userreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Service Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User Email List Report')
                                        <a href="{{ route('user_email_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Email List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User Number List Report')
                                        <a href="{{ route('user_contact_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Mobile List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                           <a href="{{ route('user_service_history_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Activity History Details" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @endrole 
                                        @can('User Disputed Report')
                                        <a href="{{ route('user_dispute_report', ['id' => $val->id]) }}"  token="{{ csrf_token() }}"  maxtype="email"    value="{{ $val->id }}"  @if($val->is_view == 1) style="color: red"  inmate_id={{$val->id}}@endif title="User Dispute Report" ><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan

                                        @can('Manage Block Service')
                                        @php
                                            $cur_date = date('Y-m-d');
                                        @endphp

                                        @if($cur_date == $val->bs_start_date || $cur_date == $val->bs_end_date || $cur_date > $val->bs_start_date && $cur_date < $val->bs_end_date )
                                        <a href="JavaScript:Void(0);" id="block_service" title="Block Service" onclick="blockService({{$val->id}})"  ><i class="fa fa-ban" style="color: red"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        <a href="JavaScript:Void(0);" id="block_service" title="Block Service" onclick="blockService({{$val->id}})"  ><i class="fa fa-ban"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        @endcan

                                        @role('Facility Admin')
                                        
                                        @if($maxLimitbyfacility)
                                        @if($maxLimitbyfacility->max_phone)
                                        @if($maxLimitbyfacility->max_email)

                                        @else
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="email"   data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif  
                                        @else
                                        @if($maxLimitbyfacility->max_email)
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="phone" data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="none"  data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>  
                                        @endif
                                        @endif
                                        @else
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="none"  data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>

                                        @endif
                                        @endrole
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
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
<script type="text/javascript">
    $('#facility_user').on('change', '', function (e) {
        var optionSelected = $("option:selected", this);
    var facility_user_id = this.value;
    if (facility_user_id ==  'Select Facility') {
        return false;
    }
   window.location.href = '{{URL::to('allusers')}}'+'/'+facility_user_id;
});
</script>
<script src="{{ asset('/assets/js/customJS/inmate.js') }}" type="text/javascript"></script>
@stop