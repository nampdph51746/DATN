@extends('layouts.admin.admin')

@section('content')

<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Chi tiết vé #{{ $ticket->id }}</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-light">
                            <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h5 class="fs-14 fw-medium text-dark">Thông tin vé</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="text-muted">ID Vé:</th>
                                                <td>{{ $ticket->id }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Trạng thái:</th>
                                                <td>
                                                    @php
                                                        $status = $ticket->status instanceof \App\Enums\TicketStatus ? $ticket->status->value : $ticket->status;
                                                    @endphp
                                                    @switch($status)
                                                        @case('valid')
                                                            <span class="badge bg-success">Chưa sử dụng</span>
                                                            @break
                                                        @case('used')
                                                            <span class="badge bg-primary">Đã sử dụng</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger">Đã hủy</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">Không xác định</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Ngày đặt:</th>
                                                <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Ngày cập nhật:</th>
                                                <td>{{ $ticket->updated_at ? $ticket->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h5 class="fs-14 fw-medium text-dark">Thông tin suất chiếu</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="text-muted">ID Suất chiếu:</th>
                                                <td>{{ $ticket->showtime_id ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Ghế:</th>
                                                <td>{{ $ticket->seat_id ?? '-' }}</td>
                                            </tr>
                                            @if($ticket->showtime)
                                            <tr>
                                                <th scope="row" class="text-muted">Thời gian chiếu:</th>
                                                <td>{{ $ticket->showtime->start_time ? $ticket->showtime->start_time->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">ID Đơn hàng:</th>
                                                <td>{{ $ticket->booking_id ?? '-' }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection