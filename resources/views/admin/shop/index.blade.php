@extends('admin.layout')
@section('title', __('main.shop_manage'))
@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('main.shop_manage') }}</h4>
            <a href="{{ route('admin.shop.create') }}" class="btn btn-primary">{{ __('main.add') }}</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="padding:30px 15px 8px 15px;">
                <form method="GET" action="{{ route('admin.shop.index') }}" class="d-flex align-items-center justify-content-between gap-2">
                    <select name="category" class="form-control form-control-sm" style="width:150px;height:30px;flex:0 0 auto;padding:2px 24px 2px 6px;font-size:13px;">
                        <option value="">{{ __('main.shop_all_categories') }}</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->localizedName() }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex align-items-center" style="gap:15px;">
                        <input type="text" name="search" class="form-control form-control-sm" style="width:160px;height:38px;flex:0 0 auto;" value="{{ request('search') }}" placeholder="ID или название...">
                        <button type="submit" class="btn btn-sm btn-primary" style="height:38px;">{{ __('main.search') }}</button>
                        @if(request('search'))
                        <a href="{{ route('admin.shop.index') }}" class="btn btn-sm btn-secondary">{{ __('main.clear') }}</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('main.name') }}</th>
                            <th>{{ __('main.shop_item_entry') }}</th>
                            <th>{{ __('main.shop_categories') }}</th>
                            <th>{{ __('main.shop_price') }}</th>
                            <th>{{ __('main.shop_quantity') }}</th>
                            <th style="width:70px;">{{ __('main.status') }}</th>
                            <th style="width:120px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>
                                @if($item->icon_name)
                                <img src="https://wow.zamimg.com/images/wow/icons/medium/{{ $item->icon_name }}.jpg" style="width:32px;height:32px;vertical-align:middle;margin-right:6px;border-radius:3px;">
                                @endif
                                {{ $item->name_ru ?: '-' }}
                            </td>
                            <td><a href="https://www.wowhead.com/ru/item={{ $item->item_entry }}" target="_blank" rel="noopener noreferrer">{{ $item->item_entry }}</a></td>
                            <td>{{ $item->category?->localizedName() ?? '-' }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.shop.toggle', ['shop' => $item]) }}" class="toggle-form">
                                    @csrf
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input toggle-submit" type="checkbox" {{ $item->enabled ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.shop.edit', ['shop' => $item]) }}" class="btn btn-sm btn-primary px-2"><i class="ri-pencil-line"></i></a>
                                <form action="{{ route('admin.shop.destroy', ['shop' => $item]) }}" method="POST" style="display:inline" onsubmit="return confirm('{{ __('main.delete') }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger px-2"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">{{ __('main.shop_no_items') }}</td></tr>
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
