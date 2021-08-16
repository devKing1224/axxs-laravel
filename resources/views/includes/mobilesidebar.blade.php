<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar inmate panel -->
        @if(!isset($generalmail))
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('inmate.sendmail', ['inmate_id'=> $inmate_id, 'service_id' => $service_id]) }}">
                    <i class="fa fa-cogs"></i>
                    <span>Compose Email</span>
                </a>
            </li>
        </ul>
        
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{route('emailid',['inmate_id' => $inmate_id,'service_id' => $service_id])}}">
                    <i class="fa fa-edit"></i>
                    <span>Add & Manage Email</span>
                </a>
            </li>
        </ul>
        
         <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('viewallemails', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-inbox"></i>
                    <span>Inbox</span>
                </a>
            </li>
        </ul>
           <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('inmate.sentbox', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-envelope-o"></i>
                    <span>Sent</span>
                </a>
            </li> 
        </ul>
           <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('viewdeletedemail', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-trash-o"></i>
                    <span>Trash</span>
                </a>
            </li> 
        </ul>
        @elseif(isset($generalmail))
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{ route('generalinbox', ['inmate_id'=> $inmate_id,'service_id' => $service_id]) }}">
                    <i class="fa fa-inbox"></i>
                    <span>Inbox</span>
                </a>
            </li>
        </ul>
        @endif
    </section>
    <!-- /.sidebar -->
</aside>
<!-- Left side column. contains the logo and sidebar -->