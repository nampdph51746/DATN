@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl py-4">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fa fa-user-edit me-2"></i>
                        <h4 class="mb-0">Edit User Information</h4>
                    </div>
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-3">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="customer_rank_id" class="form-label fw-semibold">Customer Rank</label>
                                    <select name="customer_rank_id" id="customer_rank_id"
                                        class="form-select @error('customer_rank_id') is-invalid @enderror">
                                        <option value="">Select rank</option>
                                        @foreach ($customerRanks as $rank)
                                            <option value="{{ $rank->id }}" {{ old('customer_rank_id', $user->customer_rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-semibold">Role</label>
                                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                        <option value="">Select role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role', $selectedRole) == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">Status</label>
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                        <option value="">Select status</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->value }}" {{ old('status', $user->status->value ?? '') == $status->value ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa fa-save me-1"></i> Save
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fa fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection