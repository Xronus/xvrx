@extends('admin.layout')

@section('title', __('main.races'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.races') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.races.create') }}" class="btn btn-primary">{{ __('main.add_race') }}</a>
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
                                <th style="width: 80px;">{{ __('main.race_id') }}</th>
                                <th>{{ __('main.name') }}</th>
                                <th style="width: 120px;">{{ __('main.faction') }}</th>
                                <th style="width: 130px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($races as $race)
                            <tr>
                                <td>{{ $race->race_id }}</td>
                                <td>{{ $race->name }}</td>
                                <td>
                                    <img src="{{ faction_icon_url($race->faction) }}" alt="{{ $race->faction_name }}" style="width:24px;height:24px;vertical-align:middle;">
                                    <span class="ms-1">{{ $race->faction_name }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.races.edit', $race) }}" class="btn btn-sm btn-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.races.destroy', $race) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_confirm') }}')">
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
                                <td colspan="4" class="text-center text-muted py-4">{{ __('main.no_races') }}</td>
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
