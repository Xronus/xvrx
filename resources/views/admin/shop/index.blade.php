@extends('admin.layout')
@section('title', __('main.shop_manage'))
@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('main.shop_manage') }}</h4>
            <a href="{{ route('admin.shop.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> {{ __('main.add') }}</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.shop.index') }}" class="d-flex align-items-center gap-2">
                    <input type="text" name="search" class="form-control form-control-sm me-2" value="{{ request('search') }}" placeholder="ID или название...">
                    <select name="category" class="form-select form-select-sm w-auto me-2">
                        <option value="">{{ __('main.shop_all_categories') }}</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->localizedName() }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i> {{ __('main.search') }}
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.shop.index') }}" class="btn btn-sm btn-secondary ms-2">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('main.name') }}</th>
                            <th>{{ __('main.shop_item_entry') }}</th>
                            <th>{{ __('main.shop_categories') }}</th>
                            <th>{{ __('main.shop_price') }}</th>
                            <th>{{ __('main.shop_quantity') }}</th>
                            <th>{{ __('main.status') }}</th>
                            <th>{{ __('main.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->icon_name)
                                <img src="https://wow.zamimg.com/images/wow/icons/medium/{{ $item->icon_name }}.jpg" style="width:32px;height:32px;vertical-align:middle;margin-right:6px;border-radius:3px;">
                                @endif
                                {{ $item->name_ru ?: '-' }}
                            </td>
                            <td><a href="https://www.wowhead.com/ru/item={{ $item->item_entry }}" target="_blank" rel="noopener">{{ $item->item_entry }}</a></td>
                            <td>{{ $item->category?->localizedName() ?? '-' }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><span class="badge bg-{{ $item->enabled ? 'success' : 'secondary' }}">{{ $item->enabled ? __('main.on') : __('main.off') }}</span></td>
                            <td>
                                <a href="{{ route('admin.shop.edit', ['shop' => $item]) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.shop.destroy', ['shop' => $item]) }}" method="POST" style="display:inline" onsubmit="return confirm('{{ __('main.delete') }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">{{ __('main.shop_no_items') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($items->hasPages())
                <div class="mt-3 d-flex justify-content-center">{{ $items->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
