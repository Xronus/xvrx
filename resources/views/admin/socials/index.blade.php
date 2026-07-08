@extends('admin.layout')

@section('title', __('main.social_media'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.social_networks') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.socials.create') }}" class="btn btn-primary">{{ __('main.add') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">{{ __('main.id') }}</th>
                                <th>{{ __('main.name') }}</th>
                                <th>{{ __('main.link') }}</th>
                                <th>{{ __('main.css_class') }}</th>
                                <th style="width: 120px;">{{ __('main.status') }}</th>
                                <th style="width: 130px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($socials as $social)
                            <tr>
                                <td>{{ $social->id }}</td>
                                <td>{{ $social->name }}</td>
                                <td><a href="{{ $social->link }}" target="_blank">{{ Str::limit($social->link, 40) }}</a></td>
                                <td><code>{{ $social->remixIconClass() }}</code></td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.socials.toggle', $social) }}" class="toggle-form">
                                        @csrf
                                        <div class="form-check form-switch d-flex justify-content-center mb-0">
                                            <input class="form-check-input toggle-submit" type="checkbox" {{ $social->is_active ? 'checked' : '' }}>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.socials.edit', $social) }}" class="btn btn-sm btn-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.socials.destroy', $social) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ __('main.no_socials') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-submit').forEach(function(el) {
    el.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
