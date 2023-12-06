<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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

    public function add(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:permissions,name',
                'description' => 'nullable|string',
                'user_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $permission = Permission::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $request->user_id,
            ]);

            return response()->json(['message' => 'Permission created successfully', 'data' => $permission], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create permission. ' . $e->getMessage()], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:permissions,name,' . $id,
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json(['error' => 'Permission not found'], 404);
            }

            $permission->name = $request->input('name');
            $permission->description = $request->input('description');
            $permission->save();

            return response()->json(['message' => 'Permission updated successfully', 'data' => $permission], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update permission. ' . $e->getMessage()], 400);
        }
    }

    public function getPermissions(Request $request)
    {
        $query = Permission::query();

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
                $permissions = Permission::all();
            } else {
                $permissions = $query->skip($offset)->take($limit)->get();
            }
        } else {
            $permissions = $query->get();
        }

        $permissionsCount = $filteredQuery->count();

        return response()->json([
            'data' => $permissions,
            'count' => $permissionsCount,
        ]);
    }

    public function delete($id){
        try {
            $permission = Permission::find($id);

            if ($permission) {
                $permission->delete();
                return response()->json(['message' => 'Permission deleted successfully']);
            } else {
                return response()->json(['error' => 'Permission not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete permission. ' . $e->getMessage()], 400);
        }
    }

    public function revoke($id)
    {
        try {
            $permission = Permission::withTrashed()->find($id);

            if (!$permission) {
                return response()->json(['error' => 'Permission not found'], 404);
            }

            if ($permission->trashed()) {
                $permission->restore();
                return response()->json(['message' => 'Successfully revoked the deleted permission.'], 200);
            } else {
                return response()->json(['message' => 'The permission is still active and not deleted.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to revoke Permission. ' . $e->getMessage()], 400);
        }
    }
}
