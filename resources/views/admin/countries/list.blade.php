@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

     <div class="row">
          <div class="col-xl-12">
               <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách quốc gia</h4>

                    <form action="{{ route('admin.countries.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm tên quốc gia...">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                    </form>

                    <a href="countries-add" class="btn btn-sm btn-primary">
                        Thêm Quốc Gia
                    </a>

                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                            This Month
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#!" class="dropdown-item">Download</a>
                            <a href="#!" class="dropdown-item">Export</a>
                            <a href="#!" class="dropdown-item">Import</a>
                        </div>
                    </div>
                </div>

                    <div>
                         <div class="table-responsive">
                              <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>Tên Quốc gia</th>
                                    <th>Mã code</th>
                                    <th>Thời gian tạo</th>
                                    <th>Thời gian cập nhật</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $key => $country)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $country->name }}</td>
                                    <td>{{ $country->code }}</td>
                                    <td>{{ $country->created_at }}</td>
                                    <td>{{ $country->updated_at }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.countries.destroy', $country->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quốc gia này?')">
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
                                    <td colspan="6" class="text-center text-muted">Không có quốc gia nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                         </div>
                    </div>
                    <div class="card-footer border-top">
                         <div class="d-flex justify-content-end mt-3">
                              {!! $countries->links('pagination::bootstrap-4') !!}
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
@endsection