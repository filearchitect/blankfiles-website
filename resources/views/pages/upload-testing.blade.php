<x-guest-layout>
    <x-slot name="title">Binary files for upload testing — Blank Files</x-slot>
    <x-slot name="meta">
        <meta name="description"
            content="Download minimal valid binary files for upload testing: image, video, audio, office, design, and 3D formats.">
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:title" content="Binary files for upload testing — Blank Files">
        <meta property="og:description" content="Minimal valid binary files for upload testing across common formats.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
        @php
            $uploadTestingJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Binary files for upload testing',
                'description' => 'Minimal valid binary files for upload validation and parser testing.',
                'url' => route('upload-testing'),
                'isPartOf' => route('home'),
            ];
            $faqJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name' => 'What is a minimal valid binary file?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'A file that is as small as possible while still being valid for its format and openable by compatible software.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Can I use these files for upload testing?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Yes. The files are intended for upload validation, MIME checks, parsers, and ingestion pipelines.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Are these files direct-download and API-accessible?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Yes. Each extension page provides direct download and the API exposes machine-readable file URLs.',
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($uploadTestingJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode($faqJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    </x-slot>

    <div class="mx-auto px-4 lg:px-8">
        <nav class="mb-8 border-b border-gray-200 pb-8 pt-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('home') }}" class="hover:text-gray-700">Home</a></li>
                <li class="mx-2">/</li>
                <li aria-current="page" class="text-gray-700">Upload Testing</li>
            </ol>
        </nav>

        <div class="prose prose-gray max-w-none">
            <h1 class="text-3xl font-semibold text-gray-900">Binary files for upload testing</h1>
            <p class="mt-4 text-lg text-gray-600">
                Use these minimal valid binary files to test upload validation, MIME handling, media processing, and parser edge cases.
            </p>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">High-intent formats</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($priorityFiles as $file)
                    <a href="{{ route('files.show', ['category' => $file['category'], 'type' => $file['type']]) }}"
                        class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                        .{{ $file['type'] }}
                    </a>
                @endforeach
            </div>

            <h2 class="mt-10 text-xl font-semibold text-gray-800">Browse by category</h2>
            @foreach ($filesByCategory as $category => $files)
                <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ Str::ucfirst(str_replace('-', ' ', $category)) }}</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($files as $file)
                            <a href="{{ route('files.show', ['category' => $file['category'], 'type' => $file['type']]) }}"
                                class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                                .{{ $file['type'] }}
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <h2 class="mt-10 text-xl font-semibold text-gray-800">Planned next binary formats</h2>
            <p class="mt-2 text-gray-600">
                We prioritize formats based on upload-testing demand and developer workflows.
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($plannedNextFormats as $extension)
                    <span class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700">.{{ $extension }}</span>
                @endforeach
            </div>

            <h2 class="mt-10 text-xl font-semibold text-gray-800">FAQ</h2>
            <div class="mt-4 space-y-4 text-gray-600">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">What is a minimal valid binary file?</h3>
                    <p class="mt-1">A file that is as small as possible while still being valid for its format and openable by compatible software.</p>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Can I use these files for upload testing?</h3>
                    <p class="mt-1">Yes. They are designed for upload validation, MIME checks, parser testing, and ingestion pipeline edge cases.</p>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Where do these files come from?</h3>
                    <p class="mt-1">The canonical catalog is maintained in <a href="https://github.com/filearchitect/blank-files" class="underline hover:text-gray-700"
                            target="_blank" rel="noopener noreferrer">filearchitect/blank-files</a>.</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
