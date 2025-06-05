@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">All Seat Types</h4>
                        <form class="app-search d-none d-md-block ms-2">
                            <div class="position-relative">
                                <input type="search" class="form-control form-control-sm ps-5 pe-3 rounded-2"
                                    placeholder="Tìm kiếm loại ghế" autocomplete="off" value="">
                                <iconify-icon icon="solar:magnifer-linear"
                                    class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                                    style="font-size: 16px;"></iconify-icon>
                            </div>
                        </form>
                        <a href="{{ route('seat-type.create') }}" class="btn btn-sm btn-primary">
                            Add Seat Type
                        </a>
                        <a href="{{ route('seat-type.trash') }}" class="btn btn-sm btn-outline-danger" title="Thùng rác">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                class="align-middle fs-18"></iconify-icon>
                        </a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown"
                                aria-expanded="false">
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
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th>Price Modifier</th>
                                        <th>Color Code</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seatTypes as $seatType)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="customCheck{{ $seatType->id }}">
                                                    <label class="form-check-label"
                                                        for="customCheck{{ $seatType->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded avatar-md d-flex align-items-center justify-content-center"
                                                        style="background-color: {{ $seatType->color_code }};">
                                                        <span class="text-white">{{ substr($seatType->name, 0, 1) }}</span>
                                                    </div>
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
                            {{ $seatTypes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
