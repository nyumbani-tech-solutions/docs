@if ($url())
    <a href="{{ $url() }}">{{ $slot }}</a>
@else
    {{ $slot }}
@endif
