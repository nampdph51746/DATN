{{-- filepath: resources/views/admin/movies/ageLimit/index.blade.php --}}
@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách giới hạn độ tuổi</h4>

                   @can('delete age limit')
                        <form id="delete-selected-age-limit-form" method="POST" action="{{ route('admin.age_limits.bulkDelete') }}">
                        @csrf
                        <input type="hidden" name="ids" id="selected-age-limit-ids">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon>
                            Xóa đã chọn
                        </button>
                    </form>
                   @endcan

                    @can('create age limit')
                        <a href="{{ route('admin.age_limits.create') }}" class="btn btn-sm btn-primary">
                        Thêm mới
                        </a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th style="width: 20px;">
                                    <div class="form-check ms-1">
                                        <input type="checkbox" class="form-check-input" id="checkAllAgeLimits">
                                    </div>
                                </th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Độ tuổi tối thiểu</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ageLimits as $ageLimit)
                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input age-limit-checkbox" value="{{ $ageLimit->id }}" id="ageLimitCheck{{ $ageLimit->id }}">
                                        </div>
                                    </td>
                                    <td>{{ $ageLimit->name }}</td>
                                    <td>{{ $ageLimit->description }}</td>
                                    <td>{{ $ageLimit->min_age }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.age_limits.edit', $ageLimit->id) }}" class="btn btn-sm btn-soft-primary">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            @can('delete age limit')
                                            <form action="{{ route('admin.age_limits.destroy', $ageLimit->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light">
                                                    <iconify-icon icon="solar:trash-bin-trash-broken" class="align-middle fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Chưa có dữ liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ageLimits->hasPages())
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $ageLimits->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hiện/ẩn nút xóa đã chọn
    function updateDeleteAgeLimitButton() {
        const checked = document.querySelectorAll('.age-limit-checkbox:checked');
        const form = document.getElementById('delete-selected-age-limit-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }

    // Chọn tất cả
    document.getElementById('checkAllAgeLimits')?.addEventListener('change', function() {
        document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteAgeLimitButton();
    });

    // Check từng dòng
    document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteAgeLimitButton);
    });

    document.getElementById('delete-selected-age-limit-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Ngăn submit ngay lập tức

        const checked = Array.from(document.querySelectorAll('.age-limit-checkbox:checked')).map(cb => cb.value);
        console.log('IDs được chọn:', checked);

        if (checked.length === 0) {
            alert('Bạn chưa chọn mục nào!');
            return;
        }

        if (confirm('Bạn có chắc muốn xóa các giới hạn đã chọn?')) {
            document.getElementById('selected-age-limit-ids').value = checked.join(',');
            this.submit(); // Submit sau khi đã gán
        }
    });
});
</script>
@endsection