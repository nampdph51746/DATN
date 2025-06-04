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
                    <h5 class="card-title mb-0">Deleted Roles</h5>
                    <form action="{{ route('roles.deleted') }}" method="GET" class="d-flex align-items-center">
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
                                    <th>Created at</th>
                                    <th>Deleted at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->created_at }}</td>
                                        <td>{{ $role->deleted_at }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('roles.deleted-detail', $role->id) }}"
                                                    class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>

                                                @if($role->trashed())
                                                    <form action="{{ route('roles.restore', $role->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-success btn-sm"
                                                            onclick="return confirm('Restore this role?')">
                                                            <iconify-icon icon="mdi:restore"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('roles.forceDelete', $role->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to permanently delete this role?')">
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
                            <span class="fw-semibold">{{ $roles->firstItem() ?? 0 }}</span>
                            to
                            <span class="fw-semibold">{{ $roles->lastItem() ?? 0 }}</span>
                            of
                            <span class="fw-semibold">{{ $roles->total() }}</span> Results
                        </div>
                    </div>
                    <div class="col-sm-auto mt-3 mt-sm-0">
                        {{ $roles->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <!-- End Container Fluid -->

@endsection