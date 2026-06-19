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
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('main.total_accounts') }}</span>
                        <h4 class="mb-3">
                            <font color="green"><span class="counter-value" data-target="{{ $totalAccounts }}"></span></font>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 text-truncate">{{ __('main.total_banned') }}</span>
                        <h4 class="mb-3">
                            <font color="red"><span class="counter-value" data-target="{{ $totalBanned }}"></span></font>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 text-truncate">{{ __('main.total_premium') }}</span>
                        <h4 class="mb-3">
                            <font color="gold"><span class="counter-value" data-target="{{ $totalPremium }}"></span></font>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 text-truncate">{{ __('main.total_realms') }}</span>
                        <h4 class="mb-3">
                            <font color="blue"><span class="counter-value" data-target="{{ $totalRealms }}"></span></font>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('main.project_info') }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">{{ __('main.site_title') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_title') }} (ru)</label>
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title', $settings->title ?? '') }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_title') }} (en)</label>
                                <input class="form-control @error('title_en') is-invalid @enderror" type="text" name="title_en" value="{{ old('title_en', $settings->title_en ?? '') }}">
                                @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_title') }} (de)</label>
                                <input class="form-control @error('title_de') is-invalid @enderror" type="text" name="title_de" value="{{ old('title_de', $settings->title_de ?? '') }}">
                                @error('title_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_title') }} (es)</label>
                                <input class="form-control @error('title_es') is-invalid @enderror" type="text" name="title_es" value="{{ old('title_es', $settings->title_es ?? '') }}">
                                @error('title_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_title') }} (fr)</label>
                                <input class="form-control @error('title_fr') is-invalid @enderror" type="text" name="title_fr" value="{{ old('title_fr', $settings->title_fr ?? '') }}">
                                @error('title_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-3 mt-3">{{ __('main.site_description') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_description') }} (ru)</label>
                                <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" value="{{ old('description', $settings->description ?? '') }}">
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_description') }} (en)</label>
                                <input class="form-control @error('description_en') is-invalid @enderror" type="text" name="description_en" value="{{ old('description_en', $settings->description_en ?? '') }}">
                                @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_description') }} (de)</label>
                                <input class="form-control @error('description_de') is-invalid @enderror" type="text" name="description_de" value="{{ old('description_de', $settings->description_de ?? '') }}">
                                @error('description_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_description') }} (es)</label>
                                <input class="form-control @error('description_es') is-invalid @enderror" type="text" name="description_es" value="{{ old('description_es', $settings->description_es ?? '') }}">
                                @error('description_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.site_description') }} (fr)</label>
                                <input class="form-control @error('description_fr') is-invalid @enderror" type="text" name="description_fr" value="{{ old('description_fr', $settings->description_fr ?? '') }}">
                                @error('description_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-3 mt-3">{{ __('main.start_game_title') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_title') }} (ru)</label>
                                <input class="form-control @error('main__title') is-invalid @enderror" type="text" name="main__title" value="{{ old('main__title', $settings->main__title ?? '') }}">
                                @error('main__title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_title') }} (en)</label>
                                <input class="form-control @error('main__title_en') is-invalid @enderror" type="text" name="main__title_en" value="{{ old('main__title_en', $settings->main__title_en ?? '') }}">
                                @error('main__title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_title') }} (de)</label>
                                <input class="form-control @error('main__title_de') is-invalid @enderror" type="text" name="main__title_de" value="{{ old('main__title_de', $settings->main__title_de ?? '') }}">
                                @error('main__title_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_title') }} (es)</label>
                                <input class="form-control @error('main__title_es') is-invalid @enderror" type="text" name="main__title_es" value="{{ old('main__title_es', $settings->main__title_es ?? '') }}">
                                @error('main__title_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_title') }} (fr)</label>
                                <input class="form-control @error('main__title_fr') is-invalid @enderror" type="text" name="main__title_fr" value="{{ old('main__title_fr', $settings->main__title_fr ?? '') }}">
                                @error('main__title_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-3 mt-3">{{ __('main.start_game_description') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_description') }} (ru)</label>
                                <input class="form-control @error('main__text') is-invalid @enderror" type="text" name="main__text" value="{{ old('main__text', $settings->main__text ?? '') }}">
                                @error('main__text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_description') }} (en)</label>
                                <input class="form-control @error('main__text_en') is-invalid @enderror" type="text" name="main__text_en" value="{{ old('main__text_en', $settings->main__text_en ?? '') }}">
                                @error('main__text_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_description') }} (de)</label>
                                <input class="form-control @error('main__text_de') is-invalid @enderror" type="text" name="main__text_de" value="{{ old('main__text_de', $settings->main__text_de ?? '') }}">
                                @error('main__text_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_description') }} (es)</label>
                                <input class="form-control @error('main__text_es') is-invalid @enderror" type="text" name="main__text_es" value="{{ old('main__text_es', $settings->main__text_es ?? '') }}">
                                @error('main__text_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.start_game_description') }} (fr)</label>
                                <input class="form-control @error('main__text_fr') is-invalid @enderror" type="text" name="main__text_fr" value="{{ old('main__text_fr', $settings->main__text_fr ?? '') }}">
                                @error('main__text_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ __('main.save_settings') }}
                    </button>
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
