@extends('layouts.admin.admin')

@section('content')
    <!-- Start Container Fluid -->
    <div class="container-xxl">

        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Customer Rank Details</h4>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Rank Name:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Rank ID:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark align-top">Description:</td>
                                        <td class="text-dark fw-medium px-0"
                                            style="word-break: break-word; max-height: 150px; overflow-y: auto;">
                                            {{ $customerRank->description ?: '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Min Points Required:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->min_points_required }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Discount Percentage:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->discount_percentage }}%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Created at:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Updated at:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Deleted at:</td>
                                        <td class="text-dark fw-medium px-0">{{ $customerRank->deleted_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <iconify-icon icon="mdi:arrow-left" style="vertical-align: middle;"></iconify-icon> Back
                    </a>
                </div>
            </div>
        </div>

    </div>
    <!-- End Container Fluid -->
@endsection