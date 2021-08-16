<head>
    <meta charset="UTF-8">
     <title>{{ $page_title or "The Axxs Tablet" }}</title>
    
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>-->
 

     <link rel="stylesheet" href="<?php echo asset('/'); ?>assets/build/css/intlTelInput.css">
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
    <meta name="_token" content="{{ csrf_token() }}" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/skins/skin-blue.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
     <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" >
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
{{--    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>--}}
{{--   <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>--}}
   <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" >
    <!--sweat alert css>-->
   <link href="{{ asset("/assets/css/css-loader.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/assets/css/sweatalert/sweetalert.css")}}" rel="stylesheet" type="text/css" />
    <!-- End -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
 <link rel="stylesheet" href="{{ asset("/assets/css/main-custom.css")}}">
 
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>