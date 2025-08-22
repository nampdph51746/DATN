@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8 ">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm quốc gia</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.countries.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên quốc gia</label>
                                <input type="text" name="name" id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    placeholder="Xin điền tên quốc gia" 
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="code" class="form-label">Mã quốc gia</label>
                                <input type="text" name="code" id="code" 
                                    class="form-control @error('code') is-invalid @enderror" 
                                    placeholder="Xin điền mã quốc gia" 
                                    value="{{ old('code') }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection