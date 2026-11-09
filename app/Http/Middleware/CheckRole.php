<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if authenticated
        if (!$request->session()->has('id')) {
            return redirect('/Login');
        }

        // 2. Fetch current user role from DB for real-time security
        $userId = $request->session()->get('id');
        $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            $request->session()->invalidate();
            return redirect('/Login');
        }

        $role = strtolower($user->role ?? '');
        $path = $request->path();

        // 3. Enforce Role restrictions
        if ($role === 'manager') {
            // Manager has access to all pages
            return $next($request);
        }

        if ($role === 'assistant') {
            // Assistant can access dashboard, POS, products, invoices, but CANNOT access cashier accounts management
            $restrictedPaths = ['user', 'adduser', 'updateuser', 'deleteuser'];
            foreach ($restrictedPaths as $restricted) {
                if (stripos($path, $restricted) !== false) {
                    return redirect('/')->with('error', 'Unauthorized access: Assistants are not permitted to manage cashier accounts.');
                }
            }
            return $next($request);
        }

        // Default: Staff / Cashier / other roles
        // Staff can ONLY access the POS page and POS checkout endpoints
        $allowedStaffPaths = ['pos', 'logout'];
        $isAllowed = false;
        foreach ($allowedStaffPaths as $allowed) {
            if (stripos($path, $allowed) !== false) {
                $isAllowed = true;
                break;
            }
        }

        if ($isAllowed) {
            return $next($request);
        }

        // Redirect unauthorized staff access to POS Terminal
        return redirect('/Pos')->with('error', 'Unauthorized access: Staff members are restricted to the POS Terminal.');
    }
}
