@extends('layouts.admin.admin')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <!-- Left Avatar Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="position-relative bg-light p-2 rounded text-center">
                            <img src="{{ Storage::url($users->avatar_url) }}" alt="" class="avatar-xxl">
                        </div>
                        <div class="d-flex flex-wrap justify-content-center my-3">
                            <div>
                                <h4 class="mb-1">Current Avatar</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="col-xl-9 col-lg-8">
                <form action="{{ route('users.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Avatar Upload -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Avatar</h4>
                        </div>
                        <div class="card-body">
                            <label for="avatar_url" class="form-label">Avatar Image</label>
                            <input type="file" name="avatar_url" id="avatar_url" class="form-control" accept="image/*">
                            <small class="text-muted">Recommended: 1600 x 1200 (4:3). Accepted: PNG, JPG, GIF.</small>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">User Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Full Name -->
                                <div class="col-lg-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter full name" value="{{ $users->name }}">
                                </div>

                                <!-- Email -->
                                <div class="col-lg-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Enter email address" value="{{ $users->email }}">
                                </div>

                                <!-- Password -->
                                <div class="col-lg-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter password" value="{{ $users->password }}">
                                </div>

                                <!-- Phone Number -->
                                <div class="col-lg-6 mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control"
                                        placeholder="Enter phone number" value="{{ $users->phone_number }}">
                                </div>

                                <!-- Address -->
                                <div class="col-lg-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" id="address" class="form-control" rows="3"
                                        placeholder="Enter address" style="resize: none">{{ $users->address }}</textarea>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-lg-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $users->date_of_birth ? $users->date_of_birth->format('Y-m-d') : '') }}">
                                </div>

                                <!-- Customer Rank -->
                                <div class="col-lg-6 mb-3">
                                    <label for="customer_rank_id" class="form-label">Customer Rank</label>
                                    <select name="customer_rank_id" id="customer_rank_id" class="form-control">
                                        <option value="">Select rank</option>
                                        @foreach ($customerRanks as $rank)
                                            <option value="{{ $rank->id }}"
                                                {{ old('customer_rank_id', $users->customer_rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="role_id" class="form-label">Role</label>
                                    <select name="role_id" id="role_id" class="form-control">
                                        <option value="">Select role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id', $users->role_id) == $role->id ? 'selected' : '' }}> {{ $role->name }} </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select status</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->value }}" 
                                                {{ old('status', $users->status->value ?? '') == $status->value ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-3 bg-light mb-3 rounded mt-3">
                        <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary w-100">Save</button>
                            </div>
                            <div class="col-lg-2">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection