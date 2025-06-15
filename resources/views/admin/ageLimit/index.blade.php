@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách giới hạn độ tuổi</h4>

                    <a href="{{ route('admin.age_limits.create') }}" class="btn btn-sm btn-primary">
                        Thêm mới
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th style="width: 40px;">STT</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Độ tuổi tối thiểu</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ageLimits as $index => $ageLimit)
                                <tr>
                                    <td>{{ ($ageLimits->currentPage() - 1) * $ageLimits->perPage() + $loop->iteration }}</td>
                                    <td>{{ $ageLimit->name }}</td>
                                    <td>{{ $ageLimit->description }}</td>
                                    <td>{{ $ageLimit->min_age }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.age_limits.edit', $ageLimit->id) }}" class="btn btn-sm btn-soft-primary">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.age_limits.destroy', $ageLimit->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </button>
                                            </form>
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
                        {{ $ageLimits->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateDeleteAgeLimitButton() {
        const checked = document.querySelectorAll('.age-limit-checkbox:checked');
        const form = document.getElementById('delete-selected-age-limit-form');
        form.style.display = checked.length > 0 ? 'inline-block' : 'none';
    }

    document.getElementById('checkAllAgeLimits').addEventListener('change', function () {
        document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteAgeLimitButton();
    });

    document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteAgeLimitButton);
    });

    document.getElementById('delete-selected-age-limit-form').addEventListener('submit', function (e) {
        const checked = Array.from(document.querySelectorAll('.age-limit-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-age-limit-ids').value = checked.join(',');
    });
});
</script>
@endpush