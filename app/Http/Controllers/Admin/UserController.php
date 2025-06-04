<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\CustomerRank;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mailer\Test\Constraint\EmailCount;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::with('role', 'customerRank')->orderBy('id', 'desc')->paginate(10);
        

        return view('admin.users.list', compact('users'));
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
    public function store(Request $request)
    {
        $data = $request->except('_token');

        if($request->hasFile('avatar_url')){
            $data['avatar_url'] = $request->file('avatar_url')->store('avatars', 'public');
        }

        User::create($data);


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
    public function edit(string $id)
    {
        $users = User::with('role', 'customerRank')->findOrFail($id);

        $roles = Role::all();

        $customerRanks = CustomerRank::all();

        $statuses = UserStatus::cases();
        return view('admin.users.edit', compact('roles', 'customerRanks', 'statuses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $request->except('_token', '_method');
        if($request->hasFile('avatar_url')){
           if($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)){
               Storage::disk('public')->delete($user->avatar_url);
           }
           $data['avatar_url'] = $request->file('avatar_url')->store('avatars', 'public');
        }



        if(empty($data['password'])){
            unset($data['password']);
        } else{
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    public function softDelete(User $user){
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deactivated successfully.');
    }
    public function deleted(Request $request){
        $deletedUsers = User::onlyTrashed()->paginate(10);
        return view('admin.users.deleted', compact('deletedUsers'));
    }
    public function deletedShow($id){
        $user = User::withTrashed()->findOrFail($id);
        return view('admin.users.deleted-show', compact('user'));
    }

    public function restore($id){
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.deleted')->with('success', 'User restored successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
