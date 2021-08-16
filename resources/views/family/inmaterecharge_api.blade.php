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

                    <div class="col-md-6 col-md-push-3" id='verifypage'>
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h2 class="box-title"><b>Inmate Details</b></h2>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form role="form" method="post" action="javascript:;" id="inmateData">
                                <div class="box-body">
                                    <div class="row no-margin">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="">Inmate's First Name<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="first_name" placeholder="Please enter first name">

                                            </div>
                                        </div>

                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="">Inmate's last Name<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="last_name"  placeholder="Please enter last name">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="">Inmate ID<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="user_id"  placeholder="Please enter User ID">
                                        <span id="cpc_msg"  style="color: red;display: none;">Please add funds on CPC Site.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer text-right">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" id="VerifyInmate">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8" id='redirectpage' style="display:none;">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h2 class="box-title"><b>Please Confirm</b></h2>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->

                            <div class="box-body">
                                <div class="row no-margin">
                                    <div class="col-md-10">
                                        <div class="box box-primary">
                                            <form role="form" method="post" action="https://demo.globalgatewaye4.firstdata.com/payment" >
                                                <input name="x_login"  type="hidden" >
                                                <input name="x_amount" type="hidden"> 
                                                <input name="x_fp_sequence" type="hidden"> 
                                                <input name="x_fp_timestamp" type="hidden"> 
                                                <input name="x_fp_hash"  type="hidden"> 
                                                <input name="x_show_form" value="PAYMENT_FORM" type="hidden"> 
                                                <input name="x_relay_response" value="TRUE" type="hidden"> 
                                                <input name="x_cust_id" value="" type="hidden"> 
                                                <input name="x_email" value="" type="hidden"> 
                                                <input name="x_first_name" value="" type="hidden"> 
                                                <input name="x_last_name" value="" type="hidden"> 
                                                <input name="x_relay_url" value="" type="hidden"> 
                                                <input name="x_description" value="" type="hidden"> 
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Amount</label>
                                                        <input type="text" class="form-control" id="confirm_amount" name="confirm_amount"  disabled="disabled">
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer text-center">
                                                    <input type="submit" class="btn btn-primary" value="Make Payment">
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>

                            </div>                  
                        </div>
                    </div>
                    <div class="col-md-12 " id="registerpage" style="display:none;">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h2 class="box-title"><b>Please enter your details first</b></h2>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form role="form" method="post" action="javascript:;" id="familyData">
                                <input type="hidden" class="form-control" name="inmate_id" id="inmate_id" >
                                <div class="box-body">
                                    <div class="row no-margin">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label for="">First Name<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="first_name1"  placeholder="Please enter first name">

                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label for="">Last Name<i class="requiredInput text-red">*</i></label>
                                                <input type="text" class="form-control" name="last_name1"  placeholder="Please enter last name">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-margin">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Email<i class="requiredInput text-red">*</i></label>
                                                <input type="email" class="form-control" name="email"  placeholder="Please enter email id">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Phone </label>
                                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Please enter phone">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-margin">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">State </label>
                                                <input type="text" class="form-control" name="state" id="state" placeholder="Please enter state">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">City </label>
                                                <input type="text" class="form-control" name="city"  id="city" placeholder="Please enter city">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-margin">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Zip </label>
                                                <input type="text" class="form-control" name="zip" id="zip" placeholder="Please enter zip">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Address Line 1 </label>
                                                <input type="text" class="form-control"  name="address_line_1" id="address_line_1" placeholder="Please enter address line 1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-margin">                                      
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Address Line 2 </label>
                                                <input type="text" class="form-control" name="address_line_2"  id="address_line_2" placeholder="Please enter address line 2">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer text-right">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" id="RegisterFriend">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->

                <!--Model for payment amount-->
                <div id="inmateAccountRecharge"  data-backdrop="static" class="modal fade example-modal" role="dialog"> 
                    <div class="modal-content modal-dialog">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" style="font-size:-webkit-xxx-large;" id="patientImageUploadModalCancelBtn">&times;</button>
                            <h4 class="modal-title">User account recharge</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" method="post" id="addAmount" action="javascript:;">
<!--                                {{csrf_field()}}-->
                                <div class="row">                        
                                    <div class="col-xs-12">
                                        <div class="upload-profile-popup clearfix">                               
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Amount</label>
                                                        <input type="text" class="form-control" name="amount" value="" id="amount" placeholder="Please enter recharge amount">
                                                       </div>
                                                </div> 
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input class="btn btn-primary AmountSubmit" id="AmountSubmit" value="Submit" type="submit"> 
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                            <!-- This Screen FOr Card Details FOr Patient-->
                        </div>
                    </div>
                </div>
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
