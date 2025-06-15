@extends('layouts.admin.admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Danh sách tất cả khách hàng</h4>
                        </div>
                        <form method="GET" action="{{ route('users.index') }}" class="">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm kiếm theo tên hoặc email" value="{{ request('search') }}">
                                </div>

                                <div class="col-md-2">
                                    <select name="role" class="form-select">
                                        <option value="">Tất cả vai trò</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="rank" class="form-select">
                                        <option value="">Tất cả cấp bậc</option>
                                        @foreach($ranks as $rank)
                                            <option value="{{ $rank->id }}" {{ request('rank') == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            Không hoạt động</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>
                                            Tạm khóa</option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">Đặt lại</a>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>STT</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên người dùng</th>
                                        <th>Cấp bậc khách hàng</th>
                                        <th>Email</th>
                                        <th>Ngày tạo</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <img src="{{ Storage::url($user->avatar_url) }}"
                                                    class="avatar-sm rounded-circle me-2" alt="Ảnh đại diện">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->customerRank->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
    @php
        // Xử lý giá trị trạng thái nếu là object (enum) hoặc string
        $statusValue = is_object($user->status) ? $user->status->value : $user->status;

        // Màu sắc cho từng trạng thái
        $statusColors = [
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'danger',
        ];
    @endphp

    <span class="badge bg-{{ $statusColors[$statusValue] ?? 'secondary' }}">
        {{ ucfirst($statusValue) }}
    </span>
</td>

                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-light btn-sm"
                                                        title="Xem chi tiết">
                                                        <iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>

                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                        <iconify-icon icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    {{-- <form action="{{ route('users.softDelete', $user->id) }}" method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn vô hiệu hóa người dùng này không?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-soft-danger btn-sm"
                                                            title="Vô hiệu hóa">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>

                                                    </form> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                    <div class="card-footer border-top">
                        <nav aria-label="Phân trang">
                            <ul class="pagination justify-content-end mb-0">
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    {{ $users->links('pagination::bootstrap-4') }}
                                </div>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection