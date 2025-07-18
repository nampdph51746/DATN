<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Enums\UserStatus;
use App\Models\CustomerRank;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\Mailer\Test\Constraint\EmailCount;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles', 'customerRank')->orderBy('id', 'desc');

        // 🔍 Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->input('role'));
            });
        }

        // Filter by Rank
        if ($request->filled('rank')) {
            $query->where('customer_rank_id', $request->input('rank'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(10)->withQueryString();

        // For dropdown filters
        $roles = Role::all();
        $ranks = CustomerRank::all();

        return view('admin.users.list', compact('users', 'roles', 'ranks'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $roles = Role::all();

        $customerRanks = CustomerRank::all();

        $statuses = UserStatus::cases();
        return view('admin.users.create', compact('roles', 'customerRanks', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar_url')) {
            $data['avatar_url'] = $request->file('avatar_url')->store('avatars', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($request->filled('role')) {
            // Đảm bảo chỉ gán role hợp lệ
            $role = Role::where('name', $request->input('role'))->first();

            if ($role) {
                $user->assignRole($role);
            }
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('role', 'customerRank')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); // Spatie Role
        $customerRanks = CustomerRank::all();
        $statuses = UserStatus::cases();

        $statuses = UserStatus::cases();
        return view('admin.users.edit', [
        'users' => $user,
        'roles' => $roles,
        'customerRanks' => $customerRanks,
        'statuses' => $statuses,
        'selectedRole' => $user->getRoleNames()->first(), // ← Thêm dòng này
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest  $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validated();

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
