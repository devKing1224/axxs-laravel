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
        <h1>Service List</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('/services')}}">Service</a></li>
            <li class="active">Service List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-right">
                        <a href="{{action('ExcelController@serviceReport')}}" class="btn btn-info"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Services</a>
                         @role('Super Admin') 
                        <a href="{{action('ServiceController@addServiceUI')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Service</a>
                         @endrole  
                    </div>

                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->
               
                     <form method="POST"  action="{{action('ServicePermissionController@defaultPermissionByFacility')}}">
                        {{ csrf_field() }}
                       @role('Facility Admin')                        
                            <div class="text-right">
                            <a id="resetservice"  data-link="{{url('/')}}/resetuserservices" @if(count($serviceList) > 0) onclick="resetuserservices()" @endif class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Click this button to set default services for all users !" @if(count($serviceList) == 0)disabled @endif >Reset Services</a> 
                                 <input type="submit"  class="btn btn-primary registerPermissionPost" value="Save Changes"> 
                            </div>
                         @endrole        
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
                    </script>        <!-- /.box-header -->
                    <div class="box-body">
                        @role('Facility Admin')
                             <input type="hidden" name="inmate_id" value="{{ $id }}"/>
                        @endrole
                        <table @role('Super Admin')id="servicetable"@endrole class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Service Category Name</th>
                                    <th>Service Name</th>
                                    <th>Flat Rate</th>
                                    <th>Flat Rate Charge</th>
                                    <th>Per Minute Charge Type</th>
                                    <th>Charge/Minute ($.$$)</th>
                                    <th>Action</th>
                                     @role('Facility Admin')
                                    <th>
                                    <input type="checkbox" name="defaultServiceName" id="defaultServiceHeader" value="1">Default Service
                                    </th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                <!--                                @php($count=1)-->
                                @if(count($serviceList)>0)
                                @foreach($serviceList as $index => $val)


                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>@if(isset($val->Service_category_name) && $val->Service_category_name !== NULL){{ $val->Service_category_name }} @else None @endif</td>
                                    <td>{{ $val->name }}</td>
                                    <td>@if(isset($val->flat_rate) && $val->flat_rate == 0) Disabled  @else Enabled @endif</td>
                                    <td>{{ $val->flat_rate_charge }} </td>
                                    <td>@if(isset($val->type) && $val->type == 0) Free  @elseif($val->type == 1) Facility Rate @elseif($val->type == 2) Premium @else -- @endif</td>
                                    <td>@if(isset($val->type) && $val->type == 0) Free  @elseif($val->type == 1) Facility Rate @elseif($val->type == 2) {{ $val->charge }} @else -- @endif</td>
                                    <td>
                                       @role('Super Admin')
                                        <?php $count = 0; ?>
                                        @if (count($inmateServiceList) > 0 )
                                        @foreach($inmateServiceList as $adminlist)
                                        @if($adminlist->id == $val->id)
                                        <?php $count = 1; ?>
                                        @endif
                                        @endforeach
                                        @endif
                                        @if($count == 0)
                                        <a href="viewservice/{{$val->id}}"data-toggle="tooltip" title="View" <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>                                         
                                        <a href="addservice/{{$val->id}}" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;'   token="{{ csrf_token() }}" class="serviceDelete" id="{{$val->id}}" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>                                 
                                        @endif
                                        @role('Super Admin')
                                        <a href="{{ route('vendor_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Vendor Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        @can('Download Vendor Report')
                                        <a href="{{ route('vendor_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Vendor Report" <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @endrole 
                                    @endrole
                                    </td>
                                     @role('Facility Admin')
                                    <td>
                                        <input type="checkbox" name="defaultseviceList[]"  id="dseviceList{{ $val->id }}" class="defaultserviceCheck" value="{{ $val->id }}" <?php if(count($inmateDefaultServiceList)>0){ foreach( $inmateDefaultServiceList as $value ){ if($value->service_id === $val->id){ ?> checked <?php } else { echo ''; } } }?> >
                                        </td> 
                                      
                                </tr>
                                  @endrole 
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
<script src="{{ asset('/assets/js/customJS/service.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    
$("body").on("click", "#defaultServiceHeader", function (e) {
    var serviceCheckValue = $("#defaultServiceHeader").is(':checked') ? 1 : 0;
    if (serviceCheckValue == 1) {
        $('.defaultserviceCheck').prop('checked', true);

    } else {

        $('.defaultserviceCheck').prop('checked', false);

    }
    
});

  $(function () {
    $('#servicetable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true
    });
  });
  
  function resetuserservices(){
        swal({
            title: "Are you sure?",
            text: "You want to reset services for all users!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, reset it !",
            cancelButtonText: "No, cancel please !",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        var link=$("#resetservice").data('link');
                        window.location.href = link;
                    } else {
                        swal("Cancelled", "", "error");
                    }
                });
    }
</script>
@stop