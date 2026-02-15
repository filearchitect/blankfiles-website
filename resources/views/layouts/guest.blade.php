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
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col items-center bg-gray-100">
        <div class="w-full bg-white shadow-sm">
            <div class="mx-auto flex w-full max-w-[1280px] items-center justify-between px-6 py-4">
                <h1 class="text-3xl font-bold tracking-tight text-gray-800"><a href="{{ route('home') }}">Blank Files</a></h1>
                <nav class="flex items-center gap-6" aria-label="Main">
                    <a href="{{ route('about') }}" class="font-medium text-gray-600 hover:text-gray-900">About</a>
                    <a href="{{ route('upload-testing') }}" class="font-medium text-gray-600 hover:text-gray-900">Upload Testing</a>
                    <a href="{{ route('binary-roadmap') }}" class="font-medium text-gray-600 hover:text-gray-900">Roadmap</a>
                    <a href="{{ route('api-docs') }}" class="font-medium text-gray-600 hover:text-gray-900">API</a>
                    <a href="https://github.com/filearchitect/blank-files"
                        class="inline-flex items-center rounded-sm bg-gray-800 px-4 py-2 text-white transition-colors duration-200 hover:bg-gray-700" target="_blank"
                        rel="noopener noreferrer">
                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        View on GitHub
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
