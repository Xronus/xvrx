@extends('admin.layout')

@section('title', __('main.edit_news'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_news') }} #{{ $news->id }}</h4>
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
                <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">{{ __('main.title') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.news_title_ru') }}</label>
                                <input type="text" name="text" class="form-control @error('text') is-invalid @enderror" value="{{ old('text', $news->text) }}">
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
                                <input type="text" name="text_en" class="form-control @error('text_en') is-invalid @enderror" value="{{ old('text_en', $news->text_en) }}">
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
                                <input type="text" name="text_de" class="form-control @error('text_de') is-invalid @enderror" value="{{ old('text_de', $news->text_de) }}">
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
                                <input type="text" name="text_es" class="form-control @error('text_es') is-invalid @enderror" value="{{ old('text_es', $news->text_es) }}">
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
                                <input type="text" name="text_fr" class="form-control @error('text_fr') is-invalid @enderror" value="{{ old('text_fr', $news->text_fr) }}">
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
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $news->content) }}</textarea>
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
                                <textarea name="content_en" class="form-control @error('content_en') is-invalid @enderror" rows="10">{{ old('content_en', $news->content_en) }}</textarea>
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
                                <textarea name="content_de" class="form-control @error('content_de') is-invalid @enderror" rows="10">{{ old('content_de', $news->content_de) }}</textarea>
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
                                <textarea name="content_es" class="form-control @error('content_es') is-invalid @enderror" rows="10">{{ old('content_es', $news->content_es) }}</textarea>
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
                                <textarea name="content_fr" class="form-control @error('content_fr') is-invalid @enderror" rows="10">{{ old('content_fr', $news->content_fr) }}</textarea>
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
                        <small class="text-muted">{{ __('main.keep_current_image') }}</small>
                    </div>

                    @if($news->images)
                    <div class="mb-3">
                        <label class="form-label">{{ __('main.current_image') }}</label>
                        <div>
                            <img src="{{ asset($news->images) }}" alt="" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label text-muted">{{ __('main.creation_date') }} {{ $news->date }}</label>
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
