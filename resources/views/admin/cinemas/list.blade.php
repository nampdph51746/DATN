@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách rạp</h4>

                    <form action="{{ route('admin.cinemas.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Tìm tên rạp chiếu...">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                    </form>

                    <a href="cinemas-add" class="btn btn-sm btn-primary">
                        Thêm Rạp Chiếu
                    </a>

                    <a href="cinemas/trash"
                              class="btn btn-sm"
                              style="background-color: white; border: 1px solid black; color: black;"
                              onmouseover="this.style.backgroundColor='#e6e6e6'"
                              onmouseout="this.style.backgroundColor='white'">
                              Đã xóa
                         </a>
                </div>

                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>Tên rạp</th>
                                    <th>Địa chỉ</th>
                                    <th>Thành phố</th>
                                    <th>SĐT liên hệ</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                             @forelse($cinemas as $key => $cinema)
                                             <tr>
                                                  <td>{{ $key + 1 }}</td>
                                                  <td>{{ $cinema->name }}</td>
                                                  <td>{{ Str::limit($cinema->address, 40, '...') ?? 'Không xác định' }}</td>
                                                  <td>{{ $cinema->city->name ?? 'Không xác định' }}</td>
                                                  <td>{{ $cinema->hotline }}</td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="{{ route('admin.cinemas.show', $cinema->id) }}" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="btn btn-soft-primary btn-sm">
                                                                 <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                                            </a>
                                                            <form action="{{ route('admin.cinemas.destroy', $cinema->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa rạp chiếu này?')">
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
                                                  <td colspan="6" class="text-center text-muted">Không có rạp chiếu nào.</td>
                                             </tr>
                                             @endforelse
                                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end mt-3">
                        {!! $cinemas->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection