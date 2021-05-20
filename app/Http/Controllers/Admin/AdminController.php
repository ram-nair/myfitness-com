<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use URL;
use Yajra\Datatables\Datatables;
use App\Audit;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('user_read', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['index', 'show']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('settings', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['profile']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('user_create', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['create', 'store']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('user_update', 'admin')) {
                return $next($request);
            } else {
                alert()->error('Your don\'t have permission to acces this page.', 'No Access!');
                return redirect()->route('admin.dashboard');
            }
        }, ['only' => ['edit', 'update']]);

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('admin')->user();
            if ($user->hasRole('super-admin', 'admin') || $user->hasPermissionTo('user_delete', 'admin')) {
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
        //Get all users and pass it to the view
        return view('admin.users.index');
    }

    /**
     * To display dynamic table by datatable
     *
     * @return mixed
     */
    public function datatable()
    {
        $currentUser = Auth::guard('admin')->user();
        $isSuperAdmin = $currentUser->hasRole('super-admin', 'admin');

        $admins = Admin::select('*')
            ->where('id', '!=', $currentUser->id)
            ->where('type', '=', 'admin');
        if ($isSuperAdmin == false) {
            $admins->where('admin_id', $currentUser->id);
        }
        return Datatables::of($admins)
            ->rawColumns(['actions'])
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })->editColumn('roles', function ($user) {
            return $user->roles()->pluck('name')->implode(',');
        })->editColumn('actions', function ($user) use ($currentUser, $isSuperAdmin) {
            $b = '';
            if ($isSuperAdmin || $currentUser->hasPermissionTo('user_update', 'admin')) {
                $b .= '<a href="' . URL::route('admin.users.edit', $user->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
            }

            if ($isSuperAdmin || $currentUser->hasPermissionTo('user_delete', 'admin')) {
                $b .= ' <a href="' . URL::route('admin.users.destroy', $user->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                $b .= ' <a href="' . URL::route('admin.users.show', $user->id) . '" class="btn btn-outline-danger btn-xs"><i class="fa fas fa-eye"></i></a>';
            }

            return $b;
        })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $roles = Role::where('id', '!=', 1)->where('guard_name', '=', 'admin')->get();
        return view('admin.users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        $request->merge(['type' => 'admin', 'admin_id' => Auth::guard('admin')->user()->id]);
        $user = Admin::create($request->only('admin_id', 'email', 'name', 'password', 'type'));

        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }
        //Redirect to the users.index view and display message
        alert()->success('User successfully added.', 'Added');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         /*$users = Admin::with('audits')->where('id', $id)->first();*/
       /*  $user = Admin::findOrFail($id);
        
        $audits = Audit::where('admin_id', $id)->orderBy('created_at','desc')->paginate(10);*/
$user = Admin::findOrFail($id);
        return view('admin.users.show',compact('user'));
            
        
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Admin::findOrFail($id); //Get user with specified id
        $roles = Role::where('id', '!=', 1)->where('guard_name', '=', 'admin')->get(); //Get all roles

        return view('admin.users.edit', compact('user', 'roles')); //pass user and roles data to view
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
        $user = Admin::findOrFail($id); //Get role specified by id
        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password_confirmation' => 'same:password',
        ]);
        $input = $request->only(['name', 'email', 'password']); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles

        if ($input['password'] !== null) {
            $input['remember_token'] = Str::random(32);
        } else {
            unset($input['password']);
        }

        $user->fill($input)->save();

        if (isset($roles)) {
            $user->roles()->sync($roles); //If one or more role is selected associate user to roles
        } else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        alert()->success('User details successfully updated.', 'Updated');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

    public function profile()
    {
        return view('admin.users.profile', ['user' => Auth::guard('admin')->user()]);
    }

    public function profilePost(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $user->id,
            'password_confirmation' => 'same:password',
        ]);

        $input = $request->all();

        if ($input['password'] !== null) {
            $input['remember_token'] = Str::random(32);
        } else {
            unset($input['password']);
        }

        $user->update($input);

        alert()->success(trans('myadmin.profile.successupdate'), 'Updated');
        return redirect()->route('admin.user.profile');
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditdatatable(Request $request)
    {

         /*$users = Admin::with('audits')->where('id', $id)->first();*/
       /*  $user = Admin::findOrFail($id);*/
        
        $audits = Audit::where('admin_id', $request->user_id)->orderBy('created_at','desc');

        if ($request->modal_id && !empty($request->modal_id)) {
            if($request->modal_id == 'Category' || $request->modal_id == 'Brand'){
                $model ='App\\' .$request->modal_id;
            }else{
                $model =$request->modal_id;
            }
            
           
            $audits->where('auditable_type', $model);
        }
        if ($request->event_type && !empty($request->event_type)) {
            
           
            $audits->where('event', $request->event_type);
        }

         return Datatables::of($audits)
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('F d, Y h:ia');
            })
            ->editColumn('old_values', function ($audit) {
                return view('admin.audit.data',['data' => $audit->old_values]);
            })
            ->editColumn('new_values', function ($audit) {
                return view('admin.audit.data',['data' => $audit->new_values]);
            })
         ->make(true);
            
        
    }


   

}
