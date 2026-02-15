<?php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FileService {
    /**
     * Get all files from CDN
     *
     * @return array
     */
    public function getAllFiles(): array {
        if (config('cache.cache_enabled')) {
            return Cache::remember('cdn_files', now()->addMinutes((int) config('cache.catalog_ttl_minutes')), function () {
                return $this->fetchFilesFromCDN('/files/files.json');
            });
        }

        return $this->fetchFilesFromCDN('/files/files.json');
    }

    public function fetchFilesFromCDN(string $path = '/files/files.json') {
        $baseUrl = rtrim(config('app.cdn_url'), '/');
        return Http::timeout(15)->get($baseUrl . $path)->json();
    }

    /**
     * Get formatted files with full URLs
     *
     * @return Collection
     */
    public function getFormattedFiles(): Collection {
        $filesData = $this->getAllFiles();

        $baseUrl = rtrim(config('app.cdn_url'), '/');

        return collect($filesData['files'])->map(function ($file) use ($baseUrl) {

            $package = $file['package'] ?? false;
            $url     = $baseUrl . "/files/{$file['url']}" . ($package ? '.zip' : '');

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
