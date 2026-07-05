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
<div class="row"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form action="{{ route('admin.shop-categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ __('main.name') }}</label>
            <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ old('name_ru') }}" required>
            @error('name_ru')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3"><label class="form-label">{{ __('main.sort') }}</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0"></div>
        <button type="submit" class="btn btn-primary" id="save-btn" disabled><i class="fas fa-save me-1"></i> {{ __('main.save') }}</button>
    </form>
</div></div></div></div>
@push('scripts')
<script>
(function() {
    var form = document.querySelector('form');
    var btn = document.getElementById('save-btn');
    var fields = form.querySelectorAll('[required]');
    function check() { btn.disabled = !form.checkValidity(); }
    fields.forEach(function(f) { f.addEventListener('input', check); f.addEventListener('change', check); });
    check();
})();
</script>
@endpush
@endsection
