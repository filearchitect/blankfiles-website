<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Files
        </h2>
    </x-slot>
    
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @foreach ($files['categories'] as $category)
            <div class="mb-8 bg-white">
                <div class="py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">{{ ucfirst($category['name']) }}</h2>
                </div>
                <div class="py-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($category['files'] as $file)
                        <a 
                            href="{{ route('files.download', ['category' => $category['name'], 'filename' => $file['url']]) }}"
                            class="text-sm px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-gray-700 text-center transition-colors"
                        >
                            {{ $file['type'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-guest-layout>