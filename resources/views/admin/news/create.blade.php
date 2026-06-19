@extends('admin.layout')

@section('title', __('main.add_news'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.add_news') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="mb-3">{{ __('main.title') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_title_ru') }}</label>
                                <input type="text" name="text" class="form-control @error('text') is-invalid @enderror" value="{{ old('text') }}" placeholder="{{ __('main.news_title_placeholder') }}">
                                @error('text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_title_en') }}</label>
                                <input type="text" name="text_en" class="form-control @error('text_en') is-invalid @enderror" value="{{ old('text_en') }}" placeholder="News title">
                                @error('text_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_title_de') }}</label>
                                <input type="text" name="text_de" class="form-control @error('text_de') is-invalid @enderror" value="{{ old('text_de') }}" placeholder="Nachrichtentitel">
                                @error('text_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_title_es') }}</label>
                                <input type="text" name="text_es" class="form-control @error('text_es') is-invalid @enderror" value="{{ old('text_es') }}" placeholder="Título de la noticia">
                                @error('text_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_title_fr') }}</label>
                                <input type="text" name="text_fr" class="form-control @error('text_fr') is-invalid @enderror" value="{{ old('text_fr') }}" placeholder="Titre de l'actualité">
                                @error('text_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-3">{{ __('main.content') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_content_ru') }}</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="10" placeholder="{{ __('main.news_content_placeholder') }}">{{ old('content') }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_content_en') }}</label>
                                <textarea name="content_en" class="form-control @error('content_en') is-invalid @enderror" rows="10" placeholder="Full news text in English">{{ old('content_en') }}</textarea>
                                @error('content_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_content_de') }}</label>
                                <textarea name="content_de" class="form-control @error('content_de') is-invalid @enderror" rows="10" placeholder="Vollständiger Nachrichtentext">{{ old('content_de') }}</textarea>
                                @error('content_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_content_es') }}</label>
                                <textarea name="content_es" class="form-control @error('content_es') is-invalid @enderror" rows="10" placeholder="Texto completo de la noticia">{{ old('content_es') }}</textarea>
                                @error('content_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_content_fr') }}</label>
                                <textarea name="content_fr" class="form-control @error('content_fr') is-invalid @enderror" rows="10" placeholder="Texte complet de l'actualité">{{ old('content_fr') }}</textarea>
                                @error('content_fr')
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
                        <small class="text-muted">{{ __('main.max_5mb') }}</small>
                    </div>

                    <div class="mb-3" id="image-preview-wrap" style="display: none;">
                        <img id="image-preview" src="" alt="" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
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
