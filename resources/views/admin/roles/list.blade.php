@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl py-4">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="card-title mb-0"><i class="fas fa-user-tag me-2"></i>Quản lý Vai trò</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('roles.create') }}" class="btn btn-light"><i class="fas fa-plus me-2"></i>Thêm vai trò</a>
                        @can('delete role')
                            <a href="{{ route('roles.deleted') }}" class="btn btn-outline-light" data-bs-toggle="tooltip" title="Xem vai trò đã xóa">
                                <i class="fas fa-trash-restore"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="GET" action="{{ route('roles.index') }}" class="mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4 col-lg-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm vai trò" value="{{ request('keyword') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <select name="created_order" class="form-select">
                                <option value="">Thứ tự tạo</option>
                                <option value="desc" {{ request('created_order') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('created_order') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i>Lọc</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-2"></i>Đặt lại</a>
                        </div>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectAll">
                        <label class="form-check-label" for="selectAll">Chọn tất cả</label>
                    </div>
                    <select class="form-select w-auto" id="bulkActions" disabled>
                        <option value="">Hành động hàng loạt</option>
                        @can('delete role')
                            <option value="delete">Xóa mềm</option>
                        @endcan
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th style="width: 50px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1"></label>
                                    </div>
                                </th>
                                <th>Tên Vai Trò</th>
                                <th>ID</th>
                                <th>Ngày Tạo</th>
                                <th>Ngày Cập Nhật</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="rolesTableBody">
                            @forelse ($roles as $role)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input role-checkbox" id="roleCheck{{ $role->id }}" value="{{ $role->id }}">
                                            <label class="form-check-label" for="roleCheck{{ $role->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    </td>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->created_at ? \Carbon\Carbon::parse($role->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $role->updated_at ? \Carbon\Carbon::parse($role->updated_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit role')
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete role')
                                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $role->id }}" title="Xóa mềm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $role->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $role->id }}">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc muốn xóa vai trò <strong>{{ $role->name }}</strong>? Hành động này sẽ chuyển vai trò vào thùng rác.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                        Không có vai trò nào phù hợp. <a href="{{ route('roles.create') }}" class="text-primary">Tạo vai trò mới</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-light border-top p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Hiển thị <span class="fw-semibold">{{ $roles->firstItem() ?? 0 }}</span> đến 
                        <span class="fw-semibold">{{ $roles->lastItem() ?? 0 }}</span> trong tổng số 
                        <span class="fw-semibold">{{ $roles->total() }}</span> kết quả
                    </div>
                    {{ $roles->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            .card-header {
                border-radius: 12px 12px 0 0;
            }
            .table th, .table td {
                vertical-align: middle;
            }
            .table-hover tbody tr:hover {
                background-color: #f1f5f9;
            }
            .form-select, .form-control {
                border-radius: 8px;
            }
            .btn-sm {
                padding: 0.4rem 0.8rem;
            }
            .badge {
                font-size: 0.9rem;
                padding: 0.5em 1em;
            }
            .sticky-top {
                top: 0;
                z-index: 1;
            }
            .spinner-border {
                display: none;
            }
            .loading .spinner-border {
                display: inline-block;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Select all checkboxes
            document.getElementById('selectAll').addEventListener('change', function () {
                document.querySelectorAll('.role-checkbox').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkActions();
            });

            // Toggle bulk actions dropdown
            document.querySelectorAll('.role-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkActions);
            });

            function toggleBulkActions() {
                const selectedCheckboxes = document.querySelectorAll('.role-checkbox:checked').length;
                const bulkActions = document.getElementById('bulkActions');
                bulkActions.disabled = selectedCheckboxes === 0;
            }

            // Simulate loading state (optional, can be removed if not needed)
            document.addEventListener('DOMContentLoaded', function () {
                const tableBody = document.getElementById('rolesTableBody');
                tableBody.classList.add('loading');
                setTimeout(() => {
                    tableBody.classList.remove('loading');
                }, 1000);
            });
        </script>
    @endpush
@endsection