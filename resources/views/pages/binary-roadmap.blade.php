<x-guest-layout>
    <x-slot name="title">Binary format roadmap — Blank Files</x-slot>
    <x-slot name="meta">
        <meta name="description" content="Upcoming binary blank file formats planned for Blank Files expansion.">
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:title" content="Binary format roadmap — Blank Files">
        <meta property="og:description" content="Planned next binary file formats for upload testing and automation workflows.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
    </x-slot>

    <div class="mx-auto px-4 lg:px-8">
        <nav class="mb-8 border-b border-gray-200 pb-8 pt-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('home') }}" class="hover:text-gray-700">Home</a></li>
                <li class="mx-2">/</li>
                <li><a href="{{ route('upload-testing') }}" class="hover:text-gray-700">Upload Testing</a></li>
                <li class="mx-2">/</li>
                <li aria-current="page" class="text-gray-700">Roadmap</li>
            </ol>
        </nav>

        <div class="prose prose-gray max-w-none">
            <h1 class="text-3xl font-semibold text-gray-900">Binary format roadmap</h1>
            <p class="mt-4 text-lg text-gray-600">
                Planned binary format additions are prioritized by upload-testing demand and developer workflow relevance.
            </p>

            <section class="mt-8 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h2 class="mt-0 text-xl font-semibold text-gray-900">Priority 1: Highest traffic potential</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($priority1 as $ext)
                        <span class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700">.{{ $ext }}</span>
                    @endforeach
                </div>
            </section>

            <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h2 class="mt-0 text-xl font-semibold text-gray-900">Priority 2: Data and developer workflows</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($priority2 as $ext)
                        <span class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700">.{{ $ext }}</span>
                    @endforeach
                </div>
            </section>

            <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h2 class="mt-0 text-xl font-semibold text-gray-900">Priority 3: Creative and professional formats</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($priority3 as $ext)
                        <span class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700">.{{ $ext }}</span>
                    @endforeach
                </div>
            </section>

            <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h2 class="mt-0 text-xl font-semibold text-gray-900">Priority 4: Platform and package binaries</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($priority4 as $ext)
                        <span class="inline-flex rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700">.{{ $ext }}</span>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>
