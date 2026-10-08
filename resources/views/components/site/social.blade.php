@props(['size' => 22])

<ul {{ $attributes->class('social') }}>
    <li>
        <a href="{{ config('kodu.social.facebook') }}" target="_blank" rel="noopener" aria-label="Facebook de KODU">
            <x-ui.icon name="facebook" :size="$size" />
        </a>
    </li>
    <li>
        <a href="{{ config('kodu.social.instagram') }}" target="_blank" rel="noopener" aria-label="Instagram de KODU">
            <x-ui.icon name="instagram" :size="$size" />
        </a>
    </li>
</ul>
