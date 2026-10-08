@php
    $contact = config('kodu.contact');

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/').'#organizacion',
                'name' => config('kodu.legal_name'),
                'url' => url('/'),
                'logo' => asset(config('kodu.logo')),
                'email' => $contact['email'],
                'telephone' => $contact['phone_e164'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Col. Bojórquez',
                    'addressLocality' => $contact['locality'],
                    'addressRegion' => $contact['region'],
                    'addressCountry' => $contact['country'],
                ],
                'areaServed' => config('kodu.coverage'),
                'sameAs' => array_values(config('kodu.social')),
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/').'#sitio',
                'url' => url('/'),
                'name' => config('kodu.legal_name'),
                'inLanguage' => 'es-MX',
                'publisher' => ['@id' => url('/').'#organizacion'],
            ],
        ],
    ];
@endphp
<x-site.json-ld :data="$schema" />
