<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
//Importing laravel-permission models
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('role_read', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        });

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('role_create', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['create', 'store']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('role_update', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['edit', 'update']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('role_delete', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('id', '!=', 1)->where('guard_name', '=', 'admin')->get(); //Get all roles

        return view('admin.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::where('guard_name', '=', 'admin')->orderBy('id', 'asc')->pluck('id', 'name')->toArray(); //Get all permissions
        $target = [];
        foreach ($permissions as $k => $v) {
            $keys = explode("_", $k);
            if (count($keys) > 1) {
                $target[$keys[0]][$v] = $keys[1];
            } else {
                $target[$k] = $v;
            }
        }
        return view('admin.roles.create', ['permissions' => $target]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name and permissions field
        $this->validate($request, [
            'name' => 'required|unique:roles|max:10',
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

        alert()->success($role->name, 'added!');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::orderBy('id', 'asc')->where('guard_name', '=', 'admin')->pluck('id', 'name')->toArray(); //Get all permissions
        $target = [];
        foreach ($permissions as $k => $v) {
            $keys = explode("_", $k);
            if (count($keys) > 1) {
                $target[$keys[0]][$v] = $keys[1];
            } else {
                $target[$k] = $v;
            }
        }

        $permissions = $target;
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $role = Role::findOrFail($id); //Get role with the given id
        //Validate name and permission fields
        $this->validate($request, [
            'name' => 'required|max:10|unique:roles,name,' . $id,
            'permissions' => 'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        $role->fill($input)->save();

        $p_all = Permission::all(); //Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
            $role->givePermissionTo($p); //Assign permission to role
        }

        alert()->success($role->name, 'updated!');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role->id == 1 || $role->id == 2) {
            return response()->json(["status" => false, "message" => 'Cannot delete Default Role']);
        }
        $role->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

}
