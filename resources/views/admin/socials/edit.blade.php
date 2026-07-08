@extends('admin.layout')

@section('title', __('main.edit'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit') }}: {{ $social->name }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.socials.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.socials.update', $social) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.name') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $social->name) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.link') }}</label>
                        <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $social->link) }}">
                        @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.social_network') }}</label>
                        <select name="class" class="form-control @error('class') is-invalid @enderror">
                            @php($currentIcon = old('class', $social->remixIconClass()))
                            <option value="ri-discord-fill" {{ $currentIcon == 'ri-discord-fill' ? 'selected' : '' }}>Discord</option>
                            <option value="ri-telegram-fill" {{ $currentIcon == 'ri-telegram-fill' ? 'selected' : '' }}>Telegram</option>
                            <option value="ri-vk-fill" {{ $currentIcon == 'ri-vk-fill' ? 'selected' : '' }}>VK</option>
                            <option value="ri-youtube-fill" {{ $currentIcon == 'ri-youtube-fill' ? 'selected' : '' }}>YouTube</option>
                            <option value="ri-facebook-fill" {{ $currentIcon == 'ri-facebook-fill' ? 'selected' : '' }}>Facebook</option>
                            <option value="ri-twitter-x-fill" {{ $currentIcon == 'ri-twitter-x-fill' ? 'selected' : '' }}>X / Twitter</option>
                        </select>
                        @error('class')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $social->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('main.show_on_site') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
