@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Chi tiết điểm thưởng</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted" style="width: 180px;">Khách hàng</th>
                            <td>
                                <span class="fw-semibold">{{ $point->user->name ?? 'N/A' }}</span>
                                <br>
                                <small class="text-muted">ID: {{ $point->user_id }}</small>
                                <br>
                                <small class="text-muted">Email: {{ $point->user->email ?? '-' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tổng điểm</th>
                            <td class="fw-bold text-primary fs-5">{{ $point->total_points }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Ngày hết hạn</th>
                            <td>{{ $point->points_expiry_date ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Ngày tạo</th>
                            <td>{{ $point->created_at ? $point->created_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Lịch sử cộng/trừ điểm</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Điểm thay đổi</th>
                                    <th>Lý do</th>
                                    <th>Mô tả</th>
                                    <th>Đơn hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($point->histories()->orderByDesc('created_at')->get() as $history)
                                    <tr>
                                        <td>{{ $history->created_at ? $history->created_at->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="{{ $history->points_change > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $history->points_change > 0 ? '+' : '' }}{{ $history->points_change }}
                                        </td>
                                        <td>{{ $history->reason_type ?? '-' }}</td>
                                        <td>{{ $history->description ?? '-' }}</td>
                                        <td>
                                            @if($history->booking_id)
                                                <a href="{{ route('admin.bookings.show', $history->booking_id) }}" class="text-decoration-underline">#{{ $history->booking_id }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Chưa có lịch sử điểm</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.points.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection