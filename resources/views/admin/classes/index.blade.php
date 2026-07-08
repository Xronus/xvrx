@extends('admin.layout')

@section('title', __('main.classes'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.classes') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">{{ __('main.add_class') }}</a>
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
                                <th style="width: 80px;">{{ __('main.class_id') }}</th>
                                <th>{{ __('main.name') }}</th>
                                <th style="width: 130px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                            <tr>
                                <td>{{ $class->class_id }}</td>
                                <td>
                                    <img src="{{ class_icon_url($class->class_id) }}" alt="" style="width:24px;height:24px;vertical-align:middle;margin-right:6px;">{{ $class->name }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_confirm') }}')">
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
                                <td colspan="3" class="text-center text-muted py-4">{{ __('main.no_classes') }}</td>
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
