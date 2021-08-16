<?php

/**
 * Registering staff and and managing records
 * 
 * @category StaffController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;

use App\Facility;
use App\User;
use App\StaffLog;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Mail\StaffEmail;
use Mail;
use Auth;

class StaffController extends Controller {

    /**
     * Add Middleware for security.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(['auth', 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Auth::user()->hasRole('Facility Admin')) {
            $users = User::with(['staffFacility' => function($q){
                $q->orderBy('facility_name','ASC');
            }])->role('Facility Staff')
                    ->where('admin_id', Auth::user()->id)
                    ->get();
        } else {
            $users = User::with(['staffFacility' => function($q){
                $q->orderBy('facility_name','ASC');
            }])->role('Facility Staff')
                    ->get();
            
        }
        if(!empty($users)){
           return view('staffs.index')->with('users', $users); 
        }
        else {
            abort('401');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $facilities = Facility::where('is_deleted', 0)->pluck('facility_name', 'facility_user_id');
        return view('staffs.create', ['facilities' => $facilities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        //Validate names, username,email and password fields
        $this->validate($request, [
            'first_name' => 'required|max:40|alpha',
            'last_name' => 'required|max:40|alpha',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:10|confirmed',
            'facility' => 'required'
        ]);

        $role_id = Role::where('name', 'Facility Staff')->first();
        $role = $role_id->id;
        $request['role_id'] = $role;
        $request['admin_id'] = $request['facility'];
        $password = $request['password'];
        $request['status'] = 0;
        $request['first_login'] = 1;
        $request['password'] = bcrypt($request->password);

        $user = User::create($request->all()); //Retrieving details
        //Send mail to staff facility
        /*if (isset($user->id) && !empty($user->id)) {
            $content = [
                'title' => 'Congratulations! You have been registered with TheAxxsTablet',
                'staffname' => $user->first_name . ' ' . $user->last_name,
                'username' => $user->username,
                'password' => $password,
            ];
            $receiverAddress = $user->email;
            $var = Mail::to($receiverAddress)->send(new StaffEmail($content));
        }*/

        //Checking if a role was selected
        if (isset($role)) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();
            $user->assignRole($role_r); //Assigning role to user
        }
        //Redirect to the users.index view and display message
        return redirect()->route('staffs.index')
                        ->with('flash_message', 'Staff successfully added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //Get user with specified id
        if (Auth::user()->hasRole('Facility Admin')) {
            $user = User::where('id', $id)->where('admin_id', Auth::user()->id)->first();
        } else {
            $user = User::findOrFail($id);
        }
        if (!isset($user) || !($user->hasRole('Facility Staff'))) {
            return redirect()->route('staffs.index')
                            ->with('flash_message', 'Invalid URL');
        }
        $facilities = Facility::where('is_deleted', 0)->select('name', 'facility_user_id')->get();
        return view('staffs.edit', compact('user', 'facilities')); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id
        //Validate name, email and password fields  
        $this->validate($request, [
            'first_name' => 'required|max:40|alpha',
            'last_name' => 'required|max:40|alpha',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:10|confirmed',
            'facility' => 'required',
        ]);

        $request['admin_id'] = $request['facility'];
        $input = $request->all(); //Retreive the all fields except password
        $input['password'] = bcrypt($request->password);
        $user->fill($input)->save();

        return redirect()->route('staffs.index')
                        ->with('flash_message', 'Staff Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (Auth::user()->hasRole('Facility Admin')) {
            $user = User::where('id', $id)->where('admin_id', Auth::user()->id)->first();
        } else {
            $user = User::findOrFail($id);
        }
        if (empty($user) || !($user->hasRole('Facility Staff'))) {
            return response()->json(array(
                        'Code' => 200,
                        'Message' => \Lang::get('roles.failure'),
                        'Status' => \Lang::get('roles.staff_not_action'),
            ));
        } else {
            $user->delete();
            return response()->json(array(
                        'Code' => 200,
                        'Message' => \Lang::get('roles.success'),
                        'Status' => \Lang::get('roles.staff_delete'),
            ));
        }
    }

    /**
     * Activate/Deactivate the specified resource from storage.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function staffActiveDeactive($id) {
        if (Auth::user()->hasRole('Facility Admin')) {
            $user = User::where('id', $id)->where('admin_id', Auth::user()->id)->first();
        } else {
            $user = User::findOrFail($id);
        }
        if (empty($user) || !($user->hasRole('Facility Staff'))) {
            return response()->json(array(
                        'Code' => 200,
                        'Message' => \Lang::get('roles.failure'),
                        'Status' =>  \Lang::get('roles.staff_not_action'),
            ));
        } else {
            if ($user->is_deleted == 0) {
                $user->is_deleted = 1;
                $message = \Lang::get('roles.staff_deactivate');
            } else {
                $user->is_deleted = 0;
                $message = \Lang::get('roles.staff_activate');
            }
            $user->save();
            return response()->json(array(
                        'Code' => 200,
                        'Message' => \Lang::get('roles.success'),
                        'Status' => $message,
            ));
        }
    }
    
        /**
     * Activate/Deactivate the specified resource from storage.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function staffActiveDetails($id) {
        if (Auth::user()->hasRole('Facility Admin')) {
            $user = User::where('id', $id)->where('admin_id', Auth::user()->id)->first();
        } else {
            $user = User::findOrFail($id);
        }
        if (empty($user) || !($user->hasRole('Facility Staff'))) {
             return redirect()->route('staffs.index')
                        ->with('flash_message', 'Invalid URL');
        } else { 
            $details = StaffLog::where('staff_id',$id)->orderBy('datetime', 'desc')->get();

            return view('staffs.activity', compact('details', 'user'));
        }
    }

}
