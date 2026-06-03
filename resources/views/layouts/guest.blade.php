<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Blank Files') }}</title>

    {{-- Per-page SEO meta --}}
    {{ $meta ?? '' }}
    <link rel="alternate" type="application/json" href="{{ url('/api/v1/files') }}">
    <link rel="alternate" type="application/json" href="{{ route('openapi') }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}">
    <link rel="alternate" type="text/plain" href="{{ route('llms') }}">
    <link rel="alternate" type="text/plain" href="{{ route('llms-full') }}">

    <!-- Fonts -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.openpanel')
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col items-center bg-gray-100">
        <div class="w-full bg-white shadow-sm">
            <div class="mx-auto flex w-full max-w-[1280px] items-center justify-between px-6 py-4">
                <h1 class="text-3xl font-bold tracking-tight text-gray-800"><a href="{{ route('home') }}">Blank Files</a></h1>
                <nav class="flex items-center gap-6" aria-label="Main">
                    <a href="{{ route('about') }}" class="font-medium text-gray-600 hover:text-gray-900">About</a>
                    <a href="{{ route('upload-testing') }}" class="font-medium text-gray-600 hover:text-gray-900">Upload Testing</a>
                    <a href="{{ route('api-docs') }}" class="font-medium text-gray-600 hover:text-gray-900">API</a>
                    <a href="https://github.com/filearchitect/blank-files" class="font-medium text-gray-600 hover:text-gray-900" target="_blank" rel="noopener noreferrer">
                        GitHub
                    </a>
                </nav>
            </div>
        </div>

        <div class="mt-8 w-full max-w-[1280px] flex-grow overflow-hidden bg-white px-6 py-6 sm:rounded-lg">
            {{ $slot }}
        </div>

        <footer class="mt-8 w-full border-t border-gray-200 bg-white">
            <div class="mx-auto w-full max-w-[1280px] px-6 py-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <div class="text-gray-600">
                        © {{ date('Y') }} Blank Files. All rights reserved.
                    </div>
                    <div class="text-center text-gray-600 sm:text-right">
                        <div>
                            Agent access: <a href="https://www.npmjs.com/package/@filearchitect/blankfiles-mcp" class="underline hover:text-gray-700" target="_blank"
                                rel="noopener noreferrer">npm package</a> · <a
                                href="https://registry.modelcontextprotocol.io/v0.1/servers?search=io.github.filearchitect/blankfiles-mcp"
                                class="underline hover:text-gray-700" target="_blank" rel="noopener noreferrer">MCP registry</a>
                        </div>
                        <div>
                            Server ID: <code class="rounded bg-gray-100 px-1 py-0.5 text-xs">io.github.filearchitect/blankfiles-mcp</code>
                        </div>
                    </div>
                    <div class="text-gray-600">
                        Created by <a href="https://seblavoie.dev" class="underline hover:text-gray-700" target="_blank" rel="noopener noreferrer">Sébastien Lavoie</a> for <a
                            href="https://filearchitect.com" class="underline hover:text-gray-700" target="_blank" rel="noopener noreferrer">File
                            Architect</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
