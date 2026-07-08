@extends('admin.layout')

@section('title', __('main.add_feature'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.add_feature') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> {{ __('main.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.features.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.title') }}</label>
                        <input type="text" name="title_ru" class="form-control @error('title_ru') is-invalid @enderror" value="{{ old('title_ru') }}" required>
                        @error('title_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.description') }}</label>
                        <textarea name="description_ru" class="form-control @error('description_ru') is-invalid @enderror" rows="5" required>{{ old('description_ru') }}</textarea>
                        @error('description_ru')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.image') }}</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="image-preview-wrap" style="display: none;">
                        <img id="image-preview" src="" alt="" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.sort') }}</label>
                                <input type="number" name="sort" class="form-control @error('sort') is-invalid @enderror" value="{{ old('sort', 0) }}" min="0">
                                @error('sort')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 pt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">{{ __('main.enabled') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('input[name="image"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('image-preview').src = ev.target.result;
                document.getElementById('image-preview-wrap').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
