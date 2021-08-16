<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
    <!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
    -->
<html>
    <head>
        
        @include('includes.head')
    </head>
    <body class="skin-blue sidebar-mini">
    
            <div class="wrapper">
                @include('includes.header')
                <div id="main" class="row">
                    <!-- sidebar content -->
                    <div id="sidebar">
                        @include('includes.sidebar')
                    </div>
                    <!-- main content -->
                    <div id="content" class="col-md-12">
                        @yield('content')
                    </div>
                </div>
                @include('includes.footer')
            </div>
       
    </body>
</html>