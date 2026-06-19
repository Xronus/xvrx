@extends('admin.layout')

@section('title', __('main.edit'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit') }}: {{ $feature->localized('title') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('main.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.features.update', $feature) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">{{ __('main.title') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_ru') }}</label>
                                <input type="text" name="title_ru" class="form-control @error('title_ru') is-invalid @enderror" value="{{ old('title_ru', $feature->title_ru) }}" required>
                                @error('title_ru')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_en') }}</label>
                                <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $feature->title_en) }}">
                                @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_de') }}</label>
                                <input type="text" name="title_de" class="form-control @error('title_de') is-invalid @enderror" value="{{ old('title_de', $feature->title_de) }}">
                                @error('title_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_es') }}</label>
                                <input type="text" name="title_es" class="form-control @error('title_es') is-invalid @enderror" value="{{ old('title_es', $feature->title_es) }}">
                                @error('title_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_fr') }}</label>
                                <input type="text" name="title_fr" class="form-control @error('title_fr') is-invalid @enderror" value="{{ old('title_fr', $feature->title_fr) }}">
                                @error('title_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-3">{{ __('main.description') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_ru') }}</label>
                                <textarea name="description_ru" class="form-control @error('description_ru') is-invalid @enderror" rows="5" required>{{ old('description_ru', $feature->description_ru) }}</textarea>
                                @error('description_ru')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_en') }}</label>
                                <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="5">{{ old('description_en', $feature->description_en) }}</textarea>
                                @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_de') }}</label>
                                <textarea name="description_de" class="form-control @error('description_de') is-invalid @enderror" rows="5">{{ old('description_de', $feature->description_de) }}</textarea>
                                @error('description_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_es') }}</label>
                                <textarea name="description_es" class="form-control @error('description_es') is-invalid @enderror" rows="5">{{ old('description_es', $feature->description_es) }}</textarea>
                                @error('description_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_fr') }}</label>
                                <textarea name="description_fr" class="form-control @error('description_fr') is-invalid @enderror" rows="5">{{ old('description_fr', $feature->description_fr) }}</textarea>
                                @error('description_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.image') }}</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">{{ __('main.keep_current_image') }}</small>
                    </div>

                    @if($feature->image)
                    <div class="mb-3">
                        <label class="form-label">{{ __('main.current_image') }}</label>
                        <div>
                            <img src="{{ asset($feature->image) }}" alt="" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3" id="image-preview-wrap" style="display: none;">
                        <label class="form-label">{{ __('main.new_image') }}</label>
                        <img id="image-preview" src="" alt="" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.sort') }}</label>
                                <input type="number" name="sort" class="form-control" value="{{ old('sort', $feature->sort) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 pt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $feature->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">{{ __('main.enabled') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ __('main.save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('input[name="image"]').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('image-preview').src = ev.target.result;
                document.getElementById('image-preview-wrap').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
