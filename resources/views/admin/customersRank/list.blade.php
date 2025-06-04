@extends('layouts.admin.admin')

@section('content')
    <!-- Start Container Fluid -->
    <div class="container-xxl">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card overflow-hiddenCoupons">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center p-3">
                    <h5 class="card-title mb-0">Customer Rank Management</h5>
                    <form action="{{ route('customers-rank.index') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="search" name="keyword" class="form-control" placeholder="Search by rank name..."
                                autocomplete="off" value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <iconify-icon icon="solar:magnifer-linear" class="align-middle"></iconify-icon>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Min Points</th>
                                    <th>Discount %</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerRanks as $customerRank)
                                    <tr>
                                        <td>{{ $customerRank->id }}</td>
                                        <td>{{ $customerRank->name }}</td>
                                        <td>{{ $customerRank->min_points_required }}</td>
                                        <td>{{ $customerRank->discount_percentage }}%</td>
                                        <td>{{ $customerRank->description }}</td>
                                        <td>{{ $customerRank->created_at }}</td>
                                        <td>{{ $customerRank->updated_at }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('customers-rank.show', $customerRank->id) }}"
                                                    class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>

                                                <a href="{{ route('customers-rank.edit', $customerRank->id) }}"
                                                    class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>

                                                <form action="{{ route('customersRank.softDelete', $customerRank->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this customer rank?')">
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
                    <!-- end table-responsive -->
                </div>
                <div class="row g-0 align-items-center justify-content-between text-center text-sm-start p-3 border-top">
                    <div class="col-sm">
                        <div class="text-muted">
                            Showing
                            <span class="fw-semibold">{{ $customerRanks->firstItem() ?? 0 }}</span>
                            to
                            <span class="fw-semibold">{{ $customerRanks->lastItem() ?? 0 }}</span>
                            of
                            <span class="fw-semibold">{{ $customerRanks->total() }}</span> Results
                        </div>
                    </div>
                    <div class="col-sm-auto mt-3 mt-sm-0">
                        {{ $customerRanks->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container Fluid -->
@endsection
