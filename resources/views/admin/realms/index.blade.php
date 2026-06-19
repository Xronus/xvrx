@extends('admin.layout')

@section('title', __('main.realms'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.realms') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.realms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ __('main.add_realm') }}
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
                                <th>{{ __('main.name_ru') }}</th>
                                <th>{{ __('main.rate') }}</th>
                                <th>{{ __('main.version') }}</th>
                                <th style="width: 150px;">{{ __('main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($realms as $realm)
                            <tr>
                                <td>{{ $realm->id }}</td>
                                <td>{{ $realm->name }}</td>
                                <td>{{ $realm->rate }}</td>
                                <td>{{ $realm->version }}</td>
                                <td>
                                    <a href="{{ route('admin.realms.edit', $realm) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.realms.destroy', $realm) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_realm_confirm') }}')">
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
                                <td colspan="5" class="text-center text-muted py-4">{{ __('main.no_realms') }}</td>
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
