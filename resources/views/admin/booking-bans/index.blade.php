@extends('admin.layouts.master')

@section('title', 'Quản lý Ban Đặt Vé')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Quản lý Ban Đặt Vé</h4>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createBanModal">
                    <i class="fa fa-ban"></i> Tạo Ban Thủ Công
                </button>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.booking-bans.index') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc email..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.booking-bans.index') }}">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Bans Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Số lần thất bại</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Lý do</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bans as $ban)
                            <tr>
                                <td>{{ $ban->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $ban->user->name }}</strong><br>
                                        <small class="text-muted">{{ $ban->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($ban->failed_attempts > 0)
                                        <span class="badge bg-danger">{{ $ban->failed_attempts }}</span>
                                    @else
                                        <span class="badge bg-warning">Thủ công</span>
                                    @endif
                                </td>
                                <td>{{ $ban->banned_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $ban->banned_until->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($ban->isActive())
                                        <span class="badge bg-danger">Đang ban</span>
                                    @else
                                        <span class="badge bg-success">Đã hết hạn</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($ban->reason, 50) }}</small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.booking-bans.show', $ban->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if($ban->isActive())
                                        <form method="POST" action="{{ route('admin.booking-bans.unban', $ban->id) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc muốn hủy ban này?')">
                                                <i class="fa fa-unlock"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $bans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Ban Modal -->
<div class="modal fade" id="createBanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.booking-bans.create') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tạo Ban Thủ Công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn người dùng</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Chọn user --</option>
                        </select>
                        <small class="text-muted">Gõ để tìm kiếm user</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số ngày ban</label>
                        <input type="number" name="days" class="form-control" min="1" max="365" value="7" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lý do</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Nhập lý do ban user..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Tạo Ban</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize select2 for user selection
    $('select[name="user_id"]').select2({
        ajax: {
            url: '{{ route("admin.booking-bans.search-users") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(function(user) {
                        return {
                            id: user.id,
                            text: user.name + ' (' + user.email + ')'
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        placeholder: 'Gõ tên hoặc email để tìm kiếm...'
    });
});
</script>
@endpush
