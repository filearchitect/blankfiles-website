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
                        <div class="group relative block cursor-pointer rounded bg-gray-100 px-3 py-2 text-center text-gray-700 transition-colors hover:bg-gray-200" role="link"
                            tabindex="0" onclick="window.location='{{ route('files.show', ['category' => $file['category'], 'type' => $file['type']]) }}'"
                            onkeydown="if(event.key==='Enter'||event.key===' '){window.location='{{ route('files.show', ['category' => $file['category'], 'type' => $file['type']]) }}';}">
                            <span class="file-label">.{{ $file['type'] }}</span>
                            @if ($file['package'])
                                <span class="package-badge absolute right-2 top-2 rounded-full bg-gray-200 px-2 py-1 text-xs text-gray-500">zipped</span>
                            @endif

                            <button type="button"
                                class="pointer-events-none absolute bottom-2 right-2 inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-white text-gray-900 opacity-0 transition-opacity hover:bg-gray-50 focus:opacity-100 focus:outline-none focus:ring-2 focus:ring-gray-400 group-hover:pointer-events-auto group-hover:opacity-100"
                                title="Download .{{ $file['type'] }}{{ $file['package'] ? ' (zip)' : '' }}"
                                aria-label="Download .{{ $file['type'] }}{{ $file['package'] ? ' (zip)' : '' }}" onclick="event.stopPropagation(); quickDownload(this);"
                                data-url="{{ $file['url'] }}" data-type="{{ $file['type'] }}" data-package="{{ $file['package'] ? 'true' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true" class="h-4 w-4">
                                    <title>download-solid</title>
                                    <g fill="#212121">
                                        <path
                                            d="M 15 4 L 15 20.5625 L 9.71875 15.28125 L 8.28125 16.71875 L 15.28125 23.71875 L 16 24.40625 L 16.71875 23.71875 L 23.71875 16.71875 L 22.28125 15.28125 L 17 20.5625 L 17 4 Z M 7 26 L 7 28 L 25 28 L 25 26 Z">
                                        </path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        async function quickDownload(buttonEl) {
            const url = buttonEl.dataset.url;
            const fileType = buttonEl.dataset.type;
            const isPackage = buttonEl.dataset.package === 'true';
            const originalHtml = buttonEl.innerHTML;

            try {
                buttonEl.disabled = true;
                buttonEl.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="4" opacity="0.25"/><path d="M4 12a8 8 0 0 1 8-8" stroke-width="4" opacity="0.75"/></svg>';

                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const blob = await response.blob();
                const fileBlob = isPackage ? new Blob([blob], {
                    type: 'application/zip'
                }) : blob;

                const downloadUrl = URL.createObjectURL(fileBlob);
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.download = `blank.${fileType}${isPackage ? '.zip' : ''}`;
                document.body.appendChild(link);
                link.click();
                setTimeout(() => {
                    document.body.removeChild(link);
                    URL.revokeObjectURL(downloadUrl);
                }, 100);
            } catch (e) {
                console.error('Quick download failed:', e);
                alert('Download failed. Please try again.');
            } finally {
                buttonEl.disabled = false;
                buttonEl.innerHTML = originalHtml;
            }
        }
    </script>

</x-guest-layout>
