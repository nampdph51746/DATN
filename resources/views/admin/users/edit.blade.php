@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">

            <!-- Right Form Section -->
            <div class="col-xl-9 col-lg-8 mx-auto">
                <form action="{{ route('users.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- User Information -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">User Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Customer Rank -->
                                <!-- Customer Rank -->
                                <div class="col-lg-6 mb-3">
                                    <label for="customer_rank_id" class="form-label">Customer Rank</label>
                                    <select name="customer_rank_id" id="customer_rank_id"
                                        class="form-control @error('customer_rank_id') is-invalid @enderror">
                                        <option value="">Select rank</option>
                                        @foreach ($customerRanks as $rank)
                                            <option value="{{ $rank->id }}" {{ old('customer_rank_id', $users->customer_rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="col-lg-6 mb-3">
                                    <label for="role_id" class="form-label">Role</label>
                                    <select name="role_id" id="role_id"
                                        class="form-control @error('role_id') is-invalid @enderror">
                                        <option value="">Select role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', $users->role_id) == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-lg-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="">Select status</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->value }}" {{ old('status', $users->status->value ?? '') == $status->value ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <!-- Action Buttons -->
                                <div class="p-3 bg-light mb-3 rounded mt-3">
                                    <div class="row justify-content-end g-2">
                                        <div class="col-lg-2">
                                            <button type="submit" class="btn btn-primary w-100">Save</button>
                                        </div>
                                        <div class="col-lg-2">
                                            <a href="{{ route('users.index') }}"
                                                class="btn btn-outline-secondary w-100">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                </form>


            </div>
        </div>
    </div>
@endsection