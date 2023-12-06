<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UsersRolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function revoke(Request $request, $id)
    {
        if(isset($request->role_id)) {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $role = UserRole::where('user_id', $id)->latest()->first();
            if($request->role_id  == $role->role_id) {
                return response()->json(['error' => "Could not update the user's to the requested permission. As user is already possessing the $role->id role"], 400);
            }
            $newUserRole = UserRole::create([
                'user_id' => $id,
                'role_id' => $request->role_id,
                'user_id' => $request->user_id,
            ]);

            return response()->json(['success' => 'User role updated successfully', 'updated_user_role' => $newUserRole], 200);
        } else {
            return response()->json(['error' => 'Invalid input data'], 400);
        }
    }
}
