<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar inmate panel -->
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('contactnumber', ['inmate_id'=> $inmate_id, 'service_id' => $service_id]) }}">
                    <i class="fa fa-edit"></i>
                    <span>Add & Manage Contact</span>
                </a>
            </li>
        </ul>
         <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('sendSMS', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-envelope-o"></i>
                    <span>Send Message</span>
                </a>
            </li>
        </ul>
           <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('viewnumberlist', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-inbox"></i>
                    <span>Inbox</span>
                </a>
            </li> 
        </ul>
           <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('viewdeletedsms', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-trash-o"></i>
                    <span>Trash</span>
                </a>
            </li> 
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<!-- Left side column. contains the logo and sidebar -->