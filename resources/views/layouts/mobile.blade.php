<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
    <!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
    -->
<html>
    <head>
        @yield('styles')
        @include('includes.head')
    </head>
    <body class="skin-blue sidebar-mini sidebar-collapse wysihtml5-supported">
    
            <div class="wrapper">
                @include('includes.mobileheader')
                <div id="main" class="row">
                    <!-- sidebar content -->
                    <div id="sidebar">
                        @include('includes.mobilesidebar')
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