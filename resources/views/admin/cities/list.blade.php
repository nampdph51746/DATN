@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

     <div class="row">
          <div class="col-xl-12">
               <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                         <h4 class="card-title flex-grow-1">Danh sách thành phố</h4>

                         <form action="{{ route('admin.cities.index') }}" method="GET" class="d-flex align-items-center">
                              <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm tên thành phố...">
                              <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                         </form>

                         <a href="cities-add" class="btn btn-sm btn-primary">
                              Thêm Thành Phố
                         </a>

                         <a href="cities/trash"
                              class="btn btn-sm"
                              style="background-color: white; border: 1px solid black; color: black;"
                              onmouseover="this.style.backgroundColor='#e6e6e6'"
                              onmouseout="this.style.backgroundColor='white'">
                              Đã xóa
                         </a>

                    </div>

                    <div>
                         <div class="table-responsive">
                              <div class="table-responsive">
                                   <table class="table align-middle mb-0 table-hover table-centered">
                                        <thead class="bg-light-subtle">
                                             <tr>
                                                  <th>#</th>
                                                  <th>Tên Thành phố</th>
                                                  <th>Quốc gia</th>
                                                  <th>Thời gian tạo</th>
                                                  <th>Thời gian cập nhật</th>
                                                  <th>Thao tác</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             @forelse($cities as $key => $city)
                                             <tr>
                                                  <td>{{ $key + 1 }}</td>
                                                  <td>{{ $city->name }}</td>
                                                  <td>{{ $city->country->name ?? 'Không xác định' }}</td>
                                                  <td>{{ $city->created_at }}</td>
                                                  <td>{{ $city->updated_at }}</td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-soft-primary btn-sm">
                                                                 <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                                            </a>
                                                            <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thành phố này?')">
                                                                 @csrf
                                                                 @method('DELETE')
                                                                 <button type="submit" class="btn btn-soft-danger btn-sm">
                                                                      <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                                                 </button>
                                                            </form>
                                                       </div>
                                                  </td>
                                             </tr>
                                             @empty
                                             <tr>
                                                  <td colspan="6" class="text-center text-muted">Không có thành phố nào.</td>
                                             </tr>
                                             @endforelse
                                        </tbody>
                                   </table>
                              </div>
                         </div>
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