@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
    @include('admin.partials.notifications')

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Danh sách loại ghế</h4>

                        <a href="{{ route('seat-type.create') }}" class="btn btn-sm btn-primary">
                            Thêm loại ghế
                        </a>
                        <a href="{{ route('seat-type.trash') }}" class="btn btn-sm btn-outline-danger" title="Thùng rác">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                class="align-middle fs-18"></iconify-icon>
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên loại ghế</th>
                                        <th>Hệ số giá ghế</th>
                                        <th>Mã màu hiển thị</th>
                                        <th>Mô tả</th>
                                        <th>Thời gian tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seatTypes as $seatType)
                                        <tr>
                                            <td>
                                                {{ ($seatTypes->currentPage() - 1) * $seatTypes->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $seatType->name }}</p>
                                                </div>
                                            </td>
                                            <td>{{ number_format($seatType->price_modifier, 2) }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $seatType->color_code }};">
                                                    {{ $seatType->color_code }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($seatType->description, 50) }}</td>
                                            <td>{{ $seatType->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    {{-- <a href="{{ route('seat-types.show', $seatType->id) }}" class="btn btn-light btn-sm">
                                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a> --}}
                                                    <a href="{{ route('seat-type.edit', $seatType->id) }}"
                                                        class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <form action="{{ route('seat-type.destroy', $seatType->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this seat type?')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-end">
                            {{ $seatTypes->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
