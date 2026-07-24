@extends('admin.layout')

@section('title', __('main.edit_type'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_type') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.shop-item-types.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> {{ __('main.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.shop-item-types.update', $shop_item_type) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">{{ __('main.name') }}</label>
                        <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ old('name_ru', $shop_item_type->name_ru) }}" required>
                        @error('name_ru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
