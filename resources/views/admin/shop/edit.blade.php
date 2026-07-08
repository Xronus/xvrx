@extends('admin.layout')
@section('title', __('main.edit_shop_item'))
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_shop_item') }}: #{{ $item->item_entry }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.shop.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> {{ __('main.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.shop.update', ['shop' => $item]) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.shop_categories') }}</label>
                        <select name="subcategory_id" class="form-select @error('subcategory_id') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('subcategory_id', $item->subcategory_id) == $cat->id ? 'selected' : '' }}>{{ $cat->localizedName() }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.name') }}</label>
                        <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ old('name_ru', $item->name_ru) }}" required>
                        @error('name_ru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.shop_icon') }}</label>
                        <input type="text" name="icon_name" class="form-control @error('icon_name') is-invalid @enderror" value="{{ old('icon_name', $item->icon_name) }}" placeholder="inv_misc_questionmark" required>
                        @error('icon_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.shop_item_entry') }}</label>
                        <input type="number" name="item_entry" class="form-control @error('item_entry') is-invalid @enderror" value="{{ old('item_entry', $item->item_entry) }}" required>
                        @error('item_entry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.shop_price') }}</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $item->price) }}" min="1" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.shop_quantity') }}</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $item->quantity) }}" min="1">
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.sort') }}</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $item->sort_order) }}" min="0">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="enabled" value="1" id="enabled" {{ old('enabled', $item->enabled) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enabled">{{ __('main.enabled') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
