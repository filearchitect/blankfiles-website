<x-guest-layout>
    
    
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @php
            $groupedFiles = $files->groupBy('category');
        @endphp

        @foreach($groupedFiles as $category => $files)
            <div class="mb-8 bg-white">
                <div class="py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ Str::ucfirst(str_replace('-', ' ', $category)) }}
                    </h2>
                </div>
                
                <div class="py-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($files as $file)
                        <button 
                            class="download-button text-sm px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-gray-700 text-center transition-colors"
                            data-url="{{ $file['url'] }}"
                            data-type="{{ $file['type'] }}"
                            
                        >
                            @if($file['package'])
                                <span class="text-xs text-gray-500">Package</span>
                            @endif
                            {{ Str::upper($file['type']) }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        async function downloadFile(url, filename, isPackage) {
            try {
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                alert(url)
                const blob = await response.blob();
                
                // For packages/zip files, create a new blob with the correct mime type
                const fileBlob = isPackage ? 
                    new Blob([blob], { type: 'application/zip' }) : 
                    blob;
                
                const downloadUrl = URL.createObjectURL(fileBlob);
                
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = filename + (isPackage ? '.zip' : '');
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                
                // Cleanup
                setTimeout(() => {
                    document.body.removeChild(a);
                    URL.revokeObjectURL(downloadUrl);
                }, 100);
            } catch (error) {
                console.error('Download error:', error);
                throw error;
            }
        }

        document.querySelectorAll('.download-button').forEach(button => {
            button.addEventListener('click', async () => {
                const url = button.dataset.url;
                const type = button.dataset.type;
                const isPackage = button.querySelector('span.text-xs.text-gray-500') !== null;
                const originalText = button.textContent;
                
                button.disabled = true;
                button.textContent = 'Downloading...';
                button.classList.add('opacity-50', 'cursor-not-allowed');

                try {
                    await downloadFile(url, `file.${type}`, isPackage);
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