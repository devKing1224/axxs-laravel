<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller {

    protected function guard() {
        return Auth::guard('admin');
    }

    public function __construct() {
//        $this->middleware(['auth']);
        $this->middleware(['auth', 'common']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::orderBy('name','ASC')->whereNotIn('id',[3,4])->get(); //Get all roles

        return view('roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permissions = Permission::orderBy('name')->get(); //Get all permissions

        return view('roles.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Validate name and permissions field
        $this->validate($request, [
            'name' => 'required|unique:roles|max:20',
            'permissions' => 'required',
                ]
        );

        $name = $request['name'];

        $role = new Role();
        $role->name = $name;
        $role->guard_name = 'admin';

        $permissions = $request['permissions'];

        $role->save();
        //Looping thru selected permissions
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            //Fetch the newly created role and assign permission
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }

        return redirect()->route('roles.index')
                        ->with('flash_message', 'Role ' . $role->name . ' added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $role = Role::findOrFail($id);
        if ($role->name == 'Inmate') {
           return redirect()->route('roles.index')
                        ->with('flash_message', 'Invalid Url');
        } else {
            $permissions = Permission::orderBy('name')->get();
            return view('roles.edit', compact('role', 'permissions'));
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

        $role = Role::findOrFail($id); //Get role with the given id
        //Validate name and permission fields
        $this->validate($request, [
            'name' => 'required|max:50|unique:roles,name,' . $id,
            'permissions' => 'required',
        ]);

        $input = $request->except(['permissions']);

        if ($role->name == 'Facility Admin' || $role->name == 'Facility Staff' || $role->name == 'Super Admin' || $role->name == 'Family Admin' || $role->name == 'Inmate') {
            $permissions = $request['permissions'];
        } else {
            $permissions = $request['permissions'];
            $role->fill($input)->save();
        }


        $p_all = Permission::all(); //Get all permissions

        if ($role->name == 'Facility Admin') {
            $rolestaff = Role::where('name', 'Facility Staff')->first();
            foreach ($p_all as $p) {
                $rolestaff->revokePermissionTo($p); //Remove all permissions associated with role
            }

            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
                $rolestaff->givePermissionTo($p);  //Assign permission to role
            }
            $rolestaff->revokePermissionTo('Manage Staff');
        }
        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
            $role->givePermissionTo($p);  //Assign permission to role
        }

        return redirect()->route('roles.index')
                        ->with('flash_message', 'Role ' . $role->name . ' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        $role = Role::findOrFail($id);

        if ($role->name == 'Super Admin' || $role->name == 'Facility Admin' || $role->name == 'Family Admin' || $role->name == 'Facility Staff') {
            $message = \Lang::get('roles.role_not_delete');
        } else {
            $role->delete();
            $message = \Lang::get('roles.role_delete');
        }

        return response()->json(array(
                    'Code' => 200,
                    'Message' => \Lang::get('roles.success'),
                    'Status' => $message,
        ));
    }

}
