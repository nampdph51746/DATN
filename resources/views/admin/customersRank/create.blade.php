@extends('layouts.admin.admin')

@section('content')
    <!-- Start Container Fluid -->
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Customer Rank</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form action="{{ route('customers-rank.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="customer_rank-name" class="form-label">Customer Rank Name</label>
                                        <input type="text" id="customer_rank-name" name="name" class="form-control"
                                            value="{{ old('name') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-min_points_required" class="form-label">Min Points Required</label>
                                        <input type="number" id="customer_rank-min_points_required" name="min_points_required" class="form-control"
                                            value="{{ old('min_points_required') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-discount_percentage" class="form-label">Discount Percentage</label>
                                        <input type="number" id="customer_rank-discount_percentage" name="discount_percentage" class="form-control"
                                            value="{{ old('discount_percentage') }}" step="0.01" min="0" max="100"
                                            placeholder="e.g. 12.5">
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_rank-description" class="form-label">Customer Rank Description</label>
                                        <textarea id="customer_rank-description" name="description" class="form-control"
                                            rows="5" style="resize: none;">{{ old('description') }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Create Customer Rank</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Container Fluid -->
@endsection
