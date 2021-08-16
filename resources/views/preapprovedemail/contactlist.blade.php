@extends('layouts.default')
@section('title', '|Pre-Approved Text List')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-ban"></i> Pre-Approved Text List
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Pre-Approved Contacts</a></li>
            <li class="active">Pre-Approved Text List </li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                  <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                <div class="box box-primary">
                    <div class="box-header text-right">
                          <div class="box-header text-right">
                        <a href="{{route('preapprovedemail.addcontact')}}" class="btn btn-primary "><i class="fa fa-plus" aria-hidden="true"></i>Add Pre-Aprroved Contact</a>
                    </div>
                 
                    </div>
                    <!-- Flash message show -->
                   <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message show end-->
                    <!-- Session message show -->
                    @if (Session::has('flash_message'))
                    <div class="alert alert-info" id="successMessage" data-dismiss="alert"><span>{{ Session::get('flash_message') }} </span></div>
                    @endif
                    <!-- Session message end -->

                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                    <th>Name</th>
                                    <th>Contact Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($allcontacts as $index => $allcontact)
                                
                                <tr>
                                    <td>{{ ++ $index }}</td>
                                      <td>{{ $allcontact->name}}</td>
                                    <td>{{ $allcontact->contact_number}}</td>
                                    
                                    <td>@if(isset($allcontact->status) && $allcontact->status ==0) Active 
                                            @else Inactive @endif</td>                                  
                                    <td>
                                        <a href="{{route('approvedcontact.edit', ['id'=>$allcontact->id] )}}"><i class="fa fa-pencil" data-toggle="tooltip" id="{{$allcontact->id}}" title="Edit"></i>&nbsp;&nbsp;&nbsp;
                                        </a>

                                        <a href='javascript:;' token="{{ csrf_token() }}" deletename="user" class="preContactDelete" id="{{$allcontact->id}}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>


                                        
                                    </td>
                                </tr>
    
                                @endforeach

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('/assets/js/customJS/superadmin.js') }}" type="text/javascript"></script>
<script src="{{ asset('/assets/js/customJS/facility.js') }}" type="text/javascript"></script>
@endsection