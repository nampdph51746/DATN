@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">

        <!-- Right Form Section -->
        <div class="col-xl-9 col-lg-8">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Upload -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ảnh đại diện</h4>
                    </div>
                    <div class="card-body">
                        <label for="avatar_url" class="form-label">Ảnh đại diện</label>
                        <input type="file" name="avatar_url" id="avatar_url" class="form-control @error('avatar_url') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Kích thước đề xuất: 1600 x 1200 (4:3). Định dạng: PNG, JPG, GIF.</small>
                        @error('avatar_url')
                            <div class="invalid-feedback">Vui lòng chọn ảnh đại diện hợp lệ (PNG, JPG, GIF, tối đa 2MB).</div>
                        @enderror
                    </div>
                </div>

                <!-- User Information -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin người dùng</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nhập họ và tên" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">Vui lòng nhập họ và tên hợp lệ.</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-lg-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Nhập địa chỉ email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ và chưa được sử dụng.</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-lg-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Nhập mật khẩu">
                                @error('password')
                                    <div class="invalid-feedback">Mật khẩu phải có ít nhất 8 ký tự.</div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="col-lg-6 mb-3">
                                <label for="phone_number" class="form-label">Số điện thoại</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                                    placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">Số điện thoại không hợp lệ.</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-lg-12 mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                    placeholder="Nhập địa chỉ" style="resize: none">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">Địa chỉ không hợp lệ.</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-lg-6 mb-3">
                                <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">Ngày sinh không hợp lệ.</div>
                                @enderror
                            </div>

                            <!-- Customer Rank -->
                            <div class="col-lg-6 mb-3">
                                <label for="customer_rank_id" class="form-label">Cấp bậc khách hàng</label>
                                <select name="customer_rank_id" id="customer_rank_id" class="form-control @error('customer_rank_id') is-invalid @enderror">
                                    <option value="">Chọn cấp bậc</option>
                                    @foreach ($customerRanks as $rank)
                                        <option value="{{ $rank->id }}" {{ old('customer_rank_id') == $rank->id ? 'selected' : '' }}>
                                            {{ $rank->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_rank_id')
                                    <div class="invalid-feedback">Vui lòng chọn cấp bậc khách hàng hợp lệ.</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-lg-6 mb-3">
                                <label for="role_id" class="form-label">Vai trò</label>
                                <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                    <option value="">Chọn vai trò</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">Vui lòng chọn vai trò hợp lệ.</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-lg-6 mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="">Chọn trạng thái</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">Vui lòng chọn trạng thái hợp lệ.</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                <div class="p-3 bg-light mb-3 rounded mt-3">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">Lưu</button>
                        </div>
                        <div class="col-lg-2">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
