<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionsHelper
{
    public static function hasPermissionOrIsAdmin($permissions)
    {
        $user = Auth::user();

        if ($user && $user->role && $user->role->name == 'admin') {
            return true;
        }

        return $user && $user->permissions() && count(array_intersect($permissions, $user->permissions())) > 0;
    }
}
