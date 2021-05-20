<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        // if ($request->ajax() && !Auth::guard('admin')->check()) {
        //     return response()->json(["status" => "expired"], 500);
        // }
        // if (Auth::guard('admin')->check()) {
        // }
        return $next($request);

        // return redirect('/admin')->with('growl', ['Your don\'t have permission to acces this page.','danger']);
    }

}
