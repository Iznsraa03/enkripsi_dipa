@props([
    'title'   => null,
    'icon'    => null,
    'class'   => '',
    'bodyClass' => '',
])

<div class="card {{ $class }}">
    @if ($title)
    <div class="card-header">
        <div class="card-header-title">
            @if ($icon)
            <span class="material-symbols-outlined">{{ $icon }}</span>
            @endif
            {{ $title }}
        </div>
        {{ $actions ?? '' }}
    </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @isset ($footer)
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endisset
</div>
