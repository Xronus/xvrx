@extends('admin.layout')

@section('title', __('main.edit_news'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_news') }} #{{ $news->id }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.news_title') }}</label>
                        <input type="text" name="text" class="form-control @error('text') is-invalid @enderror" value="{{ old('text', $news->text) }}">
                        @error('text')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.news_content') }}</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $news->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
