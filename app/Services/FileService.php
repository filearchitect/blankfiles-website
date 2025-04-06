<?php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class FileService {
    /**
     * Get all files from CDN
     *
     * @return array
     */
    public function getAllFiles(): array {
        // if (config('cache.cache_enabled')) {
        //     return Cache::remember('cdn_files', now()->addHours(1), function () {
        //         return $this->fetchFilesFromCDN('/files/files.json');
        //     });
        // }

        return $this->fetchFilesFromCDN('/files/files.json');
    }

    public function fetchFilesFromCDN(string $path = '/files/files.json') {
        return Http::get(config('app.cdn_url') . $path)->json();
    }

    /**
     * Get formatted files with full URLs
     *
     * @return Collection
     */
    public function getFormattedFiles(): Collection {
        $filesData = $this->getAllFiles();

        return collect($filesData['files'])->map(function ($file) {

            $package = $file['package'] ?? false;
            $url     = config('app.cdn_url') . "/files/{$file['url']}" . ($package ? '.zip' : '');

            return [
                'category' => $file['category'],
                'type' => $file['type'],
                'url' => $url,
                'package' => $package,
            ];
        });
    }

    /**
     * Get files by type
     *
     * @param string $type
     * @return Collection
     */
    public function getFilesByType(string $type): Collection {
        return $this->getFormattedFiles()->where('type', $type);
    }
}