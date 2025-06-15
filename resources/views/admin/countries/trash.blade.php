@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

     <div class="row">
          <div class="col-xl-12">
               <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h4 class="card-title flex-grow-1">Danh sách quốc gia đã xóa</h4>

                        <form action="{{ route('admin.countries.trash') }}" method="GET" class="d-flex align-items-center">
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm tên quốc gia...">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                        </form>

                        <a href="{{ route('admin.countries.index') }}" class="btn btn-sm btn-outline-dark">
                              ⬅️ Quay lại danh sách
                         </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>Tên Quốc gia</th>
                                    <th>Mã code</th>
                                    <th>Thời gian xóa</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $key => $country)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $country->name }}</td>
                                    <td>{{ $country->code }}</td>
                                    <td>{{ $country->deleted_at }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('admin.countries.restore', $country->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-soft-success btn-sm" onclick="return confirm('Bạn có chắc chắn muốn khôi phục quốc gia này?')">
                                                    <iconify-icon icon="solar:refresh-circle-broken" class="fs-18"></iconify-icon>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.countries.forceDelete', $country->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn quốc gia này?')">
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
                                    <td colspan="5" class="text-center text-muted">Không có quốc gia nào đã bị xóa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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