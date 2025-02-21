<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Files
        </h2>
    </x-slot>
    
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @php
            $groupedFiles = $files->groupBy('category');
        @endphp

        @foreach($groupedFiles as $category => $files)
            <div class="mb-8 bg-white">
                <div class="py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ Str::ucfirst($category) }}
                    </h2>
                </div>
                
                <div class="py-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($files as $file)
                        <button 
                            class="download-button text-sm px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-gray-700 text-center transition-colors"
                            data-url="{{ $file['url'] }}"
                            data-type="{{ $file['type'] }}"
                        >
                            {{ Str::upper($file['type']) }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        async function downloadFile(url, filename) {
            const response = await fetch(url);
            const blob = await response.blob();
            const downloadUrl = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(downloadUrl);
        }

        document.querySelectorAll('.download-button').forEach(button => {
            button.addEventListener('click', async () => {
                const url = button.dataset.url;
                const type = button.dataset.type;
                const originalText = button.textContent;
                
                button.disabled = true;
                button.textContent = 'Downloading...';
                button.classList.add('opacity-50', 'cursor-not-allowed');

                try {
                    await downloadFile(url, `file.${type}`);
                } catch (error) {
                    console.error('Download failed:', error);
                    alert(`Failed to download file: ${error.message}`);
                } finally {
                    button.disabled = false;
                    button.textContent = originalText;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    </script>
</x-guest-layout>