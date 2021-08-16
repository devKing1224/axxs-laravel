@extends('layouts.default')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <!-- Message -->
      @if (Session::has('message'))
   <div class="alert alert-info " role="alert">{{ Session::get('message') }}
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
@endif
@if (Session::has('error'))
   <div class="alert alert-danger " role="alert">{{ Session::get('error') }}
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
@endif

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ count($facilityList) }}</h3>

                        <p>Facility</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{action('FacilityController@facilityListUI')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ count($userList) }}<sup style="font-size: 20px"></sup></h3>

                        <p>User</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{action('InmateController@inmateListUI')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{count($freeServiceInfo) }}</h3>

                        <p>Free Services</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('service.list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{count($paidServiceInfo) }}</h3>

                        <p>Paid services</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{route('service.list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="box box-primary">
    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#welcomemsg">Terms & Conditions</a>
                                </h4>
                            </div>
                            <div id="welcomemsg" class="panel-collapse collapse">
                                <div class="panel-body">
                                   <!-- <div id="summernote">Hello Summernote</div> -->

                                    <form role="form" method="post" action="{{url('/')}}/termsupdate" id="WelcomeForm">
                                       {!! csrf_field() !!}
                                        
                                        <div class="box-body">
                                            <textarea class="form-control" id="termsofservice" name="tos">{{$tos->content or ''}}</textarea>
                                            <input type="hidden" name="id" value="{{$tos->id or ''}}">
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary" id="Welcomemsgsub">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Last Updated on {{$tos->updated_at or ''}} </div>
                            </div>
                        </div>
                    </div>
                </div>
       

    </section>
    <!-- /.content -->
</div>
<script>
    $(document).ready(function() {
        $('#termsofservice').wysihtml5();
    });
  </script>
@stop
