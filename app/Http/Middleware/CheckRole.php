<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        \Log::info('Middleware CheckRole invoked');

        if (!Auth::check()) {
            return redirect('login');
        }

        \Log::info('User roles check', ['user' => Auth::user(), 'roles' => $roles]);

        if (Auth::user()->hasAnyRole($roles)) {
            \Log::info('User hasAnyRole', [true]);
            return $next($request);
        }

        \Log::info('User hasAnyRole', [false]);
        return redirect('/');
    }
}




