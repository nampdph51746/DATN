@extends('layouts.admin.admin')

@section('content')
    <!-- Start Container Fluid -->
    <div class="container-xxl">

        <div class="row justify-content-center">
            <div class="col-lg-6"> <!-- tăng chiều rộng để mô tả không bị quá chật -->

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Roles Details</h4>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark" style="width: 30%;">Role Name:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Role ID:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark align-top">Description:</td>
                                        <td class="text-dark fw-medium px-0" style="word-break: break-word; max-height: 150px; overflow-y: auto;">
                                            {{ $role->description ?: '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Created at:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 fw-semibold text-dark">Updated at:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->updated_at }}</td>
                                    </tr>
                                     <tr>
                                        <td class="px-0 fw-semibold text-dark">Deleted at:</td>
                                        <td class="text-dark fw-medium px-0">{{ $role->deleted_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('roles.deleted') }}" class="btn btn-secondary">
                        <iconify-icon icon="mdi:arrow-left" style="vertical-align: middle;"></iconify-icon> Back
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!-- End Container Fluid -->
@endsection
