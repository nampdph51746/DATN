<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\StoreRoleRequest;
use App\Http\Requests\Admin\Roles\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {

        $query = Role::query();

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('id', $request->keyword);
            });
        }

        if ($request->filled('created_order')) {
            $query->orderBy('created_at', $request->created_order);
        } else {
            $query->orderBy('created_at', 'desc'); // default: newest first
        }

        $roles = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.roles.list', compact('roles'));
    }
    public function create()
    {

        return view('admin.roles.create');
    }

    public function show(Role $role)
    {

        $role = Role::find($role->id);
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {

        $role = Role::find($role->id);
        return view('admin.roles.edit', compact('role'));
    }

    public function store(StoreRoleRequest $request)
    {

        $data = $request->validated();

        Role::create($data);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $request->validated();


        $role->update($data);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function softDelete(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa vai trò này vì đang được sử dụng bởi người dùng.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }


    public function deleted(Request $request)
    {
        $query = Role::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $roles = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.roles.deleted', compact('roles'));
    }
    public function deletedShow($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        return view('admin.roles.deleted-show', compact('role'));
    }

    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();
        return redirect()->route('roles.deleted')->with('success', 'Role restored successfully.');
    }

    public function forceDelete($id)
    {
        $role = Role::withTrashed()->findOrFail($id);

        if ($role->users()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa vĩnh viễn vai trò này vì đang được sử dụng.');
        }

        $role->forceDelete();
        return redirect()->route('roles.deleted')->with('success', 'Role deleted permanently.');
    }
}
