@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    @include('admin.partials.notifications')

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('assets/images/default.png') }}" alt="Combo Preview" class="img-fluid rounded bg-light" id="previewImage">
                    <div class="mt-3">
                        <h4 id="previewSku">Combo mới</h4>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="submitForm()">Tạo Combo</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.combos.index') }}" class="btn btn-primary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <form action="{{ route('admin.combos.store') }}" method="POST" id="comboForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Biến thể Combo</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Sản phẩm</label>
                                    <select class="form-control" id="product_id" name="product_id" onchange="loadVariants(this)">
                                        <option value="">Chọn sản phẩm</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="combo_product_variant_id" class="form-label">Biến thể đại diện Combo</label>
                                    <select class="form-control" id="combo_product_variant_id" name="combo_product_variant_id" onchange="updatePreview()" required>
                                        <option value="">Chọn biến thể</option>
                                    </select>
                                    @error('combo_product_variant_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Các mục trong Combo</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="items-table">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 40%">Sản phẩm</th>
                                        <th style="width: 40%">Biến thể</th>
                                        <th style="width: 15%">Số lượng</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <tr class="item-row">
                                        <td>
                                            <select class="form-control product-select" name="items[0][product_id]" onchange="loadItemVariants(this)" required>
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('items.0.product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <select class="form-control variant-select" name="items[0][item_product_variant_id]" required>
                                                <option value="">Chọn biến thể</option>
                                            </select>
                                            @error('items.0.item_product_variant_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="items[0][quantity]" value="1" min="1" required>
                                            @error('items.0.quantity')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" style="display:none;" onclick="removeItemRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @error('items')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="button" class="btn btn-success mt-2" onclick="addItemRow()">Thêm mục Combo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function submitForm() {
            const comboForm = document.getElementById('comboForm');
            if (comboForm.checkValidity()) {
                comboForm.submit();
            } else {
                comboForm.reportValidity();
            }
        }

        function loadVariants(select) {
            const variantSelect = document.getElementById('combo_product_variant_id');
            variantSelect.innerHTML = '<option value="">Chọn biến thể</option>';
            const productId = select.value;
            if (productId) {
                fetch(`/admin/products/${productId}/variants`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) {
                        data.forEach(variant => {
                            const option = document.createElement('option');
                            option.value = variant.id;
                            option.text = variant.sku;
                            variantSelect.appendChild(option);
                        });
                    } else if (data.error) {
                        console.error('Server error:', data.error);
                    }
                    updatePreview();
                })
                .catch(error => console.error('Error loading variants:', error));
            }
        }

        function loadItemVariants(select) {
            const row = select.closest('.item-row');
            const variantSelect = row.querySelector('.variant-select');
            variantSelect.innerHTML = '<option value="">Chọn biến thể</option>';
            const productId = select.value;
            if (productId) {
                fetch(`/admin/products/${productId}/variants`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) {
                        data.forEach(variant => {
                            const option = document.createElement('option');
                            option.value = variant.id;
                            option.text = variant.sku;
                            variantSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error loading item variants:', error));
            }
        }

        function addItemRow() {
            const container = document.getElementById('items-container');
            const rows = container.getElementsByClassName('item-row');
            const newRow = rows[0].cloneNode(true);
            const index = rows.length;

            newRow.querySelector('.product-select').name = `items[${index}][product_id]`;
            newRow.querySelector('.variant-select').name = `items[${index}][item_product_variant_id]`;
            newRow.querySelector('input[type="number"]').name = `items[${index}][quantity]`;
            newRow.querySelector('input[type="number"]').value = 1;

            newRow.querySelector('.product-select').selectedIndex = 0;
            newRow.querySelector('.variant-select').innerHTML = '<option value="">Chọn biến thể</option>';
            newRow.querySelector('.btn-remove-item').style.display = 'inline-block';

            container.appendChild(newRow);
            updateRemoveButtons();
        }

        function removeItemRow(btn) {
            const row = btn.closest('.item-row');
            row.remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const rows = document.getElementsByClassName('item-row');
            Array.from(rows).forEach((row, idx) => {
                const btn = row.querySelector('.btn-remove-item');
                btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
                row.querySelector('.product-select').name = `items[${idx}][product_id]`;
                row.querySelector('.variant-select').name = `items[${idx}][item_product_variant_id]`;
                row.querySelector('input[type="number"]').name = `items[${idx}][quantity]`;
            });
        }

        function updatePreview() {
            const variantSelect = document.getElementById('combo_product_variant_id');
            const selectedOption = variantSelect.selectedOptions[0];
            const skuPreview = document.getElementById('previewSku');
            skuPreview.textContent = selectedOption ? selectedOption.text : 'Combo mới';

            const previewImage = document.getElementById('previewImage');
            if (selectedOption && selectedOption.value) {
                fetch(`/admin/product-variants/${selectedOption.value}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    previewImage.src = data.image_url ? `{{ asset('storage/') }}/${data.image_url}` : `{{ asset('assets/images/default.png') }}`;
                })
                .catch(error => console.error('Error loading preview:', error));
            } else {
                previewImage.src = `{{ asset('assets/images/default.png') }}`;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateRemoveButtons();
        });
    </script>
@endsection