
<!-- Main Header -->
<header class="main-header">
    <!-- Logo --> 
    <a href="#" class="logo">    
        <span class="logo-mini"><img src="{{ asset ("/bower_components/admin-lte/images/logo2.png") }}"/></span>
        <span class="logo-lg"><img src="{{ asset ("/bower_components/admin-lte/images/logo2.png") }}" /></span>

    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> Menu
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">Hello, @if($inmate_details) {{ $inmate_details->first_name}}  {{ $inmate_details->last_name }} @else @endif </span>
                    </a>
                 
                </li>
            </ul>
        </div>
    </nav>

</header>