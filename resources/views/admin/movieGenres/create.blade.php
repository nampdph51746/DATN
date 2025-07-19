@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">➕ Thêm Thể Loại Phim</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.genres.store') }}" method="POST" id="genre-form">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Tên thể loại <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên thể loại" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Mô tả (tuỳ chọn)</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả" oninput="updateCharCount()" maxlength="255">{{ old('description') }}</textarea>
                            <div class="text-muted small mt-1">
                                <span id="char-count">0</span>/255 ký tự
                            </div>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Lưu
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="confirmCancel()">
                                <i class="bi bi-x-circle me-1"></i> Hủy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script --}}
@push('scripts')
<script>
    function updateCharCount() {
        const description = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        charCount.textContent = description.value.length;
    }

    function confirmCancel() {
        if (confirm('Bạn có chắc muốn hủy? Dữ liệu bạn đã nhập sẽ bị mất.')) {
            window.location.href = "{{ route('admin.genres.index') }}";
        }
    }

    // Khởi tạo đếm ký tự nếu đã có sẵn dữ liệu
    document.addEventListener("DOMContentLoaded", updateCharCount);
</script>
@endpush
@endsection
