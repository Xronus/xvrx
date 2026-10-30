@extends('admin.layout')

@section('title', __('main.add_type'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.add_type') }}</h4>
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
                <form action="{{ route('admin.shop-item-types.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name_ru" class="form-label">{{ __('main.type') }}</label>
                        <select id="name_ru" name="name_ru" class="form-select @error('name_ru') is-invalid @enderror" aria-describedby="name_ru_help" required>
                            <option value="">{{ __('main.select_type') }}</option>
                            @foreach($typeOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('name_ru') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('name_ru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="name_ru_help" class="form-text">{{ __('main.shop_item_type_help') }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
