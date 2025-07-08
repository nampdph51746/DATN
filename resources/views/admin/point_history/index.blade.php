@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Lịch sử cộng/trừ điểm</h4>
                        <form method="GET" action="{{ route('admin.point_history.index') }}" class="d-flex gap-2">
                            <input type="text" name="user_id" value="{{ request('user_id') }}"
                                class="form-control form-control-sm" placeholder="ID khách hàng">
                            <input type="text" name="booking_id" value="{{ request('booking_id') }}"
                                class="form-control form-control-sm" placeholder="ID đơn hàng">
                            <input type="text" name="reason_type" value="{{ request('reason_type') }}"
                                class="form-control form-control-sm" placeholder="Lý do">
                            <button class="btn btn-sm btn-light" type="submit">Lọc</button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Đơn hàng</th>
                                    <th>Điểm thay đổi</th>
                                    <th>Lý do</th>
                                    <th>Mô tả</th>
                                    <th>Thời gian</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($histories as $history)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $history->user->name ?? 'N/A' }}</span><br>
                                            <small class="text-muted">ID: {{ $history->user_id }}</small>
                                        </td>
                                        <td>
                                            @if ($history->booking_id)
                                                <a href="{{ route('admin.bookings.show', $history->booking_id) }}"
                                                    class="text-decoration-underline">#{{ $history->booking_id }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="{{ $history->points_change > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $history->points_change > 0 ? '+' : '' }}{{ $history->points_change }}
                                        </td>
                                        <td>
                                            @switch($history->reason_type)
                                                @case('earned')
                                                    <span class="badge bg-success">Cộng</span>
                                                @break

                                                @case('spent')
                                                    <span class="badge bg-warning text-dark">Tiêu</span>
                                                @break

                                                @case('expired')
                                                    <span class="badge bg-secondary">Hết hạn</span>
                                                @break

                                                @case('adjusted')
                                                    <span class="badge bg-info text-dark">Điều chỉnh</span>
                                                @break

                                                @default
                                                    {{ $history->reason_type }}
                                            @endswitch
                                        </td>
                                        <td>{{ $history->description ?? '-' }}</td>
                                        <td>{{ $history->created_at ? $history->created_at->format('d-m-Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('admin.point_history.show', $history->id) }}"
                                                    class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken" class="fs-18"></iconify-icon>
                                                </a>

                                                <form action="{{ route('admin.point_history.toggle', $history->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn chuyển trạng thái điểm của đơn hàng này?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning">↔</button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-light">
                            {{ $histories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection