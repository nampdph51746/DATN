@extends('layouts.admin.admin')

@section('content')

<div class="container-xxl">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/default.png') }}" alt="" class="img-fluid rounded bg-light" id="previewImage">
                    <div class="mt-3">
                        <h4>{{ old('sku', 'Biến thể mới') }}</h4>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="submitForm()">Tạo biến thể</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.product-variants.index') }}" class="btn btn-primary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <form action="{{ route('admin.product-variants.store') }}" method="POST" enctype="multipart/form-data" id="variantForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:image-broken" class="align-middle fs-18 me-2"></iconify-icon>
                            Thêm ảnh biến thể
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                            <div class="fallback">
                                <input name="image" type="file" id="imageInput" onchange="previewImage(event)" />
                            </div>
                            <div class="dz-message needsclick">
                                <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                <h3 class="mt-4">Thả ảnh của bạn vào đây, hoặc <span class="text-primary">nhấn để duyệt</span></h3>
                                <span class="text-muted fs-13">
                                    Kích thước đề xuất 1600 x 1200 (4:3). Cho phép các tệp PNG, JPG và GIF
                                </span>
                            </div>
                        </div>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:box-broken" class="align-middle fs-18 me-2"></iconify-icon>
                            Thông tin biến thể
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="product-id" class="form-label">Sản phẩm</label>
                                    @if(isset($selectedProductId) && $selectedProductId)
                                        @php
                                            $selectedProduct = $products->firstWhere('id', $selectedProductId);
                                        @endphp
                                        <input type="hidden" name="product_id" value="{{ $selectedProductId }}">
                                        <input type="text" class="form-control" value="{{ $selectedProduct ? $selectedProduct->name : 'Sản phẩm đã chọn' }}" disabled>
                                    @else
                                        <select class="form-control" id="product-id" name="product_id" data-choices data-choices-groups data-placeholder="Chọn sản phẩm">
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ (old('product_id', $selectedProductId ?? '') == $product->id) ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @error('product_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Trạng thái</label>
                                    <select id="is_active" name="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Ngừng</option>
                                    </select>
                                    @error('is_active')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" placeholder="Giá biến thể (VNĐ)" step="0.01">
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Tồn kho</label>
                                    <input type="number" id="stock" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" placeholder="Số lượng tồn kho">
                                    @error('stock_quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <label class="form-label mb-2">Thuộc tính & Giá trị thuộc tính</label>
                        {{-- Hiển thị lỗi trùng biến thể --}}
                        @if ($errors->has('attribute_values'))
                            <div class="alert alert-danger py-2 mb-2">
                                {{ $errors->first('attribute_values') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="attributes-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Thuộc tính</th>
                                        <th style="width: 50%">Giá trị thuộc tính</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="attributes-container">
                                    <tr class="attribute-row">
                                        <td>
                                            <select class="form-control attribute-select" name="attributes[]" onchange="loadAttributeValues(this)">
                                                <option value="">Chọn thuộc tính</option>
                                                @foreach ($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}" data-values='@json($attribute->attributeValues)'>
                                                        {{ $attribute->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control value-select" name="attribute_values[]" required>
                                                <option value="">Chọn giá trị thuộc tính</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-attribute" style="display:none;" onclick="removeAttributeRow(this)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success mt-2" onclick="addAttributeRow()">
                            <i class="bx bx-plus"></i> Thêm thuộc tính
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function submitForm() {
        const variantForm = document.getElementById('variantForm');
        if (variantForm.checkValidity()) {
            variantForm.submit();
        } else {
            variantForm.reportValidity();
        }
    }

    function loadAttributeValues(select) {
        const valueSelect = select.closest('.attribute-row').querySelector('.value-select');
        valueSelect.innerHTML = '<option value="">Chọn giá trị thuộc tính</option>';
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const values = JSON.parse(selectedOption.getAttribute('data-values') || '[]');
            values.forEach(value => {
                const option = document.createElement('option');
                option.value = value.id;
                option.text = value.value;
                valueSelect.appendChild(option);
            });
        }
    }

    function addAttributeRow() {
        const container = document.getElementById('attributes-container');
        const firstRow = container.querySelector('.attribute-row');
        const newRow = firstRow.cloneNode(true);

        // Reset selects
        newRow.querySelector('.attribute-select').selectedIndex = 0;
        const valueSelect = newRow.querySelector('.value-select');
        valueSelect.innerHTML = '<option value="">Chọn giá trị thuộc tính</option>';

        // Hiện nút xóa cho các dòng thêm mới
        newRow.querySelector('.btn-remove-attribute').style.display = 'inline-block';

        container.appendChild(newRow);
        updateRemoveButtons();
    }

    function removeAttributeRow(btn) {
        const row = btn.closest('.attribute-row');
        row.remove();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#attributes-container .attribute-row');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.btn-remove-attribute');
            btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Dropzone !== 'undefined' && Dropzone.forElement('#myAwesomeDropzone')) {
            Dropzone.options.myAwesomeDropzone = {
                autoProcessQueue: false,
                uploadMultiple: false,
                parallelUploads: 1,
                maxFiles: 1,
                acceptedFiles: '.jpg,.jpeg,.gif,.png',
                addRemoveLinks: true,
                init: function () {
                    const thisDropzone = this;

                    this.on('addedfile', function (file) {
                        previewImage({ target: { files: [file] } });
                    });

                    const variantForm = document.getElementById('variantForm');
                    variantForm.addEventListener('submit', function (e) {
                        if (thisDropzone.getFiles().length) {
                            e.preventDefault();
                            thisDropzone.processQueue();
                        }
                    });

                    this.on('success', function (file, response) {
                        document.getElementById('variantForm').submit();
                    });
                }
            };
        }
    });
</script>

@endsection
