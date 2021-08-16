<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TheAxxsTablet</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}">
        <!-- Font Awesome -->

        <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css")}}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/skins/skin-blue.min.css")}}">
        <link href="{{ asset("/assets/css/custom.css")}}" rel="stylesheet" type="text/css" />


    </head>
    <body class="hold-transition login-page" >
        <div class="errorbox">
            <div class="login-logo">
                <img src="{{ asset ("/bower_components/admin-lte/images/logo.png") }}" /><a href="#"><b>TheAxxsTablet</b></a>
            </div>
            <div class="alert alert-success" id="alertDiv" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                <span id="alert"></span>
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body background_transparent ">
                <h1 class="text-center">
                    {{ $message }}
                </h1>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->


        <!-- jQuery 2.2.3 -->
        <script src="{{ asset("/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js")}}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
        <!-- iCheck -->

    </body>
</html>