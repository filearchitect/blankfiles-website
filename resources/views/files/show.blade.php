<x-guest-layout>
    <x-slot name="title">Blank .{{ $file['type'] }} file for {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }} | Blank Files</x-slot>
    <x-slot name="meta">
        <meta name="description"
            content="Download a free minimal valid blank .{{ $file['type'] }} file{{ $file['package'] ? ' (zip package)' : '' }} for {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }} upload testing, parsing, and automation workflows.">
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:title" content="Blank .{{ $file['type'] }} file for {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }}">
        <meta property="og:description"
            content="Download a minimal valid blank .{{ $file['type'] }} file for reliable upload testing and parser validation workflows.">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
        @php
            $fileJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'Dataset',
                'name' => 'Blank .' . $file['type'] . ' file',
                'description' => 'Minimal valid blank .' . $file['type'] . ' file for ' . Str::ucfirst(str_replace('-', ' ', $file['category'])) . ' upload testing, parser validation, and automation workflows.',
                'url' => url()->current(),
                'keywords' => ['blank file', $file['type'], $file['category']],
                'distribution' => [
                    '@type' => 'DataDownload',
                    'contentUrl' => route('files.download', ['category' => $file['category'], 'type' => $file['type']]),
                    'encodingFormat' => $file['package'] ? 'application/zip' : 'application/octet-stream',
                ],
                'sameAs' => $file['url'],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($fileJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    </x-slot>
    <div class="mx-auto px-4 lg:px-8">
        <nav class="mb-8 border-b border-gray-200 pb-8 pt-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('home') }}" class="hover:text-gray-700">Home</a></li>
                <li class="mx-2">/</li>
                <li><a href="{{ route('home') }}#{{ $file['category'] }}" class="hover:text-gray-700">{{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }}</a></li>
                <li class="mx-2">/</li>
                <li aria-current="page" class="text-gray-700">.{{ $file['type'] }}</li>
            </ol>
        </nav>

        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row">
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-gray-900">
                    <span class="rounded-lg border border-gray-200 bg-gray-100 px-2 py-1">.{{ $file['type'] }}</span> blank file
                </h1>
                <p class="mt-3 text-lg text-gray-600">
                    Download a fresh {{ $file['type'] }} blank file{{ $file['package'] ? ' package' : '' }} for
                    {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }}.
                </p>
                <div class="mt-8">
                    <a href="{{ route('files.download', ['category' => $file['category'], 'type' => $file['type']]) }}"
                        download="blank.{{ $file['type'] }}{{ $file['package'] ? '.zip' : '' }}"
                        class="inline-flex items-center rounded bg-gray-900 px-5 py-3 font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        rel="nofollow noopener">
                        Download <span class="ml-1 font-bold">blank.{{ $file['type'] }}{{ $file['package'] ? ' (zip)' : '' }}</span>
                    </a>
                </div>
            </div>

            <section class="sm:flex-row sm:items-center">
                <div class="space-y-1 text-gray-700">
                    <p><span class="font-medium">Category:</span> {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }}</p>
                    <p><span class="font-medium">Type:</span> .{{ $file['type'] }}</p>
                    @if ($file['package'])
                        <p><span class="font-medium">Package:</span> Zipped archive (.zip)</p>
                    @endif
                </div>
            </section>
        </div>

        @if ($relatedInCategory->isNotEmpty())
            <section class="mt-8 border-t border-gray-200 pt-8">
                <h2 class="text-xl font-semibold text-gray-900">More {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }} blank files</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($relatedInCategory as $related)
                        <a href="{{ route('files.show', ['category' => $related['category'], 'type' => $related['type']]) }}"
                            class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                            .{{ $related['type'] }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($relatedByTypeFamily->isNotEmpty())
            <section class="mt-8 border-t border-gray-200 pt-8">
                <h2 class="text-xl font-semibold text-gray-900">Related binary formats for upload testing</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($relatedByTypeFamily as $related)
                        <a href="{{ route('files.show', ['category' => $related['category'], 'type' => $related['type']]) }}"
                            class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                            .{{ $related['type'] }}
                        </a>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-gray-600">
                    Need broader test coverage? Browse the <a href="{{ route('upload-testing') }}" class="underline hover:text-gray-700">binary upload testing guide</a>.
                </p>
            </section>
        @endif

    </div>
</x-guest-layout>
