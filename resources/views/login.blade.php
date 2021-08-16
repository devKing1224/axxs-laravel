<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>TheAxxsTablet</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css")}}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/skins/skin-blue.min.css")}}">
        
            <!--sweat alert css>-->
        <link href="{{ asset("/assets/css/sweatalert/sweetalert.css")}}" rel="stylesheet" type="text/css" />
        
         <link href="{{ asset("/assets/css/custom.css")}}" rel="stylesheet" type="text/css" />
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page" >
        <div class="login-box">
            <div class="login-logo">
                <img src="{{ asset ("/bower_components/admin-lte/images/logo.png") }}" /><a href="#"><b>TheAxxsTablet</b></a>
            </div>
            <div class="alert alert-success" id="alertDiv" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                <span id="alert"></span>
            </div>
            @include('layouts._errors')
            <!-- /.login-logo -->
            <div class="login-box-body  pd_0">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="{{ route('login')}}" method="post">
                    {{csrf_field()}}
                    <div class="col-xs-12" >
                        <div class="form-group has-feedback">
                            <input type="text" name="username" class="form-control" placeholder="Username">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xs-12" >
                        <div class="form-group has-feedback">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-xs-12 ">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        </div>
                        <div class="row pt_12 pd_12" >
                         <div class="col-xs-12 ">
                            Forgot Password, Click here<a href="javascript:;" data-toggle="modal" data-target="#resetPasswordModal"> Reset Password </a>
                        </div>
                       
                    </div>
               
                    <div class="row  bg-success pt_12 pd_12" style="border-top:1px solid gray;">
                         <div class="col-xs-12">
                          Family & Friends Send Funds Now &nbsp;&nbsp;&nbsp;   <a href="{{url('/')}}/user_recharge" class="btn btn-warning btn-xs"><i class="fa fa-money"></i> Send Funds</a>
                        </div>
                       
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <!--Modal for reset password-->
        <div id="resetPasswordModal"  data-backdrop="static" class="modal fade example-modal" role="dialog"> 
            <div class="modal-content modal-dialog ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="font-size:-webkit-xxx-large;" id="patientImageUploadModalCancelBtn">&times;</button>
                    <h4 class="modal-title">Reset Password</h4>
                </div>
                <div class="modal-body">
                    <div class="row">                        
                        <div class="col-xs-12">
                            <div id="" class="upload-profile-popup clearfix">                               
                                <form action="javascript:;" method="post" id="">
                                
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">username</label>
                                                <input type="text" class="form-control" name="username" value="" id="username" placeholder="Please enter your username">
                                            </div>
                                        </div> 
                                         <!-- /.col -->
                                        <div class="col-xs-4">
                                            <button type="submit" class="btn btn-primary btn-block btn-flat resetPasseordButton">Submit</button>
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

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset("/bower_components/admin-lte/plugins/jQuery/jQuery-2.2.3.min.js")}}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
        <!-- iCheck -->
        <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js")}}"></script>
        
        <script src="{{ asset ("assets/js/sweatalert/sweetalert.min.js") }}" type="text/javascript"></script>
        
        <script src="{{ asset("assets/js/customJS/login.js")}}"></script>
        <script src="{{ asset("assets/js/customJS/appURL.js")}}"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>