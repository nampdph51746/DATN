@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl px-4 py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="detail-header rounded-5 p-5 mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-trash3 me-3"></i>
                            Khuyến mãi đã xóa mềm
                        </h1>
                        <p class="lead text-white-50 mb-0">Danh sách các khuyến mãi đã bị xóa mềm khỏi hệ thống
                        </p>
                    </div>
                    <div class="header-actions d-flex gap-3">
                        <a href="{{ route('admin.promotions.index') }}"
                            class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Table Card -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-table text-danger"></i>
                        Danh sách khuyến mãi đã xóa mềm
                    </h5>
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
                                        <form action="{{ route('admin.promotions.restore', $promotion->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-soft-success btn-sm"
                                                onclick="return confirm('Khôi phục?')">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.promotions.forceDelete', $promotion->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm"
                                                onclick="return confirm('Xóa vĩnh viễn?')">
                                                <i class="bi bi-trash3"></i>
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

<style>
:root {
    --purple-primary: #8b5cf6;
    --purple-dark: #7c3aed;
}
.detail-header {
    background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-dark) 100%);
    position: relative;
    overflow: hidden;
}
.detail-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}
.card {
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(139,92,246,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(139,92,246,0.15);
}
.card-header {
    border-radius: 1.5rem 1.5rem 0 0;
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}
@media (max-width: 768px) {
    .detail-header { padding: 2rem !important; }
    .header-content h1 { font-size: 1.75rem; }
    .header-actions { flex-direction: column; width: 100%; gap: 0.75rem; }
    .card-header { padding: 1rem 1.5rem; }
}
</style>
@endsection