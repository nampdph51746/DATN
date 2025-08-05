<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\CustomerRank;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Chuyển hướng người dùng đến trang đăng nhập Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Xử lý callback từ Google
    public function handleGoogleCallback()
    {
        try {
            // Lấy thông tin người dùng từ Google
            $googleUser = Socialite::driver('google')->user();

            $userRank = CustomerRank::firstOrCreate(['name' => 'Đồng']);

            // Tìm hoặc tạo người dùng trong cơ sở dữ liệu
            if (empty($googleUser->email)) {
                return redirect('/login')->with('error', 'Không lấy được email từ Google.');
            }
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt('password_dummy'),
                    'customer_rank_id' => $userRank->id,
                ]
            );

            // Đăng nhập người dùng (bỏ true để tắt Remember Me)
            Auth::login($user);

            if (!$user->hasRole('user')) {
                $user->assignRole('user');
            }
            // Chuyển hướng sau khi đăng nhập thành công
            return redirect()->route('client.home');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập thất bại, vui lòng thử lại.');
        }
    }
}