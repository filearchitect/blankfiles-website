<?php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FileService {
    /**
     * Get all files from CDN
     *
     * @return array
     */
    public function getAllFiles(): array {
        if (config('cache.cache_enabled')) {
            return Cache::remember('cdn_files', now()->addHours(1), function () {
                return $this->fetchFilesFromCDN('/files/index.json');
            });
        }

        return $this->fetchFilesFromCDN('/files/index.json');
    }

    public function fetchFilesFromCDN(string $path = '/files/index.json') {
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
            return [
                'category' => $file['category'],
                'type'     => $file['type'],
                'url'      => config('app.cdn_url') . "/files/{$file['url']}",
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