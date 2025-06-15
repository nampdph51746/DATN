@extends('layouts.admin.admin')

@section('content')

<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8 ">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sửa thành phố</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.cities.update', $city->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên thành phố</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $city->name) }}"
                                    placeholder="Nhập tên thành phố">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="country_id" class="form-label">Quốc gia</label>
                                <select name="country_id" id="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                    <option value="">-- Chọn quốc gia --</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" 
                                        {{ old('country_id', $city->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection