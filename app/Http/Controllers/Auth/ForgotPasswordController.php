<?php
/**
 * Register Admin To manage the Application 
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
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Register Admin To manage the Application 
 * 
 * PHP version 7.2
 * 
 * @category Admincontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
