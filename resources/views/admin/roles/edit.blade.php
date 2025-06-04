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
                              <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="role-name" class="form-label">Role Name</label>
                                        <input type="text" id="role-name" name="name" class="form-control"
                                            value="{{ old('name', $role->name) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label" name="description">Roles
                                            Description</label>
                                        <textarea id="role-description" name="description" class="form-control" rows="5" style="resize: none;">{{ old('description', $role->description) }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Create Role</button>
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