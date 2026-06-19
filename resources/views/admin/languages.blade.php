@extends('admin.layout')

@section('title', __('main.languages_page_title'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.languages_page_title') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.site_languages') }}</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">{{ __('main.languages_description') }}</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">{{ __('main.language_code') }}</th>
                                <th>{{ __('main.language_name') }}</th>
                                <th>{{ __('main.language_native') }}</th>
                                <th style="width: 120px;">{{ __('main.language_active') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($languages as $lang)
                            <tr>
                                <td><strong>{{ strtoupper($lang->code) }}</strong></td>
                                <td>{{ $lang->name }}</td>
                                <td>{{ $lang->native_name }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input lang-toggle" type="checkbox" data-code="{{ $lang->code }}" {{ $lang->is_active ? 'checked' : '' }} id="lang-{{ $lang->code }}">
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.lang-toggle').on('change', function() {
            var code = $(this).data('code');
            var isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("admin.languages.toggle") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: code,
                    is_active: isActive
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('{{ __("main.lang_update_error") }}');
                }
            });
        });
    });
</script>
@endpush
