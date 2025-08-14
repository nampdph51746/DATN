<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\StoreRoleRequest;
use App\Http\Requests\Admin\Roles\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view role')->only('index');
        $this->middleware('can:create role')->only(['create', 'store']);
        $this->middleware('can:edit role')->only(['edit', 'update']);
        $this->middleware('can:delete role')->only('destroy');
    }
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
        $permissions = \Spatie\Permission\Models\Permission::all();

        // Nhóm quyền theo tiền tố (group)
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $group = $parts[0] ?? 'Khác';
            $groupedPermissions[$group][] = $permission;
        }

        return view('admin.roles.create', compact('permissions', 'groupedPermissions'));
    }

    public function show($id)
    {
        $role = Role::with(['permissions'])->findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();

        // Mảng tên quyền để đánh dấu checkbox
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        // Nhóm quyền theo tiền tố (group)
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $group = $parts[0] ?? 'Khác';
            $groupedPermissions[$group][] = $permission;
        }

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'groupedPermissions'));
    }


    public function store(StoreRoleRequest $request)
    {

        $data = $request->validated();
        $data['guard_name'] = 'web';

        $role = Role::create($data);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findById($id); // dùng Spatie

        // Nếu là vai trò admin thì không cho sửa quyền
        if ($role->name === 'admin' && auth()->user()->hasRole('admin')) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $role->name = $request->name;
            $role->save();

            return redirect()->route('roles.index')->with('success', 'Không thể sửa quyền admin. Đã cập nhật tên.');
        }

        // Cập nhật tên + sync permission
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
        ]);

        $role->name = $request->name;
        $role->save();

        // Đồng bộ permission
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Cập nhật vai trò thành công.');
    }

    public function softDelete(Role $role, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa vai trò này vì vai trò này đang được sử dụng.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Vai trò được xóa thành công.');
    }


    public function deleted(Request $request)
    {
        $query = Role::onlyTrashed();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $roles = $query->orderByDesc('id')->paginate(10)->withQueryString();

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

        if (!$role->trashed()) {
            return redirect()->back()->with('info', 'Vai trò này không bị xóa.');
        }

        $role->restore();

        return redirect()->route('roles.deleted')->with('success', 'Khôi phục vai trò thành công.');
    }

    public function forceDelete($id)
    {
        $role = Role::withTrashed()->findOrFail($id);

        if (!$role->trashed()) {
            return redirect()->back()->with('info', 'Vai trò này chưa bị xóa.');
        }

        // Kiểm tra xem có user nào đang dùng role này không
        if ($role->users()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa vĩnh viễn vai trò này vì đang được sử dụng.');
        }

        $role->forceDelete();

        return redirect()->route('roles.deleted')->with('success', 'Xóa vĩnh viễn vai trò thành công.');
    }
}