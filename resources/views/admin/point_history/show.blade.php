@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Chi tiết lịch sử điểm</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted" style="width: 160px;">Khách hàng</th>
                            <td>
                                <span class="fw-semibold">{{ $history->user->name ?? 'N/A' }}</span>
                                <br>
                                <small class="text-muted">ID: {{ $history->user_id }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Đơn hàng</th>
                            <td>
                                @if($history->booking_id)
                                    <a href="{{ route('admin.bookings.show', $history->booking_id) }}" class="text-decoration-underline">#{{ $history->booking_id }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Điểm thay đổi</th>
                            <td class="{{ $history->points_change > 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ $history->points_change > 0 ? '+' : '' }}{{ $history->points_change }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Lý do</th>
                            <td>
                                @switch($history->reason_type)
                                    @case('earned') <span class="badge bg-success">Cộng</span> @break
                                    @case('spent') <span class="badge bg-warning text-dark">Tiêu</span> @break
                                    @case('expired') <span class="badge bg-secondary">Hết hạn</span> @break
                                    @case('adjusted') <span class="badge bg-info text-dark">Điều chỉnh</span> @break
                                    @default {{ $history->reason_type }}
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Mô tả</th>
                            <td>{{ $history->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Thời gian</th>
                            <td>{{ $history->created_at ? $history->created_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.point_history.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection