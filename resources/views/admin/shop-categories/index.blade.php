@extends('admin.layout')
@section('title', __('main.shop_categories'))
@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('main.shop_categories') }}</h4>
            <a href="{{ route('admin.shop-categories.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> {{ __('main.add') }}</a>
        </div>
    </div>
</div>
<div class="row"><div class="col-lg-8"><div class="card"><div class="card-body">
    <table class="table table-bordered table-hover mb-0">
        <thead class="table-light"><tr><th>ID</th><th>{{ __('main.name') }}</th><th>{{ __('main.sort') }}</th><th>{{ __('main.actions') }}</th></tr></thead>
        <tbody>
            @forelse($categories as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->localizedName() }}</td>
                <td>{{ $cat->sort_order }}</td>
                <td>
                    <a href="{{ route('admin.shop-categories.edit', ['shop_category' => $cat]) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.shop-categories.destroy', ['shop_category' => $cat]) }}" method="POST" style="display:inline" onsubmit="return confirm('{{ __('main.delete') }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-muted text-center py-4">{{ __('main.shop_no_items') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div></div>
@endsection
