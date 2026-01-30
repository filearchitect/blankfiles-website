<x-guest-layout>
    <x-slot name="title">About Blank Files — Free blank file downloads by type and category</x-slot>
    <x-slot name="meta">
        <meta name="description"
            content="About Blank Files: free, minimal valid blank files in many formats. Download empty documents, spreadsheets, images, audio, and video files. No sign-up required.">
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:title" content="About Blank Files — Free blank file downloads">
        <meta property="og:description" content="Download minimal valid blank files in many formats. Free empty documents, spreadsheets, images, and more by type and category.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
    </x-slot>

    <div class="mx-auto px-4 lg:px-8">
        <nav class="mb-8 border-b border-gray-200 pb-8 pt-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('home') }}" class="hover:text-gray-700">Home</a></li>
                <li class="mx-2">/</li>
                <li aria-current="page" class="text-gray-700">About</li>
            </ol>
        </nav>

        <div class="prose prose-gray max-w-none">
            <h1 class="text-3xl font-semibold text-gray-900">About Blank Files — Free blank file downloads</h1>

            <p class="mt-4 text-lg text-gray-600">
                <strong>Blank Files</strong> is a free service for downloading minimal, valid blank files in many formats. Each file is small and opens correctly in the right
                software—useful when you need an <strong>empty document</strong>, <strong>blank spreadsheet</strong>, <strong>empty image</strong>, <strong>blank audio</strong> or
                <strong>video file</strong>, or any other file type without creating one yourself.
            </p>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">What we offer</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6 text-gray-600">
                <li>Free blank files grouped by <strong>category</strong> (document-text, document-spreadsheet, image, audio, video, and more) and <strong>file type</strong> (e.g.
                    blank .xlsx, .pdf, .png, .docx).</li>
                <li>Fast, direct downloads with predictable filenames (<code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">blank.{type}</code> or <code
                        class="rounded bg-gray-100 px-1.5 py-0.5 text-sm">blank.{type}.zip</code>).</li>
                <li>A dedicated page for each blank file type so you can link or share a specific empty file.</li>
                <li>An <a href="{{ route('api-docs') }}" class="font-medium text-gray-900 underline hover:no-underline">API</a> for developers and bots to list and download blank
                    files programmatically.</li>
            </ul>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Where the files come from</h2>
            <p class="mt-2 text-gray-600">
                The file catalog and assets are maintained in the <a href="https://github.com/filearchitect/blank-files"
                    class="font-medium text-gray-900 underline hover:no-underline" target="_blank" rel="noopener noreferrer">filearchitect/blank-files</a> repository. Files are
                kept as small and minimal as possible while remaining valid for their format. This website fetches the list from that source and serves downloads so you always get
                the latest versions.
            </p>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Built for File Architect</h2>
            <p class="mt-2 text-gray-600">
                Blank Files was built for <a href="https://filearchitect.com" class="font-medium text-gray-900 underline hover:no-underline" target="_blank"
                    rel="noopener noreferrer">File Architect</a>, an app to create file and folder structures from plain text.</a>
            </p>

            <h2 class="mt-8 text-xl font-semibold text-gray-800">Who it’s for</h2>
            <p class="mt-2 text-gray-600">
                Anyone who needs a <strong>blank file</strong> or <strong>empty file</strong> quickly—developers testing uploads or parsers, designers needing empty assets, or
                anyone who wants a clean starting file. No sign-up or account required; all downloads are free.
            </p>

            <p class="mt-8 text-gray-600">
                <a href="{{ route('home') }}" class="font-medium text-gray-900 underline hover:no-underline">Browse all blank files</a> or read the <a
                    href="{{ route('api-docs') }}" class="font-medium text-gray-900 underline hover:no-underline">API documentation</a> to integrate with your app.
            </p>
        </div>
    </div>
</x-guest-layout>
