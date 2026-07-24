@extends('admin.layout')

@section('title', __('main.project_statistics'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.project_statistics') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-uppercase fw-medium text-muted mb-1">{{ __('main.total_accounts') }}</p>
                <h4 class="my-0"><span class="counter-value" data-target="{{ $totalAccounts }}"></span></h4>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-uppercase fw-medium text-muted mb-1">{{ __('main.total_banned') }}</p>
                <h4 class="my-0"><span class="counter-value" data-target="{{ $totalBanned }}"></span></h4>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-uppercase fw-medium text-muted mb-1">{{ __('main.online') }}</p>
                <h4 class="my-0"><span class="counter-value" data-target="{{ $onlineCount }}"></span></h4>
            </div>
        </div>
    </div>

</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('main.project_info') }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_title') }}</label>
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title', $settings->title ?? '') }}">
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_description') }}</label>
                                <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" value="{{ old('description', $settings->description ?? '') }}">
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_title') }}</label>
                                <input class="form-control @error('main__title') is-invalid @enderror" type="text" name="main__title" value="{{ old('main__title', $settings->main__title ?? '') }}">
                                @error('main__title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_description') }}</label>
                                <input class="form-control @error('main__text') is-invalid @enderror" type="text" name="main__text" value="{{ old('main__text', $settings->main__text ?? '') }}">
                                @error('main__text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Terms of Service --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('main.terms_of_service_text') }}</h5>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <textarea class="form-control @error('terms_text') is-invalid @enderror" name="terms_text" rows="14">{{ old('terms_text', $settings->terms_text ?? '') }}</textarea>
                                @error('terms_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">{{ __('main.html_hint') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Privacy Policy --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('main.privacy_policy_text') }}</h5>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <textarea class="form-control @error('policy_text') is-invalid @enderror" name="policy_text" rows="14">{{ old('policy_text', $settings->policy_text ?? '') }}</textarea>
                                @error('policy_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">{{ __('main.html_hint') }}</small>
                            </div>
                        </div>
                    </div>

                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-primary">{{ __('main.save_settings') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    function initCounterNumber() {
        var counter = document.querySelectorAll('.counter-value');
        var speed = 250;
        counter.forEach(function (counter_value) {
            function updateCount() {
                var target = +counter_value.getAttribute('data-target');
                var count = +counter_value.innerText;
                var inc = target / speed;
                if (inc < 1) {
                    inc = 1;
                }
                if (count < target) {
                    counter_value.innerText = (count + inc).toFixed(0);
                    setTimeout(updateCount, 1);
                } else {
                    counter_value.innerText = target;
                }
            };
            updateCount();
        });
    }

    $(document).ready(function() {
        initCounterNumber();
    });
</script>
@endpush
