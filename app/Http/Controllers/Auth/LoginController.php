<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:vendor')->except('logout');
        $this->middleware('guest:store')->except('logout');
    }

    public function showVendorLoginForm()
    {
        return view('auth.login', ['url' => 'vendor']);
    }

    public function vendorLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect('/vendor/dashboard');
        }
        alert()->error(trans('auth.failed'), 'Invalid!');
        return back()->withInput($request->only('email', 'remember'));
    }

    public function showStoreLoginForm()
    {
        return view('auth.login', ['url' => 'store']);
    }

    public function storeLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('store')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect('/store/dashboard');
        }
        alert()->error(trans('auth.failed'), 'Invalid!');
        return back()->withInput($request->only('email', 'remember'));
    }
}
