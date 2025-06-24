<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CustomerRank;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
                $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'numeric', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'date_of_birth' => ['required', 'date', 'before:today'],
        ], [
            'required' => ':attribute là bắt buộc.',
            'email' => ':attribute phải là email hợp lệ.',
            'unique' => ':attribute đã tồn tại.',
            'numeric' => ':attribute phải là số.',
            'confirmed' => ':attribute không khớp.',
            'before' => ':attribute phải trước hôm nay.',
        ], [
            'name' => 'Họ và tên',
            'email' => 'Email',
            'phone_number' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'date_of_birth' => 'Ngày sinh',
        ]);

        $userRole = Role::where('name', 'user')->first();
        $userRank = CustomerRank::where('name', 'Bạc')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'role_id' => $userRole->id,
            'date_of_birth' => $request->date_of_birth,
            'status' => \App\Enums\UserStatus::Active,
            'customer_rank_id' => $userRank->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('client.home', absolute: false));
    }
}
