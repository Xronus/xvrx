@extends('admin.layout')

@section('title', __('main.add_social'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.add_social') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.socials.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('main.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.socials.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.name') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Discord">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.link') }}</label>
                        <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link') }}" placeholder="https://discord.gg/...">
                        @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.css_class') }}</label>
                        <select name="class" class="form-control @error('class') is-invalid @enderror">
                            <option value="ri-discord-fill" {{ old('class') == 'ri-discord-fill' ? 'selected' : '' }}>Discord</option>
                            <option value="ri-telegram-fill" {{ old('class') == 'ri-telegram-fill' ? 'selected' : '' }}>Telegram</option>
                            <option value="ri-vk-fill" {{ old('class') == 'ri-vk-fill' ? 'selected' : '' }}>VK</option>
                            <option value="ri-youtube-fill" {{ old('class') == 'ri-youtube-fill' ? 'selected' : '' }}>YouTube</option>
                            <option value="ri-facebook-fill" {{ old('class') == 'ri-facebook-fill' ? 'selected' : '' }}>Facebook</option>
                            <option value="ri-twitter-x-fill" {{ old('class') == 'ri-twitter-x-fill' ? 'selected' : '' }}>X / Twitter</option>
                        </select>
                        @error('class')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('main.show_on_site') }}</label>
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
