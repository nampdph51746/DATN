
@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tạo Loại Ghế Mới</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('seat-type.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-dark">Tên Loại Ghế</label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên loại ghế" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="price_modifier" class="form-label text-dark">Hệ Số Giá</label>
                                        <input type="number" id="price_modifier" name="price_modifier" class="form-control" placeholder="Nhập hệ số giá (ví dụ: 1.50)" step="0.01" value="{{ old('price_modifier') }}">
                                        @error('price_modifier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="color_code" class="form-label text-dark">Mã Màu</label>
                                        <div class="input-group">
                                            <input type="color" id="color_code" name="color_code" class="form-control" value="{{ old('color_code', '#000000') }}">
                                        </div>
                                        @error('color_code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-dark">Mô Tả</label>
                                        <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả cho loại ghế" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top">
                                <button type="submit" class="btn btn-primary">Tạo Loại Ghế</button>
                                <a href="{{ route('seat-type.index') }}" class="btn btn-outline-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const colorInput = document.getElementById('color_code');
                const textInput = document.getElementById('color_code_text');

                // Đồng bộ giá trị từ color picker sang input text
                colorInput.addEventListener('input', function () {
                    textInput.value = colorInput.value;
                });

            });
        </script>
    @endsection
@endsection
