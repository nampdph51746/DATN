@extends('layouts.admin.admin')

@section('content')
    <!-- Start Container Fluid -->
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Roles Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="role-name" class="form-label">Role Name</label>
                                        <input type="text" id="role-name" name="name" class="form-control"
                                            placeholder="Role name">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label" name="description">Roles
                                            Description</label>
                                        <textarea id="role-description" name="description" class="form-control"
                                            placeholder="Role description" rows="5" style="resize: none;"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Create Role</button>
                                </form>
                            </div>

                            {{-- <div class="col-lg-6">
                                <p>User Status </p>
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault1" checked="">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            In Active
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- End Container Fluid -->

@endsection