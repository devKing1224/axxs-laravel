    @extends('layouts.default')
    @section('content') 
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
            <h1>Service Permission List</h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{url('/allusers')}}">User List</a></li>
                <li class="active">Service Details</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="alert alert-success" id="alertDiv" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                            <span id="alert"></span>
                        </div>
                        <!-- Flash message -->

                        <form method="POST" id="serviceAssignDataPosts" action="{{action('ServicePermissionController@registerPermission')}}">
                            {{ csrf_field() }}
                            <div class="box-header">
                                <h3 class="box-title">Service Permission List</h3>
                                
                                <div class="row">
                                  <div class="col-md-3">
                                  @if($inmateinfo != 'facility')
                                  <h5><b>Username</b> - {{$userinfo->username}}</h5>
                                    <h5><b>Inmate ID</b> - {{$userinfo->inmate_id}}</h5>
                                    @else
                                    <h5><b>Facility</b> - {{$facilityName}}</h5>
                                    @endif
                                  </div>
                                  <div class="col-md-9">
                                    <div class="text-right">
                                    <!--<i class="fa fa-cog" aria-hidden="true"></i>--><input type="submit"  class="btn btn-primary registerPermissionPost" value="Save Changes"> 
                                </div>
                                  </div>
                                  
                                </div>

                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                @if(Session::has('flash_message'))
                                    <div class="alert alert-success fade-message">
                                        {{ Session::get('flash_message') }}
                                    </div>
                                @endif
                                <script>
                        $(function(){
                            setTimeout(function() {
                                $('.fade-message').slideUp();
                            }, 4000);
                        });
                        </script>
                                <input type="hidden" name="inmate_id" value="{{ $inmate_id or ''}}"/>
                                <table id="serviceDetailsDataTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Serial Number</th>
                                            <th>Service Category Name</th>
                                            <th>Service Name</th>
                                            <th>Service Type</th>
                                            <th>Charge</th>
                                              <th>
                                          <input type="checkbox" name="serviceCheckHeaderName" id="serviceCheckHeader" value="1">Enabled
                                          </th>
                                      @if($inmateinfo == 'facility')
                                         <th>
                                            <input type="checkbox" name="defaultServiceName" id="defaultServiceHeader" value="1">Default Service
                                        </th>
                                          @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($count = 1)
                                        @if(count($serviceList)>0)
                                        @foreach($serviceList as $val)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>@if(isset($val->Service_category_name)){{ $val->Service_category_name }}@else None @endif</td>
                                            <td>{{ $val->name }}</td>

                                            <td>
                                              @php $m = 0;@endphp
                                              @if(isset($facilityCharge))
                                             @foreach($facilityCharge as $fac_charge)
                                             @if($fac_charge['service_id'] == $val->id)
                                                  @php $m = 1; @endphp
                                              @if(isset($fac_charge['type']) && $fac_charge['type'] == 0) Free 
                                              @elseif($fac_charge['type'] == 1) Paid @else Flate @endif

                                               @break

                                             @endif
                                             @endforeach
                                             @endif

                                             @if($m==0)
                                              @if(isset($val->type) && $val->type == 0) Free 
                                              @elseif($val->type == 1) Paid @else Flate @endif
                                             @endif
                                            </td> 

                                            <td>
                                              @php $m = 0;@endphp
                                              @if(isset($facilityCharge))
                                             @foreach($facilityCharge as $fac_charge)
                                             @if($fac_charge['service_id'] == $val->id)
                                                  @php $m = 1; @endphp
                                              $ {{ $fac_charge['charge'] }}

                                               @break

                                             @endif
                                             @endforeach
                                             @endif
                                             @if($m==0)
                                              $ {{ $val->charge }}
                                             @endif
                                            </td>                                        
                                                @if(!$inmateinfo)
                                                    @if($val->id == 8)
                                                        <td><input type="checkbox" name="seviceList[]" class="serviceCheckEmail" id="seviceList{{ $val->id }}" value="{{ $val->id }}" <?php if(count($inmateServiceList)>0){ foreach( $inmateServiceList as $value ){ if($value->id === $val->id){ ?> checked <?php } else { echo ''; } } }?> > </td>
                                                     @else
                                                        <td><input type="checkbox" name="seviceList[]" class="serviceCheck" id="seviceList{{ $val->id }}" value="{{ $val->id }}" <?php if(count($inmateServiceList)>0){ foreach( $inmateServiceList as $value ){ if($value->id === $val->id){ ?> checked <?php } else { echo ''; } } }?> > </td>
                                                    @endif
                                                 @elseif($inmateinfo == 'facility' && !$twilio_exist)
                                                    @if($val->id == 7)
                                                        <td><input type="checkbox" name="seviceList[]"
                                                         class="serviceCheckSMS"  id="seviceList{{ $val->id }}" value="{{ $val->id }}" <?php if(count($inmateServiceList)>0){ foreach( $inmateServiceList as $value ){ if($value->id === $val->id){ ?> checked <?php } else { echo ''; } } }?> > </td>
                                                     @else
                                                        <td><input type="checkbox" name="seviceList[]" class="serviceCheck" id="seviceList{{ $val->id }}" value="{{ $val->id }}" <?php if(count($inmateServiceList)>0){ foreach( $inmateServiceList as $value ){ if($value->id === $val->id){ ?> checked <?php } else { echo ''; } } }?> >
                                                          @if(count($inmateServiceList)>0)
                                                          @foreach($inmateServiceList as $value)
                                                          @if($value->id === $val->id)
                                                          <input type="hidden" value="1">

                                                          @else

                                                            <input type="hidden" value="0">
                                                            @endif
                                                          @endforeach

                                                        @endif
                                                         </td>
                                                    @endif
                                                @else
                                                    <td><input type="checkbox" name="seviceList[]" class="serviceCheck" id="seviceList{{ $val->id }}" value="{{ $val->id }}" <?php if(count($inmateServiceList)>0){ foreach( $inmateServiceList as $value ){ if($value->id === $val->id){ ?> checked <?php } else { echo ''; } } }?> >
                                                      @if(count($inmateServiceList)>0)
                                                          @foreach($inmateServiceList as $value)
                                                          @if($value->id === $val->id)
                                                          <input type="hidden" value="1">

                                                          @else

                                                            <input type="hidden" value="0">
                                                            @endif
                                                          @endforeach

                                                        @endif
                                                     </td>
                                                @endif 

                                                @if($inmateinfo == 'facility')
                                                 @if(!$inmateinfo)

                                                    @if($val->id == 8)
                                                     @else
                                                        <td><input type="checkbox" name="defaultseviceList[]" id="dseviceList{{ $val->id }}" class="defaultserviceCheck" value="{{ $val->id }}" onchange="myFunction('seviceList{{ $val->id }}');" <?php if(count($inmateDefaultServiceList)>0){ foreach( $inmateDefaultServiceList as $value ){ if($value->service_id === $val->id){ ?> checked <?php } else { echo ''; } } }?> > </td>
                                                    @endif
                                                 @elseif($inmateinfo == 'facility' && !$twilio_exist)
                                                    @if($val->id == 7)
                                                        <td><input type="checkbox" name="defaultseviceList[]"  id="dseviceList{{ $val->id }}" class="serviceCheckSMS" value="{{ $val->id }}" onchange="myFunction('seviceList{{ $val->id }}');" <?php if(count($inmateDefaultServiceList)>0){ foreach( $inmateDefaultServiceList as $value ){ if($value->service_id === $val->id){ ?> checked <?php } else { echo ''; } } }?> > </td>
                                                     @else
                                                        <td><input type="checkbox" name="defaultseviceList[]"  id="dseviceList{{ $val->id }}" class="defaultserviceCheck" value="{{ $val->id }}" onchange="myFunction('seviceList{{ $val->id }}');"  <?php if(count($inmateDefaultServiceList)>0){ foreach( $inmateDefaultServiceList as $value ){ if($value->service_id === $val->id){ ?> checked <?php } else { echo ''; } } }?> >
                                                           @if(count($inmateDefaultServiceList)>0)
                                                          @foreach($inmateDefaultServiceList as $value)
                                                          @if($value->service_id=== $val->id)
                                                          <input type="hidden" value="1">

                                                          @else

                                                            <input type="hidden" value="0">
                                                            @endif
                                                          @endforeach

                                                        @endif
                                                         </td>
                                                    @endif
                                                @else
                                                    <td><input type="checkbox" name="defaultseviceList[]"  id="dseviceList{{ $val->id }}" class="defaultserviceCheck" value="{{ $val->id }}" onchange="myFunction('seviceList{{ $val->id }}');" <?php if(count($inmateDefaultServiceList)>0){ foreach( $inmateDefaultServiceList as $value ){ if($value->service_id=== $val->id){ ?> checked <?php } else { echo ''; } } }?> >
                                                       @if(count($inmateDefaultServiceList)>0)
                                                          @foreach($inmateDefaultServiceList as $value)
                                                          @if($value->service_id=== $val->id)
                                                          <input type="hidden" value="1">

                                                          @else

                                                            <input type="hidden" value="0">
                                                            @endif
                                                          @endforeach

                                                        @endif
                                                     </td>
                                                @endif
                                              @endif                                                            
                                        </tr>
                                       @endforeach
                                       @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            <!-- /.col -->
            </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
	<script>
	function myFunction(id) { 
		if(!$("#"+id).is(":checked")){
      alert("Please assign service first");
			$("#d"+id).prop('checked', false);
			
				
		}
	}
	</script>
     <div class="modal fade" id="SMSModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel text-primary">You have to add Twilio Number</h4>
                  </div>
                 
                    <div class="modal-body">

                          <div class="box-body">
                               <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>No twilio number has been assigned to this Facility Admin.<br></h4>
                                                <p>To enable text Service please assign him a valid number.</p>
                                               </div>
                                 </div>
                              
                          </div>

                     </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     </div>
                  
                </div>
              </div>
            </div>
    <div class="modal fade" id="EmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Add User Email ID And Password</h4>
                  </div>
                  <form role="form" method="post" action="javascript:;" id="InmateEmailData">
                    <div class="modal-body">

                          <div class="box-body">
                               <div class="col-md-6">
                                            <div class="form-group">
                                                 <input type="hidden" id="inmateid"  name="inmate_id" value="{{ $inmate_id }}"/>
                                                <label for="exampleInputEmail1">Email ID<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="email" id="inmateemail" placeholder="Please enter correct email ID.">
                                            </div>
                                 </div>
                               <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Password<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="password" id="inmatepassword" placeholder="Please enter password">
                                            </div>
                                 </div>
                          </div>

                     </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" id="InmateEmailDetails">Add</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

    <script>
      $(function () {
        $('#serviceDetailsDataTable').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });

      $('#serviceAssignDataPosts').on('submit', function (e) {
        $('#serviceDetailsDataTable').DataTable().destroy();
    });
    </script>
    <script src="{{ asset('/assets/js/customJS/service.js') }}" type="text/javascript"></script>
    @stop