@extends('admin.layout')

@section('title', __('main.shop_item_types'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.shop_item_types') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.shop-item-types.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> {{ __('main.add_type') }}
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
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>{{ __('main.name') }}</th>
                                <th style="width: 120px;">{{ __('main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->name_ru }}</td>
                                <td>
                                    <a href="{{ route('admin.shop-item-types.edit', $type) }}" class="btn btn-sm btn-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('admin.shop-item-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('main.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">{{ __('main.no_types') }}</td>
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
