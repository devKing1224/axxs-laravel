<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->

        <!-- sidebar menu :: style can be found in sidebar.less -->

        @hasanyrole('Facility Admin|Family Admin|Facility Staff|Facility Administrator')
        @hasanyrole('Facility Admin|Facility Staff')
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{action('FacilityController@facilityDashboard')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
        </ul>
        @endhasrole
        @hasrole('Family Admin')
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{action('FamilyController@familyDashboard')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
        </ul>
        @endhasrole
        @hasrole('Facility Administrator')
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{action('FacilityAdminController@fadminDashboard')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
        </ul>
        @endhasrole

        @else
        <ul class="sidebar-menu sidebarClick">
            <li class="treeview">
                <a href="{{action('SuperadminController@index')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
        </ul>
        @endhasanyrole
        @if(Auth::user()->hasAnyRole('Super Admin'))
        <ul class="sidebar-menu" id="device">
            <li class="treeview" id="deviceAfterClickOpen">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>API Setting</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('APIController@apiNews')}}"><i class="fa fa-circle-o"></i>News API</a></li>
                    <li><a href="{{action('APIController@apiSoundcloud')}}"><i class="fa fa-circle-o"></i>SoundCloud</a></li>
                </ul>
            </li>
        </ul>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Facility Admin'))
        <ul class="sidebar-menu sidebarClick" id="setting">
            <li class="treeview" id="settingAfterClickOpen">
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Settings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->hasRole('Super Admin')) 
                    <li><a href="{{route('superadmin.configuration')}}"><i class="fa fa-cog"></i>Configuration</a></li>
                    @endif
                    @can('Manage Blacklisted Word') 
                    <li><a href="{{action('BlackListedWordController@index')}}"><i class="fa fa-ban"></i>Blacklisted Keywords</a></li>@endcan 
                    @can('Manage Whitelisted URL') 
                    <li><a href="{{action('BlackListedWordController@urllist')}}"><i class="fa fa-external-link"></i>Whitelisted URL</a></li>@endcan
                    @can('Manage Whitelisted Email') 
                    <li><a href="{{url('whemaillist')}}"><i class="fa fa-external-link"></i>Whitelisted Email</a></li>@endcan 
                    @if(Auth::user()->hasRole('Super Admin'))
                    <li><a href="{{action('UserController@index')}}"><i class="fa fa-user-plus"></i>Manage Specialists</a></li>
                    <li><a href="{{action('UserController@inactiveSpec')}}"><i class="fa fa-user-plus"></i>Inactive Specialists</a></li>
                    <li><a href="{{action('PermissionController@index')}}"><i class="fa fa-check-square"></i>List Permissions</a></li>
                    <li><a href="{{action('RoleController@index')}}"><i class="fa fa-key"></i>Manage Roles</a></li>
                    <li><a href="{{route('category.list')}}"><i class="fa fa-cogs"></i>Manage Categories</a></li>@endif
                </ul>
            </li>
        </ul>
        @elseif(Auth::user()->hasAnyPermission(['Manage Permission', 'Manage Roles',
        'Manage Setting', 'Manage Specialist', 'Manage Category', 'Set Config']))
        <ul class="sidebar-menu sidebarClick" id="setting">
            <li class="treeview" id="settingAfterClickOpen">
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Settings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('Set Config')
                    <li><a href="{{route('superadmin.configuration')}}"><i class="fa fa-cog"></i>Configuration</a></li>
                    @endcan
                    @can('Manage Specialist')
                    <li><a href="{{action('UserController@index')}}"><i class="fa fa-user-plus"></i>Manage Specialists</a></li>
                    @endcan
                    @can('Manage Permission')
                    <li><a href="{{action('PermissionController@index')}}"><i class="fa fa-check-square"></i>List Permissions</a></li>
                    @endcan
                    @can('Manage Roles')
                    <li><a href="{{action('RoleController@index')}}"><i class="fa fa-key"></i>Manage Roles</a></li>
                    @endcan
                    @can('Manage Category')
                    <li><a href="{{route('category.list')}}"><i class="fa fa-cogs"></i>Manage Categories</a></li>
                    @endcan
                </ul>
            </li>
        </ul>

        @endif
        @can('Manage Facility Administrator') 
            <ul class="sidebar-menu sidebarClick" id="facilityadmin">
                <li class="treeview" id="facilityadminAfterClickOpen">
                    <a href="#">
                        <i class="fa fa-university"></i>
                        <span>Facility Administrator</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('fadmin.add')}}"><i class="fa fa-plus"></i>Add Administrator</a></li>
                        <li><a href="{{route('fadmin.list')}}"><i class="fa fa-bars"></i>List Administrator</a></li>
                        <li><a href="{{route('fadmin.inactivelist')}}"><i class="fa fa-user-times"></i>Inactive Administrator List</a></li>
                    </ul>
                </li>
            </ul>
         @endcan
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->can('Manage Facility'))
        <ul class="sidebar-menu sidebarClick" id="facility">
            <li class="treeview" id="facilityAfterClickOpen">
                <a href="#">
                    <i class="fa fa-university"></i>
                    <span>Facility</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('facility.add')}}"><i class="fa fa-plus"></i>Add Facility</a></li>
                    <li><a href="{{route('facility.list')}}"><i class="fa fa-bars"></i>List Facilities</a></li>
                    <li><a href="{{route('facility.inactivelist')}}"><i class="fa fa-user-times"></i>Inactive Facilities List</a></li>
                </ul>
            </li>
        </ul>

        @elseif(Auth::user()->hasAnyPermission(['Download Facility Service Report',
        'Enable Services For Facility', 'Download Facility Report', 
        'Download Facility Users Report', 'Download Facility Inactive Users Report',
        'Download Facility List Report']))
        <ul class="sidebar-menu sidebarClick" id="facility">
            <li class="treeview" id="facilityAfterClickOpen">
                <a href="#">
                    <i class="fa fa-university"></i>
                    <span>Facility</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('facility.list')}}"><i class="fa fa-bars"></i>List Facilities</a></li>
                </ul>
            </li>
        </ul>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->can('Manage Staff'))
        <ul class="sidebar-menu sidebarClick" id="facilitystaff">
            <li class="treeview" id="facilitystaffAfterClickOpen">
                <a href="#">
                    <i class="fa fa-user-circle"></i>
                    <span>Facility Staff</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('staffs.create')}}"><i class="fa fa-plus"></i>Add Staff</a></li>
                    <li><a href="{{route('staffs.index')}}"><i class="fa fa-bars"></i>List Staff</a></li>
                </ul>
            </li>
        </ul>
        @endif
        @if(Auth::user()->hasAnyRole('Super Admin|Facility Admin') || Auth::user()->can('Manage Users'))
        <ul class="sidebar-menu sidebarClick" id="inmate">
            <li class="treeview" id="inmateAfterClickOpen">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>User</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('InmateController@addInmateUI')}}"><i class="fa fa-plus"></i>Add User</a></li>
                    <li><a href="{{action('InmateController@inmateListUI')}}"><i class="fa fa-bars"></i>List Users</a></li>
                    <li><a href="{{action('InmateController@inmateInactiveListUI')}}"><i class="fa fa-user-times"></i>Inactive Users List</a></li>
                    <li><a href="{{route('inmate.reportlogin')}}"><i class="fa fa-user-times"></i>Users Disputed Login Report</a></li>
                </ul>
            </li>
        </ul>
        @elseif(Auth::user()->hasAnyPermission(['Enable Service Permission For Users',
        'View User Email', 'View User SMS', 'Manage User Contacts', 
        'Download User Family List Report', 'Download User With Services Report',
        'Download User Email List Report', 'Download User Number List Report']))
        <ul class="sidebar-menu sidebarClick" id="inmate">
            <li class="treeview" id="inmateAfterClickOpen">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>User</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('InmateController@inmateListUI')}}"><i class="fa fa-bars"></i>List Users</a></li>
                </ul>
            </li>
        </ul>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->can('Manage Company'))
           <ul class="sidebar-menu sidebarClick" id="company">
               <li class="treeview" id="companyAfterClickOpen">
                   <a href="#">
                       <i class="fa fa-university"></i>
                       <span>Manage Organization</span>
                       <span class="pull-right-container">
                           <i class="fa fa-angle-left pull-right"></i>
                       </span>
                   </a>
                   <ul class="treeview-menu">
                       <li><a href="{{route('cmpy.add')}}"><i class="fa fa-plus"></i>Add Organization</a></li>
                       <li><a href="{{route('cmpy.list')}}"><i class="fa fa-bars"></i>List Organization</a></li>
                       <!-- <li><a href="{{route('fadmin.inactivelist')}}"><i class="fa fa-user-times"></i>Inactive Administrator List</a></li> -->
                   </ul>
               </li>
           </ul>
        @endif

         @if(Auth::user()->can('Manage Email'))
        
        <ul class="sidebar-menu" id="device">
            <li class="treeview" id="deviceAfterClickOpen">
                <a href="#">
                    <i class="fa fa-envelope"></i>
                    <span>Email</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('EmailController@listEmailUI')}}"><i class="fa fa-circle-o"></i>List Email</a></li>
                    <li><a href="{{action('EmailController@sendEmailUI')}}"><i class="fa fa-circle-o"></i>Send Email</a></li>
                    <li><a href="{{action('EmailController@approvedemailUI')}}"><i class="fa fa-circle-o"></i>Approved Email</a></li>
                    <li><a href="{{action('EmailController@rejectedemailUI')}}"><i class="fa fa-circle-o"></i>Rejected Email</a></li>
                    
                </ul>
            </li>
        </ul>
        
        @endif

        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->can('Manage Service'))
        <ul class="sidebar-menu sidebarClick" id="service">
            <li class="treeview" id="serviceAfterClickOpen">
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Services</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->hasRole('Super Admin'))
                    <li><a href="{{route('service.add')}}"><i class="fa fa-plus"></i>Add Service</a></li>
                    @endif
                    <li><a href="{{route('service.list')}}"><i class="fa fa-bars"></i>List Services</a></li>
                    <li><a href="{{route('service.inactivelist')}}"><i class="fa fa-circle-o"></i>Inactive Services List</a></li>
                </ul>
            </li>
        </ul>
        @endif
        @if(Auth::user()->can('Manage Movie'))
        <ul class="sidebar-menu sidebarClick" id="movie">
            <li class="treeview" id="movieAfterClickOpen">
                <a href="#">
                    <i class="fa fa-video-camera"></i>
                    <span>Movies</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->hasRole('Super Admin'))
                    <li><a href="{{route('movie.add')}}"><i class="fa fa-plus"></i>Add Movie</a></li>
                    @endif
                    <li><a href="{{route('movie.list')}}"><i class="fa fa-bars"></i>List Movies</a></li>
                    <li><a href="{{route('inactivemovie.list')}}"><i class="fa fa-circle-o"></i>Inactive Movies</a></li>
                    <!-- <li><a href="{{route('service.inactivelist')}}"><i class="fa fa-circle-o"></i>Inactive Services List</a></li> -->
                </ul>
            </li>
        </ul>
        @endif
        @if(Auth::user()->hasRole('Super Admin'))
        <ul class="sidebar-menu sidebarClick" id="music">
            <li class="treeview" id="musicAfterClickOpen">
                <a href="#">
                    <i class="fa fa-music"></i>
                    <span>Music</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->hasRole('Super Admin'))
                    <li><a href="{{route('music.add')}}"><i class="fa fa-plus"></i>Add Music</a></li>
                    @endif
                    <li><a href="{{route('music.list')}}"><i class="fa fa-bars"></i>List Music</a></li>
                    <li><a href="{{route('inactivemusic.list')}}"><i class="fa fa-circle-o"></i>Inactive Music</a></li>
                    <!-- <li><a href="{{route('service.inactivelist')}}"><i class="fa fa-circle-o"></i>Inactive Services List</a></li> -->
                </ul>
            </li>
        </ul>
        @endif
        @can('Trace User Login')
        <ul class="sidebar-menu sidebarClick" id="service">
            <li class="treeview" id="serviceAfterClickOpen">
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Trace User Login</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    
                    <li><a href="{{route('tracelogin.list')}}"><i class="fa fa-bars"></i>View Login History</a></li>
                    
                    
                </ul>
            </li>
        </ul>
        @endcan

        @if(Auth::user()->hasAnyRole('Super Admin') || Auth::user()->can('Manage Device'))
        <ul class="sidebar-menu" id="device">
            <li class="treeview" id="deviceAfterClickOpen">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>Device</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('DeviceController@addDeviceUI')}}"><i class="fa fa-circle-o"></i>Add Device</a></li>
                    <li><a href="{{action('DeviceController@deviceListUI')}}"><i class="fa fa-circle-o"></i>List Devices</a></li>
                    <li><a href="{{action('DeviceController@deviceInactiveListUI')}}"><i class="fa fa-circle-o"></i>Inactive Devices List</a></li>
                </ul>
            </li>
        </ul>
        @endif

          @if(Auth::user()->hasAnyRole('Facility Admin'))
        <ul class="sidebar-menu" id="preemail">
            <li class="treeview" id="preemailAfterClickOpen">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>Pre-Approved Contacts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('PreApprovedEmailController@allPreEmail')}}"><i class="fa fa-circle-o"></i>Pre-Approved Email List</a></li>
                     <li><a href="{{action('PreApprovedEmailController@emailInactiveListUI')}}"><i class="fa fa-user-times"></i>Pre-Approved Inactive Emails</a></li>

                     <li><a href="{{action('PreApprovedEmailController@allPreContact')}}"><i class="fa fa-circle-o"></i>Pre-Approved Text List</a></li>

                     <li><a href="{{action('PreApprovedEmailController@contactInactiveListUI')}}"><i class="fa fa-user-times"></i>Pre-Approved Inactive Text<br>Contacts</a></li>
                 
                </ul>
            </li>
        </ul>
        @endif

        <?php if (isset(Auth::user()->role_id) && Auth::user()->role_id == 3) { ?>
            <ul class="sidebar-menu" id="family">
                <li class="treeview" id="familyAfterClickOpen">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>User</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('inmate.inmateactivity', ['inmate_id'=> Auth::user()->admin_id]) }}"><i class="fa fa-circle-o"></i>User Activity History</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="sidebar-menu" id="family">
                <li class="treeview" id="familyAfterClickOpen">
                    <a href="#">
                        <i class="fa fa-home"></i>
                        <span>Account</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('family.viewinmatefamily') }}"><i class="fa fa-circle-o"></i>User Information</a></li>
                        <li><a href="{{ route('family.familyrechargeactivity') }}"><i class="fa fa-circle-o"></i>Account Activity</a></li>
                    </ul>
                </li>
            </ul>
        <?php } ?>
    </section>
    <!-- /.sidebar -->
</aside>
<!-- Left side column. contains the logo and sidebar -->