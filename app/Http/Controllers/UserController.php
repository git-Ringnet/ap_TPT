<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $title = 'Nhân viên';
        $groups = Groups::where('group_type_id', 4)->get();
        return view('setup.users.index', compact('users', 'title', 'groups'));
    }

    public function create()
    {
        $title = 'Tạo mới hàng hoá';
        $groups = Groups::where('group_type_id', 4)->get();
        $roles = Role::all();
        return view('setup.users.create', compact('title', 'groups', 'roles'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'group_id' => 'nullable|integer',
            'employee_code' => 'nullable|string',
            'role' => 'nullable|integer|exists:roles,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'group_id' => $validated['group_id'],
            'employee_code' => $validated['employee_code'],
            'role' => $validated['role'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
        ]);

        if (!empty($validated['role'])) {
            $role = Role::findById($validated['role']); // Find role by ID
            if ($role) {
                $user->assignRole($role->name); // Assign role by name
            }
        }

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $title = 'Chỉnh sửa nhân viên';
        $groups = Groups::where('group_type_id', 4)->get();
        $roles = Role::all();
        // Show a form to edit the user
        return view('setup.users.edit', compact('user', 'title', 'groups', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'group_id' => 'nullable|integer',
            'employee_code' => 'nullable|string',
            'role' => 'nullable|integer|exists:roles,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Update the user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
            'group_id' => $validated['group_id'] ?? $user->group_id,
            'employee_code' => $validated['employee_code'],
            'address' => $validated['address'],
            'role' => $validated['role'],
            'phone' => $validated['phone'],
        ]);

        // Update the role if provided
        if (!empty($validated['role'])) {
            $role = Role::findById($validated['role']); // Find the role by ID
            if ($role) {
                $user->syncRoles([$role->name]); // Sync roles (ensures only one role)
            }
        } else {
            // If no role provided, remove all roles
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Delete the user
        $user->delete();
        return redirect()->route('users.index');
    }
}
