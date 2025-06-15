@extends('layouts.admin.admin')

@section('content')
<div class="container">
    <h1>Chỉnh sửa trạng thái phương thức thanh toán: {{ $paymentMethod->name }}</h1>

    <form action="{{ route('payment_methods.updateStatus', $paymentMethod->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="is_active" class="form-label">Trạng thái kích hoạt</label>
            <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                <option value="1" {{ $paymentMethod->is_active ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ !$paymentMethod->is_active ? 'selected' : '' }}>Ngừng hoạt động</option>
            </select>
            @error('is_active')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
        <a href="{{ route('payment_methods.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
