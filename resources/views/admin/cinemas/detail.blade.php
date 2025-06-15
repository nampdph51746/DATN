@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
  <div class="row gx-5 align-items-start">
    <!-- Cột ảnh, tên và mô tả bên trái -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded p-3">
        <img src="{{ asset('assets/' . $cinema->image_url) }}" alt="{{ $cinema->name }}"
          class="img-fluid mb-3" style="padding: 10px; background-color: #f8f9fa; object-fit: cover; width: 100%; max-width: 350px; height: 350px;">
        <h3 class="fw-bold text-primary mb-2 text-center">{{ $cinema->name }}</h3>
        <p class="text-muted fst-italic mb-2 text-start" style="font-size: 1rem;">{{ $cinema->description }}</p>

        <div class="d-flex gap-2">
          @if ($cinema->trashed())
          <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-soft-secondary btn-sm" title="Quay lại danh sách rạp đã xóa">
            <iconify-icon icon="solar:arrow-left-broken" class="fs-18"></iconify-icon>
          </a>
          @else
          <a href="{{ route('admin.cinemas.index') }}" class="btn btn-soft-secondary btn-sm" title="Quay lại danh sách rạp">
            <iconify-icon icon="solar:arrow-left-broken" class="fs-18"></iconify-icon>
          </a>
          @endif

          @if(!$cinema->trashed())
          <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
            <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
          </a>

          <form action="{{ route('admin.cinemas.destroy', $cinema->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa rạp này?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa mềm">
              <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
            </button>
          </form>
          @else
          <form action="{{ route('admin.cinemas.forceDelete', $cinema->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn rạp này?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn">
              <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>

    <!-- Cột bên phải: Thông tin chi tiết -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded p-4">
        <h3 class="fw-bold mb-4" style="font-size: 40px;">Thông tin chi tiết rạp: {{ $cinema->name }}</h3>

        <ul class="list-unstyled fs-5" style="font-size: 24px; line-height: 1.4;">
          <li class="mb-3"><strong>Địa chỉ:</strong> {{ $cinema->address }}</li>
          @if($cinema->hotline)
          <li class="mb-3"><strong>Hotline:</strong> <a href="tel:{{ $cinema->hotline }}" class="text-decoration-none">{{ $cinema->hotline }}</a></li>
          @endif
          @if($cinema->city_id)
          <li class="mb-3"><strong>Thành phố:</strong> {{ $cinema->city->name }}</li>
          @endif
          @if($cinema->email)
          <li class="mb-3"><strong>Email:</strong> <a href="mailto:{{ $cinema->email }}" class="text-decoration-none">{{ $cinema->email }}</a></li>
          @endif
          @if($cinema->opening_hours)
          <li class="mb-3"><strong>Giờ mở cửa:</strong> {{ $cinema->opening_hours }}</li>
          @endif
          <li class="mb-3">
            <strong>Trạng thái:</strong>
            @if($cinema->status == 'active')
            <span class="badge bg-success">Hoạt động</span>
            @else
            <span class="badge bg-secondary">Không hoạt động</span>
            @endif
          </li>
        </ul>

        @if($cinema->map_url)
        <h4 class="mb-3">Bản đồ vị trí</h4>
        <div style="width: 100%; height: 300px; overflow: hidden;">
          <div style="width: 100%; height: 100%;">
            {!! str_replace(
            '<iframe', '<iframe style="width: 100%; height: 100%; border: 0;"' ,
              $cinema->map_url
              ) !!}
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection