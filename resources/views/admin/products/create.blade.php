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
                            <h4>{{ old('name', 'Sản phẩm mới') }}</h4>
                        </div>
                    </div>
                    <div class="card-footer bg-light-subtle">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="submitForm()">Tạo sản phẩm</button>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-primary w-100">Hủy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thêm ảnh sản phẩm</h4>
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
                            <h4 class="card-title">Thông tin sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="product-name" class="form-label">Tên sản phẩm</label>
                                        <input type="text" id="product-name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Tên mặt hàng">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="product-categories" class="form-label">Danh mục sản phẩm</label>
                                    <select class="form-control" id="product-categories" name="category_id" data-choices data-choices-groups data-placeholder="Chọn danh mục">
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="product-description" class="form-label">Mô tả</label>
                                        <textarea class="form-control bg-light-subtle" id="product-description" name="description" rows="3" placeholder="Mô tả ngắn về sản phẩm">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="product-type" class="form-label">Loại sản phẩm</label>
                                        <select class="form-control" id="product-type" name="product_type" data-choices data-choices-groups data-placeholder="Chọn loại">
                                            <option value="">Chọn loại</option>
                                            <option value="food" {{ old('product_type') == 'food' ? 'selected' : '' }}>Thức ăn</option>
                                            <option value="drink" {{ old('product_type') == 'drink' ? 'selected' : '' }}>Đồ uống</option>
                                            <option value="combo" {{ old('product_type') == 'combo' ? 'selected' : '' }}>Combo</option>
                                        </select>
                                        @error('product_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="is-active" class="form-label">Trạng thái</label>
                                        <select class="form-control" id="is-active" name="is_active" data-choices data-choices-groups data-placeholder="Chọn trạng thái">
                                            <option value="">Chọn trạng thái</option>
                                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                                        </select>
                                        @error('is_active')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
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
            const productForm = document.getElementById('productForm');
            if (productForm.checkValidity()) {
                productForm.submit();
            } else {
                productForm.reportValidity();
            }
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

                        const productForm = document.getElementById('productForm');
                        productForm.addEventListener('submit', function (e) {
                            if (thisDropzone.getFiles().length) {
                                e.preventDefault();
                                thisDropzone.processQueue();
                            }
                        });

                        this.on('success', function (file, response) {
                            document.getElementById('productForm').submit();
                        });
                    }
                };
            }
        });
    </script>

@endsection