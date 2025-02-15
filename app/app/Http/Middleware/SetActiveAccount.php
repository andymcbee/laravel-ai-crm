<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
class SetActiveAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $activeAccount = session('active_account');

            if(!$activeAccount){
                //default to first account the user belongs to
                $activeAccount = Auth::user()->accounts()->first();
                session(['active_account' => $activeAccount]);
            }

            // Share active acc globally via Inertia
            Inertia::share([
                'active_account' => $activeAccount,
            ]);
        }
        return $next($request);
    }
}
