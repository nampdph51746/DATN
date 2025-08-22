@extends('layouts.admin.admin') {{-- hoặc layout bạn đang dùng --}}

@section('content')
<div class="container mt-4">
    <h2>Cập nhật trạng thái đơn hàng #{{ $booking->id }}</h2>

    <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                <option value="pending" {{ $booking->status?->value === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="confirmed" {{ $booking->status?->value === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="cancelled" {{ $booking->status?->value === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
