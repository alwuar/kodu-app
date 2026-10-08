@props(['tags' => [], 'variant' => 'solid'])

@if ($tags)
    <ul {{ $attributes->class(['tags', 'tags--'.$variant]) }}>
        @foreach ($tags as $tag)
            <li class="tag">{{ $tag }}</li>
        @endforeach
    </ul>
@endif
