@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Thùng Rác Loại Ghế</h4>
                        <form class="app-search d-none d-md-block ms-2">
                            <div class="position-relative">
                                <input type="search" class="form-control form-control-sm ps-5 pe-3 rounded-2" placeholder="Tìm kiếm loại ghế trong thùng rác" autocomplete="off" value="">
                                <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>
                        <a href="{{ route('seat-type.index') }}" class="btn btn-sm btn-primary">
                            Quay Lại Danh Sách
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th>Tên</th>
                                        <th>Hệ Số Giá</th>
                                        <th>Mã Màu</th>
                                        <th>Mô Tả</th>
                                        <th>Ngày Xóa</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($seatTypes as $seatType)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck{{ $seatType->id }}">
                                                    <label class="form-check-label" for="customCheck{{ $seatType->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded avatar-md d-flex align-items-center justify-content-center"
                                                        style="background-color: {{ $seatType->color_code }};">
                                                        <span class="text-white">{{ substr($seatType->name, 0, 1) }}</span>
                                                    </div>
                                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $seatType->name }}</p>
                                                </div>
                                            </td>
                                            <td>{{ number_format($seatType->price_modifier, 2) }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $seatType->color_code }};">
                                                    {{ $seatType->color_code }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($seatType->description, 50) }}</td>
                                            <td>{{ $seatType->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if ($seatType->trashed())
                                                        <form action="{{ route('seat-type.restore', $seatType->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                onclick="return confirm('Bạn có chắc muốn khôi phục loại ghế này?')">
                                                                <iconify-icon icon="solar:arrow-up-linear" class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('seat-type.force-delete', $seatType->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn loại ghế này?')">
                                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có loại ghế nào trong thùng rác.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $seatTypes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection