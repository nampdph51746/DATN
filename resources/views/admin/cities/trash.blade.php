@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
     <div class="row">
          <div class="col-xl-12">
               <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                         <h4 class="card-title flex-grow-1">Danh sách thành phố đã xóa</h4>

                         <form action="{{ route('admin.cities.trash') }}" method="GET" class="d-flex align-items-center">
                              <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm tên thành phố...">
                              <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                         </form>

                         <a href="{{ route('admin.cities.index') }}" class="btn btn-sm btn-outline-dark">
                              ⬅️ Quay lại danh sách
                         </a>
                    </div>

                    <div class="table-responsive">
                         <table class="table align-middle mb-0 table-hover table-centered">
                              <thead class="bg-light-subtle">
                                   <tr>
                                        <th>#</th>
                                        <th>Tên Thành phố</th>
                                        <th>Quốc gia</th>
                                        <th>Thời gian xóa</th>
                                        <th>Thao tác</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @forelse($cities as $key => $city)
                                   <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->country->name ?? 'Không xác định' }}</td>
                                        <td>{{ $city->deleted_at }}</td>
                                        <td>
                                             <div class="d-flex gap-2">
                                                  {{-- Khôi phục --}}
                                                  <form action="{{ route('admin.cities.restore', $city->id) }}" method="POST">
                                                       @csrf
                                                       @method('PATCH')
                                                       <button type="submit" class="btn btn-soft-success btn-sm" title="Khôi phục">
                                                            <iconify-icon icon="solar:refresh-circle-broken" class="fs-18"></iconify-icon>
                                                       </button>
                                                  </form>

                                                  {{-- Xóa vĩnh viễn --}}
                                                  <form action="{{ route('admin.cities.forceDelete', $city->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn thành phố này?')">
                                                       @csrf
                                                       @method('DELETE')
                                                       <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa vĩnh viễn">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                                       </button>
                                                  </form>
                                             </div>
                                        </td>
                                   </tr>
                                   @empty
                                   <tr>
                                        <td colspan="5" class="text-center text-muted">Không có thành phố nào đã bị xóa.</td>
                                   </tr>
                                   @endforelse
                              </tbody>
                         </table>
                    </div>

                    <div class="card-footer border-top">
                         <div class="d-flex justify-content-end mt-3">
                              {!! $cities->links('pagination::bootstrap-4') !!}
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
@endsection