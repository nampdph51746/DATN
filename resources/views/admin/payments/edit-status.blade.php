@extends('layouts.admin.admin')

@section('title', 'Chỉnh sửa trạng thái thanh toán')

@section('content')
<div class="container">
    <h1>Chỉnh sửa trạng thái thanh toán #{{ $payment->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.payments.updateStatus', $payment->id) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3 w-25">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="">-- Chọn trạng thái --</option>
                @foreach($status as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $payment->status->value ?? $payment->status) == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
