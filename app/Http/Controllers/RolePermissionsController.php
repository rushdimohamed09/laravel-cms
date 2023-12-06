<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionsController extends Controller
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
        $validator = Validator::make($request->all(), [
            'add' => 'array',
            'remove' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input data'], 400);
        }

        // Check if the role with the given $id exists
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Initialize arrays to track removed and added permissions
        $removedPermissions = [];
        $addedPermissions = [];

        if ($request->filled('remove')) {
            $removeIds = $request->input('remove');
            $removedPermissions = Permission::whereIn('id', $removeIds)->pluck('name')->toArray();
            RolesPermissions::where('role_id', $id)
                ->whereIn('permission_id', $removeIds)
                ->delete();
        }

        if ($request->filled('add')) {
            $addIds = $request->input('add');

            $validPermissionIds = Permission::whereIn('id', $addIds)->pluck('id')->toArray();

            $validAddIds = array_intersect($addIds, $validPermissionIds);

            $rolePermissions = [];

            foreach ($validAddIds as $permissionId) {
                $rolePermissions[] = [
                    'role_id' => $id,
                    'permission_id' => $permissionId,
                    'user_id' => $request->user_id,
                ];

                $addedPermissions[] = Permission::find($permissionId)->name;
            }

            RolesPermissions::insert($rolePermissions);
        }

        $response = [
            'message' => 'Permissions updated successfully',
            'removed_permissions' => $removedPermissions,
            'added_permissions' => $addedPermissions,
        ];

        return response()->json($response);
    }
}
