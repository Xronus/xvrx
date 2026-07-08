@extends('admin.layout')

@section('title', __('main.account_parser'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.account_parser') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <p class="text-uppercase fw-medium text-muted mb-1">{{ __('main.game_accounts') }}</p>
                <h4 class="my-0"><span id="total-game-accounts">{{ $totalGameAccounts }}</span></h4>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <p class="text-uppercase fw-medium text-muted mb-1">{{ __('main.website_accounts') }}</p>
                <h4 class="my-0"><span id="total-website-accounts">{{ $totalWebsiteAccounts }}</span></h4>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <p class="text-uppercase fw-medium text-muted mb-1">{{ __('main.accounts_to_import') }}</p>
                <h4 class="my-0"><span id="accounts-to-import">{{ $accountsToImport }}</span></h4>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.import_settings') }}</h5>
            </div>
            <div class="card-body">
                <form id="parser-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.batch_size') }}</label>
                                <input type="number" name="batch_size" class="form-control" value="100" min="1" max="1000" required>
                                <small class="form-text text-muted">{{ __('main.batch_size_hint') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.default_email_domain') }}</label>
                                <input type="text" name="default_email_domain" class="form-control" value="example.com" required>
                                <small class="form-text text-muted">{{ __('main.default_email_domain_hint') }}</small>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;"><button type="submit" class="btn btn-primary" id="parse-btn">{{ __('main.start_import') }}</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4" id="results-section" style="display: none;">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.import_results') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary mb-0" id="stats-processed">0</h3>
                            <p class="text-muted mb-0">{{ __('main.processed') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success mb-0" id="stats-created">0</h3>
                            <p class="text-muted mb-0">{{ __('main.created') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-warning mb-0" id="stats-skipped">0</h3>
                            <p class="text-muted mb-0">{{ __('main.skipped') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-danger mb-0" id="stats-errors">0</h3>
                            <p class="text-muted mb-0">{{ __('main.errors') }}</p>
                        </div>
                    </div>
                </div>
                <div id="errors-list" class="mt-3" style="display: none;">
                    <h6 class="text-danger">{{ __('main.errors_list') }}</h6>
                    <ul class="list-unstyled" id="errors-list-content"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('parser-form');
    const parseBtn = document.getElementById('parse-btn');
    const resultsSection = document.getElementById('results-section');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        parseBtn.disabled = true;
        parseBtn.innerHTML = '{{ __('main.importing') }}...';
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.account-parser.parse") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultsSection.style.display = 'block';
                document.getElementById('stats-processed').textContent = data.stats.processed;
                document.getElementById('stats-created').textContent = data.stats.created;
                document.getElementById('stats-skipped').textContent = data.stats.skipped;
                document.getElementById('stats-errors').textContent = data.stats.errors;
                
                // Обновляем общую статистику
                const totalWebsiteAccounts = parseInt(document.getElementById('total-website-accounts').textContent);
                document.getElementById('total-website-accounts').textContent = totalWebsiteAccounts + data.stats.created;
                const accountsToImport = parseInt(document.getElementById('accounts-to-import').textContent);
                document.getElementById('accounts-to-import').textContent = Math.max(0, accountsToImport - data.stats.created);
                
                // Показываем ошибки, если есть
                if (data.stats.errors > 0 && data.stats.errors_list && data.stats.errors_list.length > 0) {
                    const errorsList = document.getElementById('errors-list');
                    const errorsListContent = document.getElementById('errors-list-content');
                    errorsList.style.display = 'block';
                    errorsListContent.innerHTML = '';
                    data.stats.errors_list.forEach(function(error) {
                        const li = document.createElement('li');
                        li.className = 'text-danger small';
                        li.textContent = error.username + ': ' + error.error;
                        errorsListContent.appendChild(li);
                    });
                } else {
                    document.getElementById('errors-list').style.display = 'none';
                }
                
                // Показываем сообщение об успехе
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('main.import_error') }}');
        })
        .finally(() => {
            parseBtn.disabled = false;
            parseBtn.innerHTML = '{{ __('main.start_import') }}';
        });
    });
});
</script>
@endpush
