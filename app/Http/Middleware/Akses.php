<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Akses
{
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if (in_array($user->role, explode('|', $roles))) {
            return $next($request);
        }

        return $this->redirectToRolePage($user);
    }

    protected function redirectToRolePage($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect('/logout');
        }
    }
}
