<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
//Importing laravel-permission models
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('permission_read', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['index', 'show']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('permission_create', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['create', 'store']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('permission_update', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['edit', 'update']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('permission_delete', 'admin')) {
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
        $permissions = Permission::where('guard_name', '=', 'admin')->get(); //Get all permissions

        return view('admin.permissions.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 1)->where('guard_name', '=', 'admin')->get(); //Get all roles

        return view('admin.permissions.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:40',
        ]);

        $name = $request['name'];

        $permissions = $request->permissions;
        if (!empty($permissions)) {
            foreach ($permissions as $k => $v) {
                $permission = new Permission();
                $permission->name = trim($name) . '_' . $v;
                $permission->guard_name = 'admin';
                $permission->save();
            }
        } else {
            $permission = new Permission();
            $permission->name = trim($name);
            $permission->guard_name = 'admin';
            $permission->save();
        }

        $roles = $request['roles'];

        if (!empty($request['roles'])) { //If one or more role is selected
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
                foreach ($permissions as $k => $v) {
                    $perm_name = trim($name) . '_' . $v;
                    // $permission = Permission::where('name', '=', $perm_name)->first(); //Match input //permission to db record
                    $r->givePermissionTo($perm_name);
                }
            }
        }
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        alert()->success($name, 'added!');

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin/permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.edit', compact('permission'));
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
        $permission = Permission::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:40',
        ]);
        $input = $request->all();
        $permission->fill($input)->save();
        alert()->success($permission->name, 'updated!');
        return redirect()->route('admin.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }
    
public function changepass(){
    return view('admin.changepassword');
}
public function changepassword(Request $request){
    
    $admin = Auth::guard('admin')->user();
    $pass = trim($request->password) ;
    $newpass = trim($request->password_confirmation) ;
    if($pass == $newpass){
            $admin->password = $pass ;
            $admin->save();  
            alert()->success('Password change successfully.', 'Updated');
            return redirect()->route('admin.dashboard');
        }
    alert()->success('Password not matching.', 'danger');
    return redirect()->route('admin.dashboard');
}


public function changeUserpassword(Request $request){
    $user = User::find($request->user_id);
    $pass = trim($request->password) ;
    $newpass = trim($request->password_confirmation) ;
     if($pass == $newpass){ 
           $user->password = $pass ;
           $user->save();  
           alert()->success($user->name .' s Password change successfully.', 'Updated');
           return redirect()->route('admin.dashboard');
        }
    alert()->success('Password not matching.', 'danger');
    return redirect()->route('admin.dashboard');
  }

}
