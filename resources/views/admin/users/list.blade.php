@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl py-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i class="fas fa-users me-2"></i>Danh sách khách hàng</h4>
                        <form method="GET" action="{{ route('users.index') }}" class="d-flex align-items-center gap-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc email" value="{{ request('search') }}">
                            </div>
                            <select name="role" class="form-select">
                                <option value="">Tất cả vai trò</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="rank" class="form-select">
                                <option value="">Tất cả cấp bậc</option>
                                @foreach($ranks as $rank)
                                    <option value="{{ $rank->id }}" {{ request('rank') == $rank->id ? 'selected' : '' }}>
                                        {{ $rank->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Tạm khóa</option>
                            </select>
                            <button type="submit" class="btn btn-light"><i class="fas fa-filter me-2"></i>Lọc</button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-light"><i class="fas fa-redo me-2"></i>Đặt lại</a>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên người dùng</th>
                                        <th>Mã người dùng</th>
                                        <th>Cấp bậc</th>
                                        <th>Vai trò</th>
                                        <th>Email</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày sinh</th>
                                        <th>Ngày tạo</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck{{ $user->id }}">
                                                    <label class="form-check-label" for="customCheck{{ $user->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <img src="{{ Storage::url($user->avatar_url) }}" class="avatar-sm rounded-circle me-2" alt="Ảnh đại diện">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ optional($user->customerRank)->name ?? 'N/A' }}</td>
                                            <td>{{ $user->getRoleNames()->first() ?? 'N/A' }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->address ?? 'N/A' }}</td>
                                            <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>
                                                @if($user->status->value === 'active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @elseif($user->status->value === 'inactive')
                                                    <span class="badge bg-secondary">Không hoạt động</span>
                                                @elseif($user->status->value === 'suspended')
                                                    <span class="badge bg-warning">Tạm khóa</span>
                                                @else
                                                    <span class="badge bg-danger">Không xác định</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top bg-light">
                        <nav aria-label="Phân trang">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Hiển thị {{ $users->firstItem() }} đến {{ $users->lastItem() }} của {{ $users->total() }} bản ghi
                                </div>
                                {{ $users->links('pagination::bootstrap-5') }}
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>
    @endpush
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .card-header {
            border-radius: 10px 10px 0 0;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .avatar-sm {
            width: 40px;
            height: 40px;
        }
        .form-select, .form-control {
            border-radius: 8px;
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.5em 1em;
        }
    </style>
@endpush