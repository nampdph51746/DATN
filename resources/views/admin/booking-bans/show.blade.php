@extends('admin.layouts.master')

@section('title', 'Chi tiết Ban Đặt Vé')

@section('content')
<div class="row">
    <!-- User Info & Ban Details -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin Ban</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>ID Ban:</strong></div>
                    <div class="col-sm-8">{{ $ban->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Người dùng:</strong></div>
                    <div class="col-sm-8">
                        {{ $ban->user->name }}<br>
                        <small class="text-muted">{{ $ban->user->email }}</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Số lần thất bại:</strong></div>
                    <div class="col-sm-8">
                        @if($ban->failed_attempts > 0)
                            <span class="badge bg-danger">{{ $ban->failed_attempts }}</span>
                        @else
                            <span class="badge bg-warning">Ban thủ công</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Ngày bắt đầu:</strong></div>
                    <div class="col-sm-8">{{ $ban->banned_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Ngày kết thúc:</strong></div>
                    <div class="col-sm-8">{{ $ban->banned_until->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Thời gian còn lại:</strong></div>
                    <div class="col-sm-8">
                        @if($ban->isActive())
                            @php
                                $diff = $ban->banned_until->diff(now());
                                $remainingTime = '';
                                if ($diff->d > 0) $remainingTime .= $diff->d . ' ngày ';
                                if ($diff->h > 0) $remainingTime .= $diff->h . ' giờ ';
                                if ($diff->i > 0) $remainingTime .= $diff->i . ' phút';
                            @endphp
                            <span class="text-danger">{{ $remainingTime ?: 'Dưới 1 phút' }}</span>
                        @else
                            <span class="text-success">Đã hết hạn</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Trạng thái:</strong></div>
                    <div class="col-sm-8">
                        @if($ban->isActive())
                            <span class="badge bg-danger">Đang hoạt động</span>
                        @else
                            <span class="badge bg-success">Đã hết hạn</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Lý do:</strong></div>
                    <div class="col-sm-8">{{ $ban->reason }}</div>
                </div>

                @if($ban->isActive())
                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.booking-bans.unban', $ban->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc muốn hủy ban này?')">
                            <i class="fa fa-unlock"></i> Hủy Ban
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thống kê Booking Attempts</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6"><strong>Tổng số lần thử:</strong></div>
                    <div class="col-sm-6">
                        <span class="badge bg-primary">{{ $stats['total_attempts'] }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6"><strong>Số lần thành công:</strong></div>
                    <div class="col-sm-6">
                        <span class="badge bg-success">{{ $stats['successful_attempts'] }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6"><strong>Số lần thất bại:</strong></div>
                    <div class="col-sm-6">
                        <span class="badge bg-danger">{{ $stats['failed_attempts'] }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6"><strong>Thất bại gần đây (24h):</strong></div>
                    <div class="col-sm-6">
                        <span class="badge bg-warning">{{ $stats['recent_failed_attempts'] }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6"><strong>Tỷ lệ thành công:</strong></div>
                    <div class="col-sm-6">
                        @php
                            $successRate = $stats['success_rate'];
                            $badgeClass = $successRate >= 80 ? 'bg-success' : ($successRate >= 50 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $successRate }}%</span>
                    </div>
                </div>

                @if($stats['recent_failed_attempts'] >= 2)
                <div class="alert alert-warning mt-3">
                    <i class="fa fa-exclamation-triangle"></i>
                    <strong>Cảnh báo:</strong> User này có {{ $stats['recent_failed_attempts'] }} lần thất bại trong 24h gần đây.
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h4 class="card-title">Thao tác nhanh</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.bookings.index', ['search' => $ban->user_id]) }}" class="btn btn-info mb-2">
                    <i class="fa fa-list"></i> Xem lịch sử đặt vé
                </a>
                <br>
                <a href="{{ route('admin.users.show', $ban->user_id) }}" class="btn btn-primary mb-2">
                    <i class="fa fa-user"></i> Xem thông tin user
                </a>
                <br>
                <a href="{{ route('admin.booking-bans.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
