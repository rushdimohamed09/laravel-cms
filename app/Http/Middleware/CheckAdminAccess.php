<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $param = null)
    {
        if (Auth::check()) {
            if(!isset(Auth::user()->role)) {
                return response()->view('admin.no-access');
            } elseif(Auth::user()->role->name !== 'admin') {
                $explodedParam = explode(':', $param);

                if (!isset($explodedParam[1])) {
                    return response()->view('admin.no-access');
                }

                $action = $explodedParam[1];
                $userRoles = Role::where('is_user', true)->get();

                $userPermissions = Auth::user()->permissions();
                $hasPermission = false;
                foreach ($userRoles as $role) {

                    if($role->name != 'user-own') {
                        if (in_array($role->name.":$action", $userPermissions)) {
                            $hasPermission = true;
                            break;
                        }
                    }
                }

                if (!$hasPermission) {
                    return response()->view('admin.no-access');
                }
            }
        }

        return $next($request);
    }
}
