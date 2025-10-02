<x-guest-layout>
    <x-slot name="title">Blank .{{ $file['type'] }} file for {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }} | Blank Files</x-slot>
    <x-slot name="meta">
        <meta name="description"
            content="Download a free blank .{{ $file['type'] }} file{{ $file['package'] ? ' (zip package)' : '' }} for {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }}.">
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:title" content="Blank .{{ $file['type'] }} file for {{ Str::ucfirst(str_replace('-', ' ', $file['category'])) }}">
        <meta property="og:description" content="Get a clean, ready-to-use blank .{{ $file['type'] }} file.">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
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
                    <a href="{{ $file['url'] }}"
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

    </div>
</x-guest-layout>
