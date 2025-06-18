@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Danh sách khuyến mãi đã xóa mềm</h4>
                    <a href="{{ route('promotions.index') }}" class="btn btn-sm btn-primary">Quay lại danh sách</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover table-centered">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>ID</th>
                                <th>Tên khuyến mãi</th>
                                <th>Mã KM</th>
                                <th>Loại giảm giá</th>
                                <th>Giá trị giảm</th>
                                <th>Ngày xóa</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promotions as $promotion)
                            <tr>
                                <td>{{ $promotion->id }}</td>
                                <td>{{ $promotion->name }}</td>
                                <td>{{ $promotion->code }}</td>
                                <td>{{ $promotion->discount_type }}</td>
                                <td>{{ number_format($promotion->discount_value, 2) }}</td>
                                <td>{{ $promotion->deleted_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('promotions.restore', $promotion->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-soft-success btn-sm" onclick="return confirm('Khôi phục?')">
                                                <iconify-icon icon="solar:refresh-circle-broken" class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                        <form action="{{ route('promotions.forceDelete', $promotion->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Xóa vĩnh viễn?')">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        {{ $promotions->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection