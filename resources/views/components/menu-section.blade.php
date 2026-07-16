@props(['title', 'items'])

<div class="mb-4">
    <div class="alert alert-info text-center fw-bold shadow-sm mb-3 py-2">{{ $title }}</div>

    <div class="menu-grid">
        @foreach ($items as $item)
            <a href="{{ $item['href'] }}" class="menu-card">
                <i class="{{ $item['icon'] }}"></i>
                <span class="menu-title">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
