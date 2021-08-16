<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use File;
use DB;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;

class UserController extends Controller {

    /**
     * Add Middleware for security.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(['auth', 'clearance'])->except(['userImageUpload']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //Get all users and pass it to the view
        $users = User::where('is_deleted', 0)
            ->whereNotIn('role_id', [2, 3, 4, 8])
            ->orderBy('first_name', 'ASC')
            ->get();

        return view('users.index')->with('users', $users);
    }

    /**
     * Display a listing of the inactive users.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactiveSpec() {
        //Get all users and pass it to the view
        $users = User::where('is_deleted', 1)
            ->whereNotIn('role_id', [2, 3, 4, 8])
            ->orderBy('first_name', 'ASC')
            ->get();

        return view('users.inactiveus')->with('users', $users);
    }

    public function activateUser($id){
        //Find a user with a given id and make him inactive
        $user = User::findOrFail($id);
        $user->is_deleted = '0';
        $user->save();
        $message = \Lang::get('roles.user_activate');

        return response()->json(array(
            'Code' => 200,
            'Message' => \Lang::get('roles.success'),
            'Status' => $message,
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //Get all roles and pass it to the view
        $roles = Role::whereNotIn('id', [2, 3, 4, 8])->pluck('name', 'id');
        return view('users.create', ['roles' => $roles]);
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
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'min:6|confirmed',
        ]);

        $request['status'] = 0;
        $request['password'] = bcrypt($request->password);
        $role = $request['role_id'];
        if(!isset($role) ){
            $request['role_id']= 0;
        }
        $user = User::create($request->all());


        //Checking if a role was selected
        if (isset($role)) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();
            $user->assignRole($role_r); //Assigning role to user
        }
        //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message', 'User successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::where('id',$id)->whereNotIn('role_id', [2, 3, 4, 8])->first(); //Get user with specified id
        $roles = Role::whereNotIn('id', [2, 3, 4, 8])->pluck('name', 'id'); //Get all roles

        if(!empty($user)){
            return view('users.edit', compact('user', 'roles')); //pass user and roles data to view
        } else {
            return redirect()->route('users.index')
                ->with('flash_message', 'Invalid Url');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id
        //Validate name, email and password fields
        $this->validate($request, [
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'confirmed'
        ]);

        $roles = $request['role_id'];
        if(!isset($roles) ){
            $request['role_id']= 0;
        }
        if(isset($request['password'])) {
            $request['password'] = bcrypt($request->password);
        }

        $input = $request->all(); //Retreive the all fields except password
        $user->fill($input)->save();

        if ($id == 1) {
            $message = \Lang::get('roles.admin_edit');
        } else {
            if (isset($roles)) {
                $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
            } else {
                $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
            }
            $message = \Lang::get('roles.user_edit');
        }

        return redirect()->route('users.index')
            ->with('flash_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //Find a user with a given id and make him inactive
        $user = User::findOrFail($id);
        if ($user->hasAnyRole('Super Admin|Facility Admin|Family Admin|Inmate|Facility Staff')) {
            $message = \Lang::get('roles.user_not_delete');
        } else {
            $user->is_deleted = '1';
            $user->save();
            $message = \Lang::get('roles.user_delete');
        }
        return response()->json(array(
            'Code' => 200,
            'Message' => \Lang::get('roles.success'),
            'Status' => $message,
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function userImageUpload(Request $request) {
        //Find a user with a given id and make him inactive
        $data = $request->input();
        $user = User::findOrFail($data['id']);

        if ($request->hasFile('user_icon')) {
            $image_path = $user->user_image;
            if ($image_path) {
                $pathimg = public_path('images/') . substr($image_path, strrpos($image_path, '/') + 1);
                File::delete($pathimg);
            }

            $image = $request->file('user_icon');
            $logo_url = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images');
            $image->move($destinationPath, $logo_url);
            $data['user_image'] = asset('/images') . "/" . $logo_url;
            $user->user_image = $data['user_image'];
            $user->save();
            return response()->json(array(
                'Code' => 200,
                'Message' => \Lang::get('roles.success'),
            ));
        } else {
            return response()->json(array(
                'Code' => 400,
                'Message' => \Lang::get('roles.failure'),
            ));
        }
    }

}
