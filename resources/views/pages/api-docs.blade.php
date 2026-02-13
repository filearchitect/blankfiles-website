<x-guest-layout>
    <x-slot name="title">API documentation — Blank Files</x-slot>
    <x-slot name="meta">
        <meta name="description" content="API documentation for Blank Files: list and download blank files by type and category.">
        <link rel="canonical" href="{{ url()->current() }}" />
    </x-slot>

    <div class="mx-auto px-4 lg:px-8">
        <nav class="mb-8 border-b border-gray-200 pb-8 pt-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('home') }}" class="hover:text-gray-700">Home</a></li>
                <li class="mx-2">/</li>
                <li aria-current="page" class="text-gray-700">API</li>
            </ol>
        </nav>

        <div class="prose prose-gray max-w-none">
            <h1 class="text-3xl font-semibold text-gray-900">API documentation</h1>
            <p class="mt-4 text-lg text-gray-600">
                Use the Blank Files API to list and download blank files programmatically. All endpoints return JSON unless noted. Throttling applies to avoid abuse.
            </p>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Base URL</h2>
            <p class="mt-2 text-gray-600">
                <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">{{ url('/') }}</code>
            </p>
            <h2 class="mt-8 text-xl font-semibold text-gray-800">Machine discovery</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6 text-gray-600">
                <li><code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">{{ route('openapi') }}</code> (OpenAPI schema)</li>
                <li><code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">{{ route('llms') }}</code> and <code
                        class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">{{ route('llms-full') }}</code> (agent-readable docs)</li>
                <li><code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">{{ route('sitemap') }}</code> (crawlable URL index)</li>
            </ul>
            <h2 class="mt-8 text-xl font-semibold text-gray-800">API routes</h2>
            <p class="mt-2 text-gray-600">Prefix: <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">/api/v1</code>. Throttle: 30 requests per minute per client.</p>
            <p class="mt-2 text-gray-600">Responses include <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">meta.version</code>, <code
                    class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">meta.generated_at</code>, and <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">meta.count</code>.
                Conditional requests are supported via <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">ETag</code> and <code
                    class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">Last-Modified</code>.</p>

            <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h3 class="text-lg font-medium text-gray-900">GET /api/v1/files</h3>
                <p class="mt-2 text-gray-600">Returns all files in the catalog.</p>
                <p class="mt-2 text-sm font-medium text-gray-700">Response</p>
                <pre class="mt-1 overflow-x-auto rounded bg-gray-900 p-4 text-sm text-gray-100"><code>{
  "files": [
    {
      "category": "document-spreadsheet",
      "type": "xlsx",
      "url": "https://…/files/blank.xlsx",
      "package": false
    }
  ],
  "meta": {
    "version": "v1",
    "generated_at": "2026-02-13T15:00:00Z",
    "count": 1
  }
}</code></pre>
                <p class="mt-2 text-sm text-gray-600"><code class="rounded bg-gray-200 px-1 py-0.5">url</code> is the full URL to the file (CDN). <code
                        class="rounded bg-gray-200 px-1 py-0.5">package</code> is <code class="rounded bg-gray-200 px-1 py-0.5">true</code> when the file is served as a .zip.</p>
            </section>

            <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h3 class="text-lg font-medium text-gray-900">GET /api/v1/files/{type}</h3>
                <p class="mt-2 text-gray-600">Returns only files matching the given type (e.g. <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">xlsx</code>, <code
                        class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">pdf</code>).</p>
                <p class="mt-2 text-sm font-medium text-gray-700">Response</p>
                <pre class="mt-1 overflow-x-auto rounded bg-gray-900 p-4 text-sm text-gray-100"><code>{
  "files": [
    { "category": "document-spreadsheet", "type": "xlsx", "url": "…", "package": false }
  ],
  "meta": { "version": "v1", "generated_at": "2026-02-13T15:00:00Z", "count": 1 }
}</code></pre>
            </section>

            <section class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <h3 class="text-lg font-medium text-gray-900">GET /api/v1/files/{category}/{type}</h3>
                <p class="mt-2 text-gray-600">Returns exactly one matching entry when the category and type exist, otherwise 404.</p>
                <p class="mt-2 text-sm font-medium text-gray-700">Response (200)</p>
                <pre class="mt-1 overflow-x-auto rounded bg-gray-900 p-4 text-sm text-gray-100"><code>{
  "files": [
    { "category": "document-spreadsheet", "type": "xlsx", "url": "…", "package": false }
  ],
  "meta": { "version": "v1", "generated_at": "2026-02-13T15:00:00Z", "count": 1 }
}</code></pre>
            </section>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Catalog schema</h2>
            <p class="mt-2 text-gray-600">
                The canonical catalog is defined in the <a href="https://github.com/filearchitect/blank-files" class="font-medium text-gray-900 underline hover:no-underline"
                    target="_blank" rel="noopener noreferrer">filearchitect/blank-files</a> repo (<code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">files/files.json</code>):
                each entry has <code class="rounded bg-gray-200 px-1 py-0.5">type</code>, <code class="rounded bg-gray-200 px-1 py-0.5">url</code>, <code
                    class="rounded bg-gray-200 px-1 py-0.5">category</code>, and optional <code class="rounded bg-gray-200 px-1 py-0.5">package</code>.
            </p>

            <p class="mt-8 text-gray-600">
                <a href="{{ route('about') }}" class="font-medium text-gray-900 underline hover:no-underline">About Blank Files</a> · <a href="{{ route('home') }}"
                    class="font-medium text-gray-900 underline hover:no-underline">Browse files</a>
            </p>
        </div>
    </div>
</x-guest-layout>
