<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CommonMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        /* If user is admin */
        if (Auth::user()->hasRole('Super Admin')) {
            return $next($request);
        }

        /* If user is admin or has permission to manage category */
        if ($request->is('categories') || $request->is('category_up/*') || $request->is('category_down/*') || $request->is('service_up/*') || $request->is('service_down/*')) {
            if (!Auth::user()->hasPermissionTo('Manage Category')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to manage Services */
        if ($request->is('services') || $request->is('serviceinactivelist') || $request->is('addservice/*') || $request->is('addservice') || $request->is('viewservice/*') || $request->is('servicereport') || $request->is('/registerservice')) {
            if (!Auth::user()->hasPermissionTo('Manage Service')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to manage configuration setting */
        if ($request->is('configuration')) {
            if (!Auth::user()->hasPermissionTo('Set Config')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to Manage or his services or reports */
        if ($request->is('allusers')) {
            if (!Auth::user()->hasAnyPermission([
                        'Manage Users',
                        'Enable Service Permission For Users',
                        'View User Email', 'View User SMS',
                        'Manage User Contacts',
                        'Download User Family List Report',
                        'Download User With Services Report',
                        'Download User Email List Report',
                        'Download User Number List Report'])) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to manage users */
        if ($request->is('userinactivelist') || $request->is('viewuser/*') || $request->is('adduser/*') || $request->is('adduser') || $request->is('userloggedhistory/*') || $request->is('user_service_history_report/*') || $request->is('all_user_service_history_report/*')) {
            if (!Auth::user()->hasPermissionTo('Manage Users')) {
                abort('401');
            } else {
                return $next($request);
            }
        }


        /* If user is admin or has permission to manage user service pemssion setting */
        if ($request->is('userservicedetails/*')) {
            if (!Auth::user()->hasPermissionTo('Enable Service Permission For Users')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to manage services for facility */
        if ($request->is('facilityservicedetails/*')) {
            if (!Auth::user()->hasPermissionTo('Enable Services For Facility')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to view all emails */
        if ($request->is('sentuseremaillist/*')) {
            if (!Auth::user()->hasPermissionTo('View User Email')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to manage facility or his any setting */
        if ($request->is('facilities')) {
            if (!Auth::user()->hasAnyPermission([
                        'Manage Facility',
                        'Download Facility Service Report',
                        'Enable Services For Facility',
                        'Download Facility Report',
                        'Download Facility Users Report',
                        'Download Facility Inactive Users Report',
                        'Download Facility List Report'])) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to manage Facility add/delete/edit setting */
        if ($request->is('addfacility/*') || $request->is('viewfacility/*') || $request->is('addfacility') || $request->is('facilityinactivelistIf') || $request->is('all_user_service_history_report/*')) {// user has permission to manage Configuration
            if (!Auth::user()->hasPermissionTo('Manage Facility')) {
                abort('401');
            } else {
                return $next($request);
            }
        }
        /* If user has permission to download inmate report with his services */
        if ($request->is('userreport/*')) {
            if (!Auth::user()->hasPermissionTo('Download User With Services Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download Facility report */
        if ($request->is('facilityreport')) {
            if (!Auth::user()->hasPermissionTo('Download Facility List Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download inmate report with his services */
        if ($request->is('perfacilityreport/*')) {
            if (!Auth::user()->hasPermissionTo('Download Facility Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download facility User report */
        if ($request->is('facilityusersreport/*')) {
            if (!Auth::user()->hasPermissionTo('Download Facility Users Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download inactive users report for each facility */
        if ($request->is('inactiveuser_facilityreport/*')) {
            if (!Auth::user()->hasPermissionTo('Download Facility Inactive Users Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download facility service report */
        if ($request->is('facility_service_report/*')) {
            if (!Auth::user()->hasPermissionTo('Download Facility Service Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download inactive facility report */
        if ($request->is('inactivefacilityreport')) {
            if (!Auth::user()->hasPermissionTo('Download Inactive Facility List Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download inmate  email report */
        if ($request->is('user_email_report/*')) {
            if (!Auth::user()->hasPermissionTo('Download User Email List Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download inmate  contact number list report */
        if ($request->is('user_contact_report/*')) {
            if (!Auth::user()->hasPermissionTo('Download User Number List Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download vendor report */
        if ($request->is('vendor_report/*')) {
            if (!Auth::user()->hasPermissionTo('Download Vendor Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user has permission to download inmate family report */
        if ($request->is('user_family_report')) {
            if (!Auth::user()->hasPermissionTo('Download User Family List Report')) {
                abort('401');
            } else {
                return $next($request);
            }
        }
    

        /* If user is admin or has permission to Manage Device */
        if ($request->is('adddevice/*') || $request->is('viewdevice/*') || $request->is('devices') || $request->is('deviceinactivelist')) {
            if (!Auth::user()->hasPermissionTo('Manage Device')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to Manage Conatcts which user inmate has added */
        if ($request->is('contactlist') || $request->is('deletecontact/*') || $request->is('updatecontact/*')) {
            if (!Auth::user()->hasPermissionTo('Manage User Contacts')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to Manage Inmate Family  */
        if ($request->is('families/*') || $request->is('addfamily/*/*') || $request->is('viewfamily/*/*') || $request->is('familiyinactivelist/*')) {
            if (!Auth::user()->hasPermissionTo('Manage Family')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to See Permssions */
        if ($request->is('permissions')) {
            if (!Auth::user()->hasPermissionTo('Manage Permission')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        /* If user is admin or has permission to Manage Roles */
        if ($request->is('roles') || $request->is('roles/create') || $request->is('roles/*/edit')) {//If user is creating a user
            if (!Auth::user()->hasPermissionTo('Manage Roles')) {
                abort('401');
            } else {
                return $next($request);
            }
        }
        return $next($request);
    }


}
