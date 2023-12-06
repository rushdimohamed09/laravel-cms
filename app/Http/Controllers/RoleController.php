<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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

    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name',
                'user_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Create the role
            $role = Role::create([
                'name' => $request->name,
                'user_id' => $request->user_id,
            ]);

            // Create permissions if defined in the request
            $createdPermissions = [];
            $failedPermissions = [];

            if (isset($request->permissions) && is_array($request->permissions) && count($request->permissions) > 0) {
                foreach ($request->permissions as $permissionName) {
                    try {
                        $permission = Permission::create([
                            'name' => $request->name.":$permissionName",
                            'user_id' => $request->user_id,
                        ]);
                        $createdPermissions[] = $permission->name;
                    } catch (\Exception $e) {
                        $failedPermissions[] = $permissionName;
                    }
                }
            }

            $response = [
                'message' => 'Role created successfully',
                'data' => $role,
            ];

            if (!empty($createdPermissions)) {
                $response['created_permissions'] = $createdPermissions;
            }

            if (!empty($failedPermissions)) {
                $response['failed_permissions'] = $failedPermissions;
            }

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create role. ' . $e->getMessage()], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|unique:roles,name,' . $id,
                'is_user' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $role = Role::find($id);

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            if ($request->filled('name')) {
                $role->name = $request->input('name');
            }

            if ($request->filled('is_user')) {
                $role->is_user = $request->input('is_user');
            }
            $role->save();

            return response()->json(['message' => 'Role updated successfully', 'data' => $role], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update role. ' . $e->getMessage()], 400);
        }
    }

    public function getRoles(Request $request)
    {
        $query = Role::query();

        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $filteredQuery = clone $query;

        if (!$request->filled('id') && !$request->filled('name')) {
            $limit = $request->input('limit', 10); // Default limit is 10 if not provided
            $offset = $request->input('offset', 0); // Default offset is 0 if not provided

            if ($request->input('admin') == 'true') {
                $roles = Role::all();
            } else {
                $roles = $query->skip($offset)->take($limit)->get();
            }
        } else {
            $roles = $query->get();
        }

        $roleCount = $filteredQuery->count();

        $rolesWithPermissions = [];
        foreach ($roles as $role) {
            $permissions = RolesPermissions::where('role_id', $role->id)
                ->join('permissions', 'roles_permissions.permission_id', '=', 'permissions.id')
                ->pluck('permissions.name')->toArray();

            $roleWithPermissions = $role->toArray();
            $roleWithPermissions['permissions'] = $permissions;
            $rolesWithPermissions[] = $roleWithPermissions;
        }

        return response()->json([
            'data' => $rolesWithPermissions,
            'count' => $roleCount,
        ]);
    }

    public function getAllRoles(Request $request) {
        $roles = Role::orderBy('name')->get();

        $response = [];
        foreach ($roles as $role) {
            $permissions = Permission::where('name', 'like', '%' . $role->name . '%')->get();
            $response["data"][] = ['id' =>$role->id, 'name' =>$role->name, 'permissions' => $permissions];
        }

        return response()->json($response, 200);
    }

    public function getRoleDetailsById(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Invalid role details'], 404);
        }

        $permissions = RolesPermissions::where('role_id', $role->id)
            ->join('permissions', 'roles_permissions.permission_id', '=', 'permissions.id')
            ->pluck('permissions.name')->toArray();
        $permissionsIds = RolesPermissions::where('role_id', $role->id)
            ->join('permissions', 'roles_permissions.permission_id', '=', 'permissions.id')
            ->pluck('permissions.id')->toArray();

        $role['permissions'] = $permissions;
        $role['permission_ids'] = $permissionsIds;

        return response()->json(['data' => $role]);
    }

    public function delete($id){
        try {
            $role = Role::find($id);

            if ($role) {
                $role->delete();
                return response()->json(['message' => 'Role deleted successfully']);
            } else {
                return response()->json(['error' => 'Role not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete role. ' . $e->getMessage()], 400);
        }
    }

    public function revoke($id)
    {
        try {
            $role = Role::withTrashed()->find($id);

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            if ($role->trashed()) {
                $role->restore();
                return response()->json(['message' => 'Successfully revoked the deleted role.'], 200);
            } else {
                return response()->json(['message' => 'The role is still active and not deleted.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to revoke role. ' . $e->getMessage()], 400);
        }
    }
}
