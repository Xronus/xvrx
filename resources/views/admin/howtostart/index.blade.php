@extends('admin.layout')

@section('title', __('main.how_to_start'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.hts_settings') }}</h4>
        </div>
    </div>
</div>

<form action="{{ route('admin.howtostart.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('main.download_links') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('main.client_size_label') }}</label>
                        <input type="text" name="client_size" class="form-control" value="{{ old('client_size', $hts->client_size ?? '25.6 GB') }}" placeholder="25.6 GB">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('main.source') }}</th>
                                    <th>{{ __('main.link') }}</th>
                                    <th style="width: 100px;">{{ __('main.show') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fab fa-google-drive me-1"></i> Google Drive</td>
                                    <td><input type="text" name="google_drive_url" class="form-control form-control-sm" value="{{ old('google_drive_url', $hts->google_drive_url ?? '') }}" placeholder="https://drive.google.com/..."></td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="google_drive_active" value="1" {{ old('google_drive_active', $hts->google_drive_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-cloud me-1"></i> Yandex Disk</td>
                                    <td><input type="text" name="yandex_disk_url" class="form-control form-control-sm" value="{{ old('yandex_disk_url', $hts->yandex_disk_url ?? '') }}" placeholder="https://disk.yandex.ru/..."></td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="yandex_disk_active" value="1" {{ old('yandex_disk_active', $hts->yandex_disk_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-envelope me-1"></i> FileMail</td>
                                    <td><input type="text" name="filemail_url" class="form-control form-control-sm" value="{{ old('filemail_url', $hts->filemail_url ?? '') }}" placeholder="https://filemail.com/..."></td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="filemail_active" value="1" {{ old('filemail_active', $hts->filemail_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-cloud-download-alt me-1"></i> MegaNZ</td>
                                    <td><input type="text" name="mega_url" class="form-control form-control-sm" value="{{ old('mega_url', $hts->mega_url ?? '') }}" placeholder="https://mega.nz/..."></td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="mega_active" value="1" {{ old('mega_active', $hts->mega_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-magnet me-1"></i> Torrent File</td>
                                    <td><input type="text" name="torrent_url" class="form-control form-control-sm" value="{{ old('torrent_url', $hts->torrent_url ?? '') }}" placeholder="https://...torrent"></td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="torrent_active" value="1" {{ old('torrent_active', $hts->torrent_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('main.launcher') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.button_text') }} (ru)</label>
                                <input type="text" name="launcher_text_ru" class="form-control" value="{{ old('launcher_text_ru', $hts->launcher_text_ru ?? '') }}">
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.button_text') }} (en)</label>
                                <input type="text" name="launcher_text_en" class="form-control" value="{{ old('launcher_text_en', $hts->launcher_text_en ?? '') }}">
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.button_text') }} (de)</label>
                                <input type="text" name="launcher_text_de" class="form-control" value="{{ old('launcher_text_de', $hts->launcher_text_de ?? '') }}">
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.button_text') }} (es)</label>
                                <input type="text" name="launcher_text_es" class="form-control" value="{{ old('launcher_text_es', $hts->launcher_text_es ?? '') }}">
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.button_text') }} (fr)</label>
                                <input type="text" name="launcher_text_fr" class="form-control" value="{{ old('launcher_text_fr', $hts->launcher_text_fr ?? '') }}">
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('main.launcher_link') }}</label>
                        <input type="text" name="launcher_url" class="form-control" value="{{ old('launcher_url', $hts->launcher_url ?? '') }}" placeholder="https://...">
                    </div>
                    <div class="row">
                        @if(in_array('ru', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.launcher_description') }} (ru)</label>
                                <textarea name="launcher_description_ru" class="form-control" rows="3">{{ old('launcher_description_ru', $hts->launcher_description_ru ?? '') }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('en', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.launcher_description') }} (en)</label>
                                <textarea name="launcher_description_en" class="form-control" rows="3">{{ old('launcher_description_en', $hts->launcher_description_en ?? '') }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('de', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.launcher_description') }} (de)</label>
                                <textarea name="launcher_description_de" class="form-control" rows="3">{{ old('launcher_description_de', $hts->launcher_description_de ?? '') }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('es', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.launcher_description') }} (es)</label>
                                <textarea name="launcher_description_es" class="form-control" rows="3">{{ old('launcher_description_es', $hts->launcher_description_es ?? '') }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(in_array('fr', $enabledLangs))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.launcher_description') }} (fr)</label>
                                <textarea name="launcher_description_fr" class="form-control" rows="3">{{ old('launcher_description_fr', $hts->launcher_description_fr ?? '') }}</textarea>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('main.system_requirements') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('main.component') }}</th>
                                    <th>{{ __('main.minimum') }}</th>
                                    <th>{{ __('main.recommended') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('main.storage_disk') }}</td>
                                    <td><input type="text" name="req_storage_min" class="form-control form-control-sm" value="{{ old('req_storage_min', $hts->req_storage_min ?? '27gb') }}"></td>
                                    <td><input type="text" name="req_storage_rec" class="form-control form-control-sm" value="{{ old('req_storage_rec', $hts->req_storage_rec ?? '30gb') }}"></td>
                                </tr>
                                <tr>
                                    <td>Windows</td>
                                    <td><input type="text" name="req_windows_min" class="form-control form-control-sm" value="{{ old('req_windows_min', $hts->req_windows_min ?? 'Windows 7') }}"></td>
                                    <td><input type="text" name="req_windows_rec" class="form-control form-control-sm" value="{{ old('req_windows_rec', $hts->req_windows_rec ?? 'Windows 10') }}"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('main.ram') }}</td>
                                    <td><input type="text" name="req_ram_min" class="form-control form-control-sm" value="{{ old('req_ram_min', $hts->req_ram_min ?? '2gb') }}"></td>
                                    <td><input type="text" name="req_ram_rec" class="form-control form-control-sm" value="{{ old('req_ram_rec', $hts->req_ram_rec ?? '6gb') }}"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('main.video_card') }}</td>
                                    <td><input type="text" name="req_gpu_min" class="form-control form-control-sm" value="{{ old('req_gpu_min', $hts->req_gpu_min ?? '256mb') }}"></td>
                                    <td><input type="text" name="req_gpu_rec" class="form-control form-control-sm" value="{{ old('req_gpu_rec', $hts->req_gpu_rec ?? '1024mb') }}"></td>
                                </tr>
                                <tr>
                                    <td>{{ __('main.internet') }}</td>
                                    <td><input type="text" name="req_internet_min" class="form-control form-control-sm" value="{{ old('req_internet_min', $hts->req_internet_min ?? '') }}"></td>
                                    <td><input type="text" name="req_internet_rec" class="form-control form-control-sm" value="{{ old('req_internet_rec', $hts->req_internet_rec ?? '') }}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-save me-1"></i> {{ __('main.save_all_settings') }}
                    </button>
                    <p class="text-muted small mb-0">{{ __('main.all_fields_hint') }}</p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
