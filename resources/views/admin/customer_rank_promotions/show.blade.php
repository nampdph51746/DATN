@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Chi tiết khuyến mãi theo hạng khách hàng #{{ $item->customer_rank_id }}_{{ $item->promotion_id }}</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customer_rank_promotions.index') }}" class="btn btn-sm btn-outline-light">
                            <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h5 class="fs-14 fw-medium text-dark">Thông tin khuyến mãi theo hạng khách hàng</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="text-muted">ID Hạng khách hàng:</th>
                                                <td>{{ $item->customer_rank_id }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">ID Khuyến mãi:</th>
                                                <td>{{ $item->promotion_id }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Mô tả:</th>
                                                <td>{{ $item->description ?? '-' }}</td>
                                            </tr>
                                            {{-- <tr>
                                                <th scope="row" class="text-muted">Ngày tạo:</th>
                                                <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Ngày cập nhật:</th>
                                                <td>{{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr> --}}
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