@extends('admin.layout')

@section('title', __('main.social_media'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.social_networks') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.socials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ __('main.add') }}
                </a>
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
                                <th style="width: 200px;">{{ __('main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($socials as $social)
                            <tr>
                                <td>{{ $social->id }}</td>
                                <td>{{ $social->name }}</td>
                                <td><a href="{{ $social->link }}" target="_blank">{{ Str::limit($social->link, 40) }}</a></td>
                                <td><code>{{ $social->class }}</code></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.socials.toggle', $social) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $social->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $social->is_active ? __('main.on') : __('main.off') }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.socials.edit', $social) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.socials.destroy', $social) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
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

                <div class="mt-4">
                    <h6>{{ __('main.available_css') }}</h6>
                    <ul class="list-unstyled">
                        <li><code>soc__link _icon-discord</code> — Discord</li>
                        <li><code>soc__link _icon-telegram</code> — Telegram</li>
                        <li><code>soc__link _icon-vk</code> — VK</li>
                        <li><code>soc__link _icon-youtube</code> — YouTube</li>
                        <li><code>soc__link _icon-facebook</code> — Facebook</li>
                        <li><code>soc__link _icon-twitter</code> — Twitter</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
