<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesPermissions;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function allUsersView(Request $request) {
        // $user = Auth::user();
        $users = User::with('roles')->select('id', 'name', 'email', 'user_id', 'created_at', 'updated_at')
        ->orderBy('id')
        ->get();
        return view('admin.users.index', ['users' => $users]);
    }

    public function getAllowedRoles()
    {
        $allRoles = Role::where('is_user', true)->get()->toArray();
        $role = Auth::user()->role;

        if ($role->name === 'admin') {
            return $allRoles;
        } else {
            $permissions = Auth::user()->permissions();
            $filteredRoles = array_filter($allRoles, function ($role) use ($permissions) {
                // Check if any permission ends with ":add"
                return in_array($role['name'] . ':add', $permissions);
            });

            return $filteredRoles;
        }
    }

    public function addUserView() {
        $permitRoles = $this->getAllowedRoles();

        return view('admin.users.add', compact('permitRoles'));
    }

    public function store(Request $request)
    {
        // Define the rules for validation
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'role' => 'required|string|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $requiredPermission = $request->role . ':add';

            if($user->role->name != 'admin') {
                $requiredPermission = $request->role.":add";
                if (!in_array($requiredPermission, $user->permissions())) {
                    return redirect()->route('user.index')->with('duplicate', 'Unauthorized. You do not have permission to create a user with the specified role.');
                }
            }

            $validatedData = $validator->validated();
            $validatedData['user_id'] = $user->id;
            $validatedData['password'] = Hash::make($request->password); // Hash the password

            $newUser = User::create($validatedData);
            $role = Role::where('name', $request->role)->first();

            if ($role) {
                UserRole::create([
                    'user_id' => $newUser->id,
                    'role_id' => $role->id,
                    'createdBy' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                return redirect()->route('user.add')->withInput($request->all())->withErrors($validator)->with('duplicate', 'Invalid role encountered. Please try again');
            }

            DB::commit();

            return redirect()->route('user.index')->with('message', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.add')->withInput($request->all())->withErrors($validator)->with('duplicate', 'Failed to create a new user. User already exists.');
        }
    }

    public function editUserView($id) {
        $user = User::find($id);

        $permitRoles = $this->getAllowedRoles();
        if ($user) {
            return view('admin.users.edit', ['user' => $user, 'permitRoles' => $permitRoles]);
        } else {
            return view("admin.users.edit")->with('error', 'User not found');
        }
    }

    public function updateUser(Request $request, $id=null)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,' . $id,
            'role' => 'required|string|exists:roles,name',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }


        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return redirect()->back()->with('duplicate', 'Invalid session');
        }
        $loggedInUserRole = $loggedInUser->role->name;

        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('duplicate', 'User not found');
        }

        if($loggedInUserRole != 'admin') {
            return redirect()->back()->with('duplicate', 'You dont have access to update the user');
        }

        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            return redirect()->back()->with('duplicate', 'Invalid role');
        }

        $updateUser = false;

        if ($user->name != $request->name || $user->email != $request->email) {
            $user->name = $request->name;
            $user->email = $request->email;
            $updateUser = true;
        }

        if ($updateUser) {
            $user->save();
        }

        $updateUserRole = false;


        if ($user->roles->isEmpty() || $user->roles->last()->role_id != $role->id) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'createdBy' => $loggedInUser->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $updateUserRole = true;
        }

        if($updateUser || $updateUserRole) {
            return redirect()->back()->with('message', 'User updated successfully');
        } else {
            return redirect()->back()->with('no-change', 'No change occured');
        }

    }

    public function updateUserPassword(Request $request, $id=null)
    {
        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        $messages = [
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least :min characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('password-error', $validator->errors()->first('password'))->withInput();
        }

        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return redirect()->back()->with('password-error', 'Invalid session');
        }
        $loggedInUserRole = $loggedInUser->role->name;

        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('password-error', 'User not found');
        }

        if ($loggedInUserRole != 'admin' || $loggedInUser->id == $user->id) {
            $rules['current_password'] = 'required|string';
            $messages = [
                'current_password.required' => 'The current password is required.',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('password-error', $validator->errors()->first('current_password'))->withInput();
        }

        try {
            $user->update([
                'password' => Hash::make($request->password),
                'user_id' => $user->id,
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('password-message', 'Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('password-error', 'Failed to update the password. ' . $e->getMessage());
        }
    }
}
