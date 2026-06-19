@extends('admin.layout')

@section('title', __('main.news'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.news') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ __('main.add_news') }}
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
                                <th style="width: 80px;">{{ __('main.photo') }}</th>
                                <th>{{ __('main.date') }}</th>
                                <th>{{ __('main.title') }}</th>
                                <th style="width: 150px;">{{ __('main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->images)
                                    <img src="{{ asset($item->images) }}" alt="" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                </td>
                                <td>{{ $item->date }}</td>
                                <td>{{ Str::limit($item->text, 80) }}</td>
                                <td>
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.news.destroy', $item) }}" style="display: inline-block;" onsubmit="return confirm('{{ __('main.delete_news_confirm') }}')">
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
                                <td colspan="5" class="text-center text-muted py-4">{{ __('main.no_news') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($news->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $news->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
