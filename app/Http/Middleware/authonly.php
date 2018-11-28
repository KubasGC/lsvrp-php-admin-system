<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class authonly
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
		if (!Auth::check())
		{
			return redirect()->route("login")->with("toast-info", "Musisz się zalogować aby móc to zrobić.");
		}
        return $next($request);
    }
}
