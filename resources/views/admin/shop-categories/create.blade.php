@extends('admin.layout')
@section('title', __('main.add'))
@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('main.add') }} {{ __('main.shop_categories') }}</h4>
            <a href="{{ route('admin.shop-categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> {{ __('main.back') }}</a>
        </div>
    </div>
</div>
<div class="row"><div class="col-lg-8"><div class="card"><div class="card-body">
    <form action="{{ route('admin.shop-categories.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6"><div class="mb-3"><label class="form-label">RU</label><input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ old('name_ru') }}" required></div></div>
            <div class="col-md-6"><div class="mb-3"><label class="form-label">EN</label><input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}"></div></div>
            <div class="col-md-6"><div class="mb-3"><label class="form-label">DE</label><input type="text" name="name_de" class="form-control" value="{{ old('name_de') }}"></div></div>
            <div class="col-md-6"><div class="mb-3"><label class="form-label">ES</label><input type="text" name="name_es" class="form-control" value="{{ old('name_es') }}"></div></div>
            <div class="col-md-6"><div class="mb-3"><label class="form-label">FR</label><input type="text" name="name_fr" class="form-control" value="{{ old('name_fr') }}"></div></div>
        </div>
        <div class="mb-3"><label class="form-label">{{ __('main.sort') }}</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0"></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> {{ __('main.save') }}</button>
    </form>
</div></div></div></div>
@endsection
