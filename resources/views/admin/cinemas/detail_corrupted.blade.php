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

        <div class="mt-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0 d-flex align-items-center justify-content-between" style="cursor: pointer;" onclick="toggleRoomsSection()">
                <span>
                  <i class="bx bx-door-open me-2"></i>
                  Danh sách phòng chiếu ({{ $cinema->rooms->count() }})
                </span>
                <i class="bx bx-chevron-down" id="roomsChevron"></i>
              </h5>
            </div>
            <div id="roomsContent" style="display: block;">
              <div class="card-body">
                  @if($cinema->rooms->count() > 0)
                    <div class="row g-3">
                      @foreach($cinema->rooms as $index => $room)
                        <div class="col-md-6">
                          <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                              <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 text-primary">{{ $room->name }}</h5>
                                @if($room->status === 'active')
                                  <span class="badge bg-success">Hoạt động</span>
                                @elseif($room->status === 'maintenance')
                                  <span class="badge bg-warning text-dark">Bảo trì</span>
                                @else
                                  <span class="badge bg-danger">Không hoạt động</span>
                                @endif
                              </div>
                              
                              <div class="row text-muted mb-3">
                                <div class="col-6">
                                  <i class="bx bx-category me-1"></i>
                                  <small>{{ $room->roomType->name ?? 'Chưa xác định' }}</small>
                                </div>
                                <div class="col-6">
                                  <i class="bx bx-chair me-1"></i>
                                  <small>{{ $room->capacity }} ghế ({{ $room->seats->count() }} đã tạo)</small>
                                </div>
                              </div>
                              
                              <div class="d-flex gap-2">
                                <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                  <i class="bx bx-show me-1"></i>
                                  Xem chi tiết
                                </a>
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-secondary">
                                  <i class="bx bx-edit me-1"></i>
                                  Sửa
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <div class="text-center py-4">
                      <i class="bx bx-door-open display-4 text-muted mb-3"></i>
                      <h5 class="text-muted">Chưa có phòng chiếu nào</h5>
                      <p class="text-muted mb-3">Rạp này chưa có phòng chiếu nào được tạo.</p>
                      <a href="{{ route('admin.rooms.create') }}?cinema_id={{ $cinema->id }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Thêm phòng mới
                      </a>
                    </div>
                  @endif
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('script')
<script>
function toggleRoomsSection() {
    const content = document.getElementById('roomsContent');
    const chevron = document.getElementById('roomsChevron');
    
    console.log('🖱️ Toggle clicked, current display:', content.style.display);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        chevron.classList.remove('bx-chevron-up');
        chevron.classList.add('bx-chevron-down');
        console.log('📂 Opening rooms section');
    } else {
        content.style.display = 'none';
        chevron.classList.remove('bx-chevron-down');
        chevron.classList.add('bx-chevron-up');
        console.log('📁 Closing rooms section');
    }
}

console.log('🎬 Toggle function loaded');
</script>
@endsection
    console.log('🎬 Cinema detail page loaded');
    
    const roomsCollapse = document.getElementById('roomsCollapse');
    const collapseButton = document.querySelector('[data-bs-target="#roomsCollapse"]');
    
    console.log('🔍 Elements found:', {
        roomsCollapse: !!roomsCollapse,
        collapseButton: !!collapseButton,
        bootstrapVersion: typeof bootstrap !== 'undefined' ? 'loaded' : 'missing'
    });
    
    if (roomsCollapse && collapseButton) {
        console.log('✅ Initial state:', {
            hasShowClass: roomsCollapse.classList.contains('show'),
            ariaExpanded: collapseButton.getAttribute('aria-expanded')
        });
        
        // Test Bootstrap collapse events
        roomsCollapse.addEventListener('show.bs.collapse', function () {
            console.log('� Collapse is opening...');
        });
        
        roomsCollapse.addEventListener('shown.bs.collapse', function () {
            console.log('✅ Collapse opened');
        });
        
        roomsCollapse.addEventListener('hide.bs.collapse', function () {
            console.log('📁 Collapse is closing...');
        });
        
        roomsCollapse.addEventListener('hidden.bs.collapse', function () {
            console.log('❌ Collapse closed');
        });
        
function toggleRoomsSection() {
    const content = document.getElementById('roomsContent');
    const chevron = document.getElementById('roomsChevron');
    
    console.log('🖱️ Toggle clicked, current display:', content.style.display);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        chevron.classList.remove('bx-chevron-up');
        chevron.classList.add('bx-chevron-down');
        console.log('📂 Opening rooms section');
    } else {
        content.style.display = 'none';
        chevron.classList.remove('bx-chevron-down');
        chevron.classList.add('bx-chevron-up');
        console.log('📁 Closing rooms section');
    }
}

console.log('🎬 Script loaded successfully');
</script>
@endsection
@endsection