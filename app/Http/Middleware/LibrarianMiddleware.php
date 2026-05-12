<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibrarianMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('librarian')->check()) {
            return redirect('/librarian/login');
        }
        
        return $next($request);
    }
}