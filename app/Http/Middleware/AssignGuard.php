<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $redirectTo = '/login')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect($redirectTo);
        }
        Auth::shouldUse($guard);
        return $next($request);
        // $api = new ApiController();
        // $language = $api->setApiLanguage($request->header('language', 'en'));
        // if (empty($guard)) {
        //     if ($request->hasHeader('Authorization')) {
        //         if ($request->bearerToken() != env('API_TOKEN')) {
        //             return systemResponse(trans('api.token.invalid'));
        //         }
        //         return $next($request);
        //     } else {
        //         return systemResponse(trans('api.token.notexist'));
        //     }
        // } else {
        //     auth()->shouldUse($guard);
        // }
        // return $next($request);
    }

}
