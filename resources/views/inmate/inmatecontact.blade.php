@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Contact</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    @if (Session::has('message'))
                    <div class="alert alert-info" id="successMessage" data-dismiss="alert">{{ Session::get('message') }}</div>
                     @endif
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
                                    <th>S. No.</th>
                                    <th>User Name(First Middle Last)</th>
                                    <th >Contact Name</th>
                                    <th>Relation</th>
                                    <th>Email/Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($inmates)>0)
                                @foreach($inmates as $index =>$inmate)
                                <tr>
                                    <td >{{ ++$index }}</td>
                                    <td >{{ $inmate->inmate->first_name }} {{ $inmate->inmate->middle_name }} {{ $inmate->inmate->last_name }}</td>
                                    <td >{{ $inmate->name }}</td>
                                    <td >{{ $inmate->relation }}</td>
                                    <td >{{ $inmate->email_phone }}</td>
                                    <td>
                                        @if($inmate->varified == 0) Not verified
                                        @else
                                            
                                        <!--<a href="{{route('updatecontact', ['id' => $inmate->id])}}"   data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>@if($inmate->is_approved == 0) Approve @else Un Approve @endif</a>-->
                                        <a href="{{route('updatecontact', ['id' => $inmate->id])}}"  @if($inmate->is_deleted == 0) @if($inmate->is_approved == 0) class=" btn btn-info" title="Mark as Approve" @else  class=" btn btn-primary"  title="Make InActive" @endif @else class=" btn btn-danger" title="Make Active" @endif  inmate_id="{{ $inmate->inmate->id}}" id="{{ $inmate->id }}" data-toggle="tooltip" >
                                           @if($inmate->is_deleted == '0') 
                                                @if($inmate->is_approved == '0')
                                                    UnApproved
                                                @else
                                                    Active
                                                @endif    
                                           @else
                                                InActive 
                                           @endif</a>
                                        @endif
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
         <!-- Modal for adding new service catagory -->
      

      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<script src="{{ asset('/assets/js/customJS/inmatecontact.js') }}" type="text/javascript"></script>
@stop
