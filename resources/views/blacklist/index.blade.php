@extends('layouts.default')
@section('title', '|Blacklisted Keywords')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-ban"></i> Blacklisted Keywords 
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Setting</a></li>
            <li class="active">Blacklisted Keywords</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header text-right">
                          <div class="box-header text-right">
                        <a href="{{route('blacklist.createadd')}}" class="btn btn-primary "><i class="fa fa-plus" aria-hidden="true"></i> Add Blacklisted Keywords</a>
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
                                    <th>Blacklisted Keywords</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($blackListedWords as $index => $blackListedWord)
                                
                                <tr>
                                    <td>{{ ++ $index }}</td>
                                    <td>{{ $blackListedWord->blacklisted_words}}</td>                                 
                                    <td>
                                        @if($blackListedWord->addedbyuser_id == null && Auth::user()->role_id == 2)
                                        Added By Admin 
                                        @elseif($blackListedWord->addedbyuser_id == Auth::user()->id || Auth::user()->role_id == 1)

                                        <a href="{{route('blacklist.create', ['id'=>$blackListedWord->id] )}}"><i class="fa fa-pencil" data-toggle="tooltip" id="{{$blackListedWord->id}}" title="Edit"></i>&nbsp;&nbsp;&nbsp;
                                        </a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" deletename="user" class="blacklistedWordDelete" id="{{$blackListedWord->id}}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        
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