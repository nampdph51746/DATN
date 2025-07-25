@extends('layouts.admin.admin')

@section('content')
    <!-- Start Container Fluid -->
    <div class="container-xxl">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card overflow-hidden">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center p-3">
                    <h5 class="card-title mb-0">Deleted Roles</h5>
                    <form action="{{ route('customers-rank.deleted') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="search" name="keyword" class="form-control" placeholder="Search by role name..."
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
                                    <th>Role Name</th>
                                    <th>Role ID</th>
                                    <th>Created At</th>
                                    <th>Deleted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customerRanks as $customerRank)
                                    <tr>
                                        <td>{{ $customerRank->name }}</td>
                                        <td>{{ $customerRank->id }}</td>
                                        <td>{{ $customerRank->created_at }}</td>
                                        <td>{{ $customerRank->deleted_at }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('customers-rank.deleted-detail', $customerRank->id) }}"
                                                    class="btn btn-light btn-sm" title="View Detail">
                                                    <iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>

                                                @if($customerRank->trashed())
                                                    <form action="{{ route('customers-rank.restore', $customerRank->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-success btn-sm"
                                                            onclick="return confirm('Restore this rank?')" title="Restore">
                                                            <iconify-icon icon="mdi:restore"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('customers-rank.forceDelete', $customerRank->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Delete permanently?')"
                                                        title="Delete Permanently">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No deleted customer ranks found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
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