<x-guest-layout>
    <x-slot name="title">Blank Files — Free blank files by type and category</x-slot>
    <x-slot name="meta">
        <meta name="description" content="Download free blank files across many types and categories. Fast, direct downloads and SEO-friendly detail pages.">
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:title" content="Blank Files — Free blank files by type and category">
        <meta property="og:description" content="Download free blank files across many types and categories.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
        @php
            $catalogJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'DataCatalog',
                'name' => 'Blank Files',
                'description' => 'Minimal valid blank files by category and extension for testing and automation.',
                'url' => route('home'),
                'dataset' => [
                    '@type' => 'Dataset',
                    'name' => 'Blank Files Catalog',
                    'distribution' => [
                        '@type' => 'DataDownload',
                        'contentUrl' => url('/api/v1/files'),
                        'encodingFormat' => 'application/json',
                    ],
                ],
                'mainEntityOfPage' => route('api-docs'),
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($catalogJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        @php
            $groupedFiles = $files->groupBy('category');
        @endphp

        @foreach ($groupedFiles as $category => $files)
            <div class="mb-8 bg-white" id="{{ $category }}">
                <div class="border-b border-gray-200 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ Str::ucfirst(str_replace('-', ' ', $category)) }}
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-3 py-6 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($files as $file)
                        <div class="group relative rounded bg-gray-100 text-center text-gray-700 transition-colors hover:bg-gray-200">
                            <a href="{{ route('files.show', ['category' => $file['category'], 'type' => $file['type']]) }}"
                                class="block rounded px-3 py-2 pr-11 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                <span class="file-label">.{{ $file['type'] }}</span>
                                @if ($file['package'])
                                    <span class="package-badge absolute right-2 top-2 rounded-full bg-gray-200 px-2 py-1 text-xs text-gray-500">zipped</span>
                                @endif
                            </a>

                            <a href="{{ route('files.download', ['category' => $file['category'], 'type' => $file['type']]) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="pointer-events-none absolute bottom-2 right-2 inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-white text-gray-900 opacity-0 transition-opacity hover:bg-gray-50 focus:pointer-events-auto focus:opacity-100 focus:outline-none focus:ring-2 focus:ring-gray-400 group-hover:pointer-events-auto group-hover:opacity-100"
                                title="Download .{{ $file['type'] }}{{ $file['package'] ? ' (zip)' : '' }}"
                                aria-label="Download .{{ $file['type'] }}{{ $file['package'] ? ' (zip)' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true" class="h-4 w-4">
                                    <title>download-solid</title>
                                    <g fill="#212121">
                                        <path
                                            d="M 15 4 L 15 20.5625 L 9.71875 15.28125 L 8.28125 16.71875 L 15.28125 23.71875 L 16 24.40625 L 16.71875 23.71875 L 23.71875 16.71875 L 22.28125 15.28125 L 17 20.5625 L 17 4 Z M 7 26 L 7 28 L 25 28 L 25 26 Z">
                                        </path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</x-guest-layout>
