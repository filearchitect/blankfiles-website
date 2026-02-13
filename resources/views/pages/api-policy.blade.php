<x-guest-layout>
    <x-slot name="title">API compatibility policy — Blank Files</x-slot>
    <x-slot name="meta">
        <meta name="description" content="Stability, versioning, and deprecation policy for the Blank Files API.">
        <link rel="canonical" href="{{ url()->current() }}" />
    </x-slot>

    <div class="mx-auto px-4 lg:px-8">
        <nav class="mb-8 border-b border-gray-200 pb-8 pt-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('home') }}" class="hover:text-gray-700">Home</a></li>
                <li class="mx-2">/</li>
                <li><a href="{{ route('api-docs') }}" class="hover:text-gray-700">API</a></li>
                <li class="mx-2">/</li>
                <li aria-current="page" class="text-gray-700">Policy</li>
            </ol>
        </nav>

        <div class="prose prose-gray max-w-none">
            <h1 class="text-3xl font-semibold text-gray-900">API compatibility policy</h1>
            <p class="mt-4 text-lg text-gray-600">
                This policy defines what can change in the Blank Files API and how breaking changes are handled.
            </p>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Versioning</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6 text-gray-600">
                <li>Current version: <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">v1</code>.</li>
                <li>Version is part of the URL: <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">/api/v1/...</code>.</li>
                <li>Breaking changes require a new major path version.</li>
            </ul>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Stability guarantees (v1)</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6 text-gray-600">
                <li>Existing endpoints and documented fields remain available within <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">v1</code>.</li>
                <li>Field types and semantics do not change incompatibly.</li>
                <li>New fields may be added; clients should ignore unknown fields.</li>
                <li>API supports conditional requests via <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">ETag</code> and <code
                        class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">Last-Modified</code>.</li>
            </ul>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Deprecation policy</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6 text-gray-600">
                <li>Deprecated endpoints remain active for at least 90 days before removal.</li>
                <li>Deprecations are announced in API docs and repository release notes.</li>
                <li>When possible, a direct replacement endpoint is provided.</li>
            </ul>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Rate limits</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6 text-gray-600">
                <li>Public clients are rate limited by IP.</li>
                <li>Optional API keys may receive higher limits using <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">X-API-Key</code>.</li>
                <li>Heavy clients should use conditional requests to reduce unnecessary traffic.</li>
            </ul>
        </div>
    </div>
</x-guest-layout>
