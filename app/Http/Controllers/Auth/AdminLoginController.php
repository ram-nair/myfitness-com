<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Auth;
use App\Events\LoginEvent;
use App\Events\LogoutEvent;
class AdminLoginController extends Controller {

    public function __construct() {
        //defining our middleware for this controller
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    //function to show admin login form
    public function showLoginForm() {
        return view('auth.admin-login', ['url' => 'admin']);
    }

    //function to login admins
    public function login(Request $request) {
        //validate the form data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        //attempt to login the admins in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            //if successful redirect to admin dashboard
            Auth::guard('admin')->user()->update(['last_login' => Carbon::now()->toDateTimeString()]);
             $arr_caches = ['categories', 'products'];
         
        // want to raise ClearCache event
        event(new LoginEvent(Auth::guard('admin')->user()));
            return redirect(route('admin.dashboard'));
        }
        //if unsuccessfull redirect back to the login for with form data
        alert()->error(trans('auth.failed'), 'Invalid!');
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout() {
         event(new LogoutEvent(Auth::guard('admin')->user()));
        Auth::guard('admin')->logout();
        

        return redirect('/admin/login');
    }

}
