@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Chi tiết khuyến mãi #{{ $promotion->id }}</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('promotions.index') }}" class="btn btn-sm btn-outline-light">
                            <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h5 class="fs-14 fw-medium text-dark">Thông tin khuyến mãi</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="text-muted">ID Khuyến mãi:</th>
                                                <td>{{ $promotion->id }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Mã KM:</th>
                                                <td>{{ $promotion->code }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Trạng thái:</th>
                                                <td>
                                                    <span class="badge {{ $promotion->status == 'active' ? 'bg-success' : ($promotion->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ $promotion->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Mô tả:</th>
                                                <td>{{ $promotion->description ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Ngày tạo:</th>
                                                <td>{{ $promotion->created_at ? $promotion->created_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="text-muted">Ngày cập nhật:</th>
                                                <td>{{ $promotion->updated_at ? $promotion->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
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