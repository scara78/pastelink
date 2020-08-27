<?php

namespace App\Http\Middleware;

use App\Link;
use Closure;
use Illuminate\Support\Facades\Hash;

class AuthRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $password = Link::select('password')->findOrFail(session('id'))->password;
        // check password
        if(!Hash::check(session('pass'), $password)){

            session()->flash('error', 'The password is wrong!');
            // redirect to previous page
            return redirect()->back();
        }
        return $next($request);
    }
}
