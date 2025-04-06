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
                            class="download-button relative px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-gray-700 text-center transition-colors "
                            data-url="{{ $file['url'] }}"
                            data-type="{{ $file['type'] }}"
                            data-package="{{ $file['package'] ? 'true' : 'false' }}"
                        >
                        <span class="file-label">.{{ $file['type'] }}</span>
                        @if($file['package'])
                                <span class="package-badge absolute text-xs text-gray-500 bg-gray-200 rounded-full px-2 py-1 top-2 right-2">zipped</span>
                        @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const downloadButtons = document.querySelectorAll('.download-button');
            
            downloadButtons.forEach(button => {
                button.addEventListener('click', handleDownload);
            });
        });

        async function handleDownload() {
            const button = this;
            const url = button.dataset.url;
            const fileType = button.dataset.type;
            const isPackage = button.dataset.package === 'true';
            const fileName = `file.${fileType}${isPackage ? '.zip' : ''}`;
            const fileLabel = button.querySelector('.file-label');
            const originalText = fileLabel.textContent;
            
            // Set button to loading state
            setButtonState(button, true, 'Downloading...');
            
            try {
                await downloadFile(url, fileName, isPackage);
            } catch (error) {
                console.error('Download failed:', error);
                alert(`Failed to download file: ${error.message}`);
            } finally {
                // Reset button state
                setButtonState(button, false, originalText);
            }
        }
        
        function setButtonState(button, isLoading, text) {
            button.disabled = isLoading;
            const fileLabel = button.querySelector('.file-label');
            fileLabel.textContent = text;
            button.classList.toggle('opacity-50', isLoading);
            button.classList.toggle('cursor-not-allowed', isLoading);
        }

        async function downloadFile(url, fileName, isPackage) {
            try {
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                
                const blob = await response.blob();
                
                // Create correct blob type for packages
                const fileBlob = isPackage ? 
                    new Blob([blob], { type: 'application/zip' }) : 
                    blob;
                
                // Create download link
                const downloadUrl = URL.createObjectURL(fileBlob);
                const downloadLink = document.createElement('a');
                downloadLink.href = downloadUrl;
                downloadLink.download = fileName;
                downloadLink.style.display = 'none';
                
                // Trigger download
                document.body.appendChild(downloadLink);
                downloadLink.click();
                
                // Clean up
                setTimeout(() => {
                    document.body.removeChild(downloadLink);
                    URL.revokeObjectURL(downloadUrl);
                }, 100);
            } catch (error) {
                console.error('Download error:', error);
                throw error;
            }
        }
    </script>
</x-guest-layout>