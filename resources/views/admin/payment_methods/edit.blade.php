@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')
    
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img id="logoPreview" src="{{ $paymentMethod->logo_url ?: asset('assets/images/payment-placeholder.png') }}" alt="{{ $paymentMethod->name }}" class="img-fluid rounded bg-light">
                    <div class="mt-3">
                        <h4>Cập nhật phương thức {{ $paymentMethod->name }}</h4>
                        <p class="text-muted">Chỉnh sửa thông tin phương thức thanh toán {{ $paymentMethod->name }}.</p>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <!-- Button moved to bottom of form -->
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-x fs-18"></i> Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bx bx-credit-card me-2 text-primary"></i>
                        Chỉnh sửa phương thức thanh toán: {{ $paymentMethod->name }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($paymentMethod) && $paymentMethod->id)
                        <div class="alert alert-success">PaymentMethod ID: {{ $paymentMethod->id }}</div>
                    @else
                        <div class="alert alert-danger">PaymentMethod not found or ID is null!</div>
                    @endif
                    
                    <form id="paymentMethodEditForm-{{ $paymentMethod->id ?? 'unknown' }}" action="/admin/payment_methods/{{ $paymentMethod->id ?? '0' }}/update" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method_id" value="{{ $paymentMethod->id }}">
                        <!-- DEBUG: Form action = /admin/payment_methods/{{ $paymentMethod->id }}/update -->
                        <!-- DEBUG: PaymentMethod ID = {{ $paymentMethod->id }} -->
                        <!-- DEBUG: PaymentMethod object = {{ json_encode($paymentMethod) }} -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Thông tin phương thức:</strong> {{ $paymentMethod->name }} ({{ $paymentMethod->code }})
                                    <br>
                                    <small class="text-muted">Bạn chỉ có thể cập nhật logo và trạng thái hoạt động của phương thức thanh toán này.</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="logo_url" class="form-label">URL Logo</label>
                                    <input type="url" name="logo_url" id="logo_url" class="form-control @error('logo_url') is-invalid @enderror" value="{{ old('logo_url', $paymentMethod->logo_url) }}" placeholder="https://example.com/logo.png">
                                    @error('logo_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Nhập URL trực tiếp đến logo hoặc upload file bên dưới</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="logo_file" class="form-label">Upload Logo</label>
                                    <input type="file" name="logo_file" id="logo_file" class="form-control @error('logo_file') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                    @error('logo_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Định dạng: JPEG, PNG, JPG, GIF, SVG. Tối đa 2MB</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">
                                            <i class="bx bx-check-circle me-1"></i>
                                            Kích hoạt phương thức thanh toán
                                        </label>
                                    </div>
                                    <div class="form-text">Phương thức thanh toán chỉ hiển thị khi được kích hoạt</div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card bg-light-subtle">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-show me-2"></i>
                                            Xem trước
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <img id="previewLogo" src="{{ $paymentMethod->logo_url ?: asset('assets/images/payment-placeholder.png') }}" 
                                                     alt="Logo" 
                                                     style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px; border: 1px solid #e9ecef; background: white;">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-primary">
                                                    <i class="bx bx-wallet me-2"></i>
                                                    <span id="previewName">{{ $paymentMethod->name }}</span>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bx bx-code me-1"></i>
                                                    Mã: <span id="previewCode">{{ $paymentMethod->code }}</span>
                                                </small>
                                                <div class="mt-1">
                                                    <span id="previewStatus" class="badge {{ $paymentMethod->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                        <i class="bx {{ $paymentMethod->is_active ? 'bx-check-circle' : 'bx-x-circle' }} me-1"></i>
                                                        {{ $paymentMethod->is_active ? 'Hoạt động' : 'Ngưng hoạt động' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-save me-1"></i>
                                Cập nhật logo & trạng thái
                            </button>
                            <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i>
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logo URL preview
    const logoUrlInput = document.getElementById('logo_url');
    const logoFileInput = document.getElementById('logo_file');
    const logoPreview = document.getElementById('logoPreview');
    const previewLogo = document.getElementById('previewLogo');
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const isActiveInput = document.getElementById('is_active');
    const previewStatus = document.getElementById('previewStatus');

    // Update logo preview when URL changes
    logoUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            logoPreview.src = url;
            previewLogo.src = url;
            logoPreview.onerror = function() {
                this.src = "{{ asset('assets/images/payment-placeholder.png') }}";
            };
            previewLogo.onerror = function() {
                this.src = "{{ asset('assets/images/payment-placeholder.png') }}";
            };
        } else {
            logoPreview.src = "{{ asset('assets/images/payment-placeholder.png') }}";
            previewLogo.src = "{{ asset('assets/images/payment-placeholder.png') }}";
        }
    });

    // Update logo preview when file is selected
    logoFileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                previewLogo.src = e.target.result;
                logoUrlInput.value = ''; // Clear URL input when file is selected
            };
            reader.readAsDataURL(file);
        } else if (file) {
            alert('Vui lòng chọn file ảnh hợp lệ (JPEG, PNG, JPG, GIF, SVG).');
            this.value = '';
        }
    });

    // Update status preview
    isActiveInput.addEventListener('change', function() {
        if (this.checked) {
            previewStatus.className = 'badge bg-success-subtle text-success';
            previewStatus.innerHTML = '<i class="bx bx-check-circle me-1"></i>Hoạt động';
        } else {
            previewStatus.className = 'badge bg-danger-subtle text-danger';
            previewStatus.innerHTML = '<i class="bx bx-x-circle me-1"></i>Ngưng hoạt động';
        }
    });
});
</script>

<style>
/* Logo preview styling */
#logoPreview, #previewLogo {
    transition: all 0.3s ease;
}

#logoPreview:hover, #previewLogo:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Form enhancements */
.form-control:focus, .form-select:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    color: white;
}

.btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    color: white;
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border-radius: 12px;
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
}

/* Preview card styling */
.bg-light-subtle {
    background-color: #f8f9fa !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}
</style>
@endsection
