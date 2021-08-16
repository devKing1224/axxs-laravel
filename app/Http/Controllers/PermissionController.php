<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller {

    /**
     * common middleware lets only users with specific permission  to access these resources
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(['auth', 'common']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $permissions = Permission::orderBy('name','ASC')->get(); //Get all permissions

        return view('permissions.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      abort('401');
        $roles = Role::get(); //Get all roles

        return view('permissions.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
         abort('401');
        $this->validate($request, [
            'name' => 'required|max:40|unique:permissions',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;
        $permission->guard_name = 'admin';

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) { //If one or more role is selected
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

                $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                $r->givePermissionTo($permission);
            }
        }

        return redirect()->route('permissions.index')
                        ->with('flash_message', 'Permission ' . $permission->name . ' added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        abort('401');
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        abort('401');
        $permission = Permission::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:50|unique:permissions,name,' . $id,
        ]);
        $input = $request->all();
        $permission->fill($input)->save();

        return redirect()->route('permissions.index')
                        ->with('flash_message', 'Permission ' . $permission->name . ' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        abort('401');
        $permission = Permission::findOrFail($id);
        //Make it impossible to delete this specific permission 
        if ($permission->name == "Admin Role") {
            $message = \Lang::get('roles.permission_not_delete');
        } else {
            $permission->delete();
            $message = \Lang::get('roles.permission_delete');
        }

        return redirect()->route('permissions.index')
                        ->with('flash_message', $message);
    }

}
