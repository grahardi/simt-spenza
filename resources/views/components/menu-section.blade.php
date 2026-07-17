@props(['title', 'items', 'theme' => 'purple'])

<div class="panel-section panel-theme-{{ $theme }}">
    <div class="panel-title">{{ $title }}</div>

    <div class="menu-grid">
        @foreach ($items as $item)
            <a href="{{ $item['href'] }}" class="menu-card">
                <span class="menu-icon bg-{{ $item['color'] ?? 'purple' }}">
                    <i class="{{ $item['icon'] }}"></i>
                </span>
                <span class="menu-title">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
