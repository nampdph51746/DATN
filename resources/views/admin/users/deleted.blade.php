@extends('layouts.admin.admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">All Customers List</h4>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light rounded"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                This Month
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="#!" class="dropdown-item">Download</a>
                                <!-- item-->
                                <a href="#!" class="dropdown-item">Export</a>
                                <!-- item-->
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
                                        <th>Avatar</th>
                                        <th>User Name</th>
                                        <th>User ID</th>
                                        <th>Customer Rank</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Date of Birth</th>
                                        <th>Created_At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deletedUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck2">
                                                    <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                                </div>
                                            </td>
                                            <td>
                                                <img src="{{ Storage::url($user->avatar_url) }}" class="avatar-sm rounded-circle me-2" alt="...">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->customerRank->name }}</td>
                                            <td>{{ $user->role->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->date_of_birth }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
                                               {{ $user->status }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('users.deleted.show', $user->id) }}"
                                                        class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                      </a>
                                                    @if($user->trashed())
                                                        <a href="{{ route('users.restore', $user->id) }}"
                                                        class="btn btn-soft-success btn-sm"
                                                        onclick="return confirm('Khôi phục người dùng này?')">
                                                            <iconify-icon icon="mdi:restore" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    {{ $deletedUsers->links('pagination::bootstrap-4') }}
                                </div>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
