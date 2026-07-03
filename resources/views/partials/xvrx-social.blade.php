@if(($socialLinks ?? collect())->isNotEmpty())
    <aside class="social-rail" aria-label="Соцсети">
        <div class="social-rail-line" aria-hidden="true"></div>
        <nav class="social-rail-list">
            @foreach($socialLinks as $social)
                <a class="social-rail-link" href="{{ $social->link }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social->name }}" title="{{ $social->name }}">
                    <i class="{{ $social->remixIconClass() }}" aria-hidden="true"></i>
                </a>
            @endforeach
        </nav>
        <div class="social-rail-line" aria-hidden="true"></div>
    </aside>
@endif
