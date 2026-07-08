@extends('admin.layout')

@section('title', __('main.logo_management'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.logo_management') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.upload_logo') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.logo.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('main.select_logo_file') }}</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp" required>
                        @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">{{ __('main.logo_upload_hint') }}</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-upload-line me-1"></i> {{ __('main.upload') }}
                    </button>
                </form>
            </div>
        </div>

        @if(count($logos) > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.available_logos') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($logos as $logo)
                    <div class="col-md-4 mb-3">
                        <div class="card border {{ $logo['is_current'] ? 'border-primary' : '' }}">
                            <div class="card-body text-center">
                                <img src="{{ $logo['url'] }}" alt="{{ $logo['name'] }}" class="img-fluid mb-2" style="max-height: 150px;">
                                <p class="mb-2 small text-muted">{{ $logo['name'] }}</p>
                                @if($logo['is_current'])
                                <span class="badge bg-success mb-2">{{ __('main.current_logo') }}</span>
                                @else
                                <form action="{{ route('admin.logo.set-current') }}" method="POST" class="d-inline mb-2">
                                    @csrf
                                    <input type="hidden" name="logo_path" value="{{ $logo['path'] }}">
                                    <button type="submit" class="btn btn-sm btn-primary" title="{{ __('main.set_as_current') }}" style="width:30px;padding:0;margin:0 2px;">
                                        <i class="ri-check-line"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.logo.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('main.delete_logo_confirm') }}');">
                                    @csrf
                                    <input type="hidden" name="logo_path" value="{{ $logo['path'] }}">
                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('main.delete') }}" style="width:30px;padding:0;margin:0 2px;">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="card mt-4">
            <div class="card-body text-center text-muted">
                <p>{{ __('main.no_logos_uploaded') }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.current_logo') }}</h5>
            </div>
            <div class="card-body text-center">
                @if($currentLogo)
                <img src="{{ asset($currentLogo) }}" alt="Current Logo" class="img-fluid mb-2" style="max-height: 200px;">
                <p class="text-muted small">{{ __('main.current_logo_path') }}: {{ $currentLogo }}</p>
                @else
                <p class="text-muted">{{ __('main.no_logo_set') }}</p>
                <p class="text-muted small">{{ __('main.default_logo_will_be_used') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
