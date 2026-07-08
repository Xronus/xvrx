@extends('admin.layout')

@section('title', __('main.features_title'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.features_title') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.features.create') }}" class="btn btn-primary">{{ __('main.add_feature') }}</a>
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
                                <th style="width: 80px;">{{ __('main.image') }}</th>
                                <th>{{ __('main.title') }}</th>
                                <th style="width: 100px;">{{ __('main.sort') }}</th>
                                <th style="width: 100px;">{{ __('main.status') }}</th>
                                <th style="width: 150px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($features as $feature)
                            <tr>
                                <td>{{ $feature->id }}</td>
                                <td>
                                    @if($feature->image)
                                    <img src="{{ asset($feature->image) }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                </td>
                                <td>{{ $feature->localized('title') }}</td>
                                <td>{{ $feature->sort }}</td>
                                <td>
                                    @if($feature->status)
                                    <span class="badge bg-success" style="color:#fff">{{ __('main.active') }}</span>
                                    @else
                                    <span class="badge bg-secondary" style="color:#fff">{{ __('main.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-sm btn-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.features.destroy', $feature) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_feature_confirm') }}')">
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
                                <td colspan="6" class="text-center text-muted py-4">{{ __('main.no_features') }}</td>
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
