<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveAccountIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $activeAccount = session('active_account');

        $user = $request->user();

        // Check that the active account is set and belongs to the authenticated user
        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        return $next($request);
    }
}
