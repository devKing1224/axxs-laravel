<?php
/**
 * Admin login To manage the Application 
 * 
 * PHP version 7.2
 * 
 * @category Admincontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Admin Login To manage the Application 
 * 
 * @category Admincontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }
    
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function showLoginForm() 
    {
        return view('login');
    }
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }
    
    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request user details
     * @param mixed                    $user    user details
     * 
     * @return mixed
     */
   protected function authenticated(Request $request, $user) {
        if (isset($user) && !empty($user)) {
            if ($user->role_id == 1) {
                //redirect('dashboard');
                return redirect('dashboard');
            } else if (($user->hasAnyRole(['Facility Admin', 'Facility Staff']))) {
                if ($user->hasRole('Facility Staff')) {
                    $facility = \App\Facility::where('facility_user_id', $user->admin_id)->first();
                } else {
                    $facility = \App\Facility::where('facility_user_id', $user->id)->first();
                }

                if ($facility->is_deleted == 1) {
                    auth()->logout();
                    $message = 'This account is inactive, please contact your Administrator.';
                    return view('emails.varifyemail', array('message' => $message));
                } else {
                    return redirect('facilitydashboard');
                }
            } else if ($user->role_id == 3) {
                $family = \App\Family::where('family_user_id', $user->id)->first();
                $inmate = \App\User::where('id', $user->admin_id)->first();
                $facility = \App\Facility::where('facility_user_id', $inmate->admin_id)->first();
                if ($family->is_deleted == 1 || ($facility->is_deleted == 1) || ($inmate->is_deleted == 1)) {
                    auth()->logout();
                    $message = 'This account is inactive, please contact your Administrator.';

                    return view('emails.varifyemail', array('message' => $message));
                } else {
                    return redirect('familydashboard');
                }
            } else if ($user->role_id == 4) {
                auth()->logout();
                // redirect('familydashboard');
                return view('login');
            } else if ($user->is_deleted == 0) {
                return redirect('dashboard');
            } else {
                auth()->logout();
                $message = 'This account is inactive, please contact your Administrator.';

                return view('emails.varifyemail', array('message' => $message));
            }
        }
    }
}
