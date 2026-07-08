@extends('admin.layout')

@section('title', __('main.edit_class'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_class') }}: {{ $class->name }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('admin.classes.update', $class) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.class_id') }}</label>
                        <input type="number" name="class_id" class="form-control @error('class_id') is-invalid @enderror" value="{{ old('class_id', $class->class_id) }}">
                        @error('class_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">{{ __('main.class_id_hint') }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.name') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $class->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.name_de') }}</label>
                                <input type="text" name="name_de" class="form-control @error('name_de') is-invalid @enderror" value="{{ old('name_de', $class->name_de) }}">
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
                                <input type="text" name="name_es" class="form-control @error('name_es') is-invalid @enderror" value="{{ old('name_es', $class->name_es) }}">
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
                                <input type="text" name="name_fr" class="form-control @error('name_fr') is-invalid @enderror" value="{{ old('name_fr', $class->name_fr) }}">
                                @error('name_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
