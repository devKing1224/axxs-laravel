@extends('layouts.default')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/b-1.7.1/b-html5-1.7.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/b-1.7.1/b-html5-1.7.1/datatables.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>

        .dataTables_filter {
            width: 50%;
            float: right;
            text-align: right;

        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 30%;
            left: 50%;
            width: 30%;
            height: 40px;
            margin-left: -20%;
            margin-top: -25px;
            padding-top: 20px;
            text-align: center;
            font-size: 1.2em;
            background:none;
        }



    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Trace User Login</h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{url('/devices')}}">Trace</a></li>
                <li class="active">User Login</li>
            </ol>
        </section>

        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
        @if(Session::has('error'))
            <p id="error" class="alert {{ Session::get('alert-class', 'alert-danger') }} " >{{ Session::get('error') }}<button type="button" class="close" data-dismiss="alert">x</button></p>
    @endif

    <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-control" id="facility_user" @if($facilityUser) disabled @endif>
                                        <option> Select Facility</option>
                                        @if(count($facility) > 0)
                                            @foreach($facility as $fac)
                                                <option value="{{$fac->id}}" @if($fac->id == $facility_id) selected @endif>{{$fac->facility_name}}</option>
                                            @endforeach

                                        @endif
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- Flash message -->
                        <div class="alert alert-success" id="alertDiv" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span id="alert"></span>
                        </div>
                        <!-- Flash message -->

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="email-table" class="table table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>Start Datetime</th>
                                    <th>Device Mac</th>
                                    <th>Device ID</th>
                                    <th>Inmate ID</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
        <!-- Trigger the modal with a button -->

        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title ">View Email</h4>
                    </div>
                    <div class="modal-body">
                        <div id="email_html"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal -->
        <div id="view_attach" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title ">View Attachment</h4>
                    </div>
                    <div class="modal-body">
                        <div id="email_attach"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo asset('/'); ?>assets/js/customJS/device.js" type="text/javascript"></script>

    <!--<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
    <!--<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>-->
    <!--<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>-->
    <!--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
    <!--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>-->
    <!--<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>-->
    <!--<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>-->



    <script type="text/javascript">
        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function (e, s, data) {
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function (e, settings) {
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function (e, s, data) {
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });
                    setTimeout(dt.ajax.reload, 0);
                    return false;
                });
            });
            dt.ajax.reload();
        };

        function getFacilities() {
            var optionSelected = $("option:selected", $('#facility_user'));
            var facility_user_id = $('#facility_user').val();
            if (facility_user_id ==  'Select Facility') {
                return false;
            }
            var base_url = window.location.origin;
            $('.dataTables_filter input').attr("placeholder", "Zoeken...");
            $(function() {
                $('#email-table').DataTable({
                    language: {
                        processing: "<img style='width:50px; height:50px;' src='"+base_url+"/images/2.gif'> Loading...",
                    },
                    dom: 'Blfrtip',
                    lengthMenu: [[25, 100, -1], [25, 100, "All"]],
                    buttons: [
                        { extend:'excel',
                            action: newexportaction
                        }
                    ],
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: base_url+'/getuserlogindetails'+'/'+facility_user_id,

                    columns: [
                        {data: 'start_date_time'},
                        {data: 'imei'},
                        {data: 'device_id'},
                        {data: 'inmate_id'},
                        {data: 'first_name'},
                        {data: 'middle_name'},
                        {data: 'last_name'}
                    ],
                    language: {
                        searchPlaceholder: "Enter Mac Address"
                    },
                    order: [[0, 'desc']]
                });

            });
        }

        @if ($facilityUser)
            getFacilities();
        @endif

        $('#facility_user').on('change', '', function (e) {
            getFacilities();

        });
    </script>
@stop