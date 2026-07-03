@extends('admin.layout')

@section('title', __('main.mail_settings'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.mail_settings') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ __('main.smtp_status') }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th>{{ __('main.status') }}</th>
                                <td>
                                    @if($smtp['configured'])
                                        <span class="badge bg-success">{{ __('main.configured') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('main.not_configured') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('main.mailer') }}</th>
                                <td><code>{{ $smtp['mailer'] ?: '—' }}</code></td>
                            </tr>
                            <tr>
                                <th>{{ __('main.server') }}</th>
                                <td><code>{{ $smtp['host'] ?: '—' }}</code></td>
                            </tr>
                            <tr>
                                <th>{{ __('main.port') }}</th>
                                <td><code>{{ $smtp['port'] ?: '—' }}</code></td>
                            </tr>
                            <tr>
                                <th>{{ __('main.login_field') }}</th>
                                <td><code>{{ $smtp['username'] ?: '—' }}</code></td>
                            </tr>
                            <tr>
                                <th>{{ __('main.password') }}</th>
                                <td>{{ $smtp['password_configured'] ? __('main.configured_hidden') : __('main.not_configured') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('main.from_address') }}</th>
                                <td><code>{{ $smtp['from_address'] ?: '—' }}</code></td>
                            </tr>
                            <tr>
                                <th>{{ __('main.encryption') }}</th>
                                <td><code>{{ $smtp['encryption'] ?: '—' }}</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-3 mb-0">{{ __('main.mail_env_hint') }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ __('main.password_reset_mail') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mail.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="mail_password_reset_enabled" value="1" id="mail_password_reset_enabled" {{ old('mail_password_reset_enabled', $settings->mail_password_reset_enabled ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mail_password_reset_enabled">{{ __('main.enable_password_reset_mail') }}</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.from_name') }}</label>
                        <input type="text" name="mail_from_name" class="form-control @error('mail_from_name') is-invalid @enderror" value="{{ old('mail_from_name', $settings->mail_from_name ?? config('app.name')) }}">
                        @error('mail_from_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.email_subject') }}</label>
                        <input type="text" name="mail_reset_subject" class="form-control @error('mail_reset_subject') is-invalid @enderror" value="{{ old('mail_reset_subject', $settings->mail_reset_subject ?? __('main.reset_password')) }}">
                        @error('mail_reset_subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.email_body') }}</label>
                        <textarea name="mail_reset_body" rows="7" class="form-control @error('mail_reset_body') is-invalid @enderror">{{ old('mail_reset_body', $settings->mail_reset_body ?? __('main.reset_email_body')) }}</textarea>
                        <div class="form-text">{{ __('main.email_body_hint') }}</div>
                        @error('mail_reset_body')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
