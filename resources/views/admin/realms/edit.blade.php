@extends('admin.layout')

@section('title', __('main.edit_realm'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_realm') }}: {{ $realm->name }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.realms.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.realms.update', $realm) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">{{ __('main.name') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name') }}</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $realm->name) }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_de') }}</label>
                                <input type="text" name="name_de" class="form-control @error('name_de') is-invalid @enderror" value="{{ old('name_de', $realm->name_de) }}">
                                @error('name_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_es') }}</label>
                                <input type="text" name="name_es" class="form-control @error('name_es') is-invalid @enderror" value="{{ old('name_es', $realm->name_es) }}">
                                @error('name_es')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_fr') }}</label>
                                <input type="text" name="name_fr" class="form-control @error('name_fr') is-invalid @enderror" value="{{ old('name_fr', $realm->name_fr) }}">
                                @error('name_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.rate') }}</label>
                                <input type="text" name="rate" class="form-control @error('rate') is-invalid @enderror" value="{{ old('rate', $realm->rate) }}">
                                @error('rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.version') }}</label>
                                <select name="version" class="form-control">
                                    <option value="lich" {{ old('version', $realm->version) == 'lich' ? 'selected' : '' }}>WotLK (3.3.5)</option>
                                    <option value="legion" {{ old('version', $realm->version) == 'legion' ? 'selected' : '' }}>Legion (7.3.5)</option>
                                    <option value="bfa" {{ old('version', $realm->version) == 'bfa' ? 'selected' : '' }}>BFA (8.3)</option>
                                    <option value="sl" {{ old('version', $realm->version) == 'sl' ? 'selected' : '' }}>Shadowlands</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.full_name') }}</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $realm->full_name) }}" placeholder="xVRx">
                        <small class="text-muted">{{ __('main.full_name_hint') }}</small>
                    </div>

                    <h5 class="mb-3">{{ __('main.description') }}</h5>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description') }}</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $realm->description) }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_de') }}</label>
                                <textarea name="description_de" class="form-control" rows="3">{{ old('description_de', $realm->description_de) }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_es') }}</label>
                                <textarea name="description_es" class="form-control" rows="3">{{ old('description_es', $realm->description_es) }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.description_fr') }}</label>
                                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr', $realm->description_fr) }}</textarea>
                            </div>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-3">{{ __('main.rates') }}</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.professions') }}</label>
                                <input type="text" name="proffesion" class="form-control" value="{{ old('proffesion', $realm->proffesion) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.gold') }}</label>
                                <input type="text" name="gold" class="form-control" value="{{ old('gold', $realm->gold) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.reputation') }}</label>
                                <input type="text" name="rep" class="form-control" value="{{ old('rep', $realm->rep) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.loot') }}</label>
                                <input type="text" name="loot" class="form-control" value="{{ old('loot', $realm->loot) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.honor_points') }}</label>
                                <input type="text" name="honor" class="form-control" value="{{ old('honor', $realm->honor) }}">
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">{{ __('main.realm_link') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.link_url') }}</label>
                                <input type="text" name="link_url" class="form-control" value="{{ old('link_url', $realm->link_url) }}" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.link_text') }}</label>
                                <input type="text" name="link_text" class="form-control" value="{{ old('link_text', $realm->link_text) }}" placeholder="{{ __('main.start_playing') }}">
                            </div>
                        </div>
                    </div>

                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
