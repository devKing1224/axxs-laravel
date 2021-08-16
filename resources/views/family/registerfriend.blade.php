<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<head>
    @yield('styles')
    @include('includes.head')
</head>
<body class="skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo --> 
            <a href="#" class="logo">
                <span class="logo-lg"><img src="{{ asset ("/bower_components/admin-lte/images/logo.png") }}" /></span>
            </a>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">

            </nav>
            <!--Loader Image Code-->
            <div id="floatingBarsG" style=" pointer-events: auto; display: none; position: none; width: 100%;height: 100%; align-items: center; margin-left: 48%;margin-top: 24%;">
                <img src="<?php echo asset('/'); ?>assets/images/loader.gif" height="70" style="margin: 0 auto;"alt="Loader">
            </div>
            <!--Loader Image Code-->
        </header>
        <div id="main" class="container ">
            <section class="content ">
                <div class="row">
                    <!-- left column -->


                    <div class="col-md-12 ">
                        <!-- general form elements -->
                        <div class="box box-primary">
                               <div class="box-header text-right">
                                 <a class="btn btn-primary" href="https://theaxxstablet.com">Login</a>
                            </div>
                            <div class="box-header with-border">
                                 @if($response == 0)
                                        <h1 class="text-primary">Amount transfered successfully</h1>
                               
                                    @elseif($response == 1)
                                        <h1 class="text-danger">Got some error. </h1>
                                        
                                    @elseif($response == 2)
                                        <h1 class="text-info">Your status is pending</h1>
                                       
                                    @endif
                                
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                           
                                <div class="box-body">
                                    <div class="row no-margin">
                                        <div class="col-md-10 ">
                                            @if($response == 0)
                                        <h3>@if(isset($user)) {{$user->first_name}} {{$user->last_name}} @else User's @endif account has been recharge successfully with amount ${{ $amount }} </h3>
                                    @elseif($response == 1)

                                        <h3> Fund of ${{ $amount }} could not be added into @if(isset($user)) {{$user->first_name}} {{$user->last_name}} @else User's @endif account. Please try again</h3>
                                    @elseif($response == 2)
                                        <h3>Fund of ${{ $amount }} is still pending , In case of unsuccessful transaction in @if(isset($user)) {{$user->first_name}} {{$user->last_name}} @else User's @endif account, amount will be refunded into your account.</h3>
                                    @endif
                                           
                                        </div>
                                   </div>
                                </div>
                        </div>
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->

            </section>
        </div>
        <footer class="main-footer" style="margin-left:0; position: fixed; bottom: 0; width:100%;">
            <strong>Copyright © 2017 <a href="#"> TheAxxsTablet</a>.</strong> All rights reserved.
        </footer>
    </div>

    <script src="<?php echo asset('/'); ?>assets/js/customJS/recharge.js" type="text/javascript"></script>
    <!-- REQUIRED JS SCRIPTS -->


    <!-- jQuery 2.1.3 -->
    <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.2.3.min.js") }}"></script>

    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>

    <!-- AdminLTE App -->
    <!--<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>-->


    <!-- Validate of jquery -->
    <script src="{{ asset ("assets/js/jquery.validate.min.js") }}" type="text/javascript"></script>

    <!-- Sweat alert javascript -->
    <script src="{{ asset ("assets/js/sweatalert/sweetalert.min.js") }}" type="text/javascript"></script>



    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <!-- jQuery 2.2.3 -->
    <!-- Bootstrap 3.3.6 -->
    <!-- DataTables -->
    <script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

    <!-- SlimScroll -->
    <script src="{{ asset ("/bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>

    <!-- FastClick -->
    <script src="{{ asset ("/bower_components/admin-lte/plugins/fastclick/fastclick.js") }}"></script>

    <!-- AdminLTE App -->
    <!--<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}"></script>-->

    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset ("/bower_components/admin-lte/dist/js/demo.js") }}"></script>
    <!-- page script -->

    <script src="<?php echo asset('/'); ?>assets/js/customJS/appURL.js" type="text/javascript"></script>
</body>
</html>
