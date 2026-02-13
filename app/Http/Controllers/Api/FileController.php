<?php
namespace App\Http\Controllers\Api;

use App\Services\FileService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class FileController extends Controller {
    protected FileService $fileService;

    public function __construct(FileService $fileService) {
        $this->fileService = $fileService;
    }

    /**
     * Get list of all files
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse {
        return $this->respondWithFiles($request, $this->fileService->getFormattedFiles());
    }

    /**
     * Get files by type
     *
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByType(Request $request, string $type): JsonResponse {
        return $this->respondWithFiles($request, $this->fileService->getFilesByType($type));
    }

    /**
     * Get a single file by category and type.
     */
    public function show(Request $request, string $category, string $type): JsonResponse {
        $file = $this->fileService->getFormattedFiles()
            ->first(fn ($item) => $item['category'] === $category && $item['type'] === $type);

        if (!$file) {
            return response()->json([
                'error' => 'File not found.',
                'meta' => [
                    'version' => 'v1',
                    'generated_at' => Carbon::now('UTC')->toIso8601String(),
                    'count' => 0,
                ],
            ], 404);
        }

        return $this->respondWithFiles($request, collect([$file]));
    }

    /**
     * API operational status and catalog stats.
     */
    public function status(Request $request): JsonResponse {
        $files = $this->fileService->getFormattedFiles();
        $lastModified = Carbon::now('UTC')->startOfMinute();

        return $this->respondJsonCached($request, [
            'status' => 'ok',
            'service' => 'blankfiles-api',
            'version' => 'v1',
            'generated_at' => $lastModified->copy()->toIso8601String(),
            'catalog' => [
                'source_repository' => 'https://github.com/filearchitect/blank-files',
                'cdn_url' => rtrim((string) config('app.cdn_url'), '/'),
                'file_count' => $files->count(),
                'type_count' => $files->pluck('type')->unique()->count(),
                'category_count' => $files->pluck('category')->unique()->count(),
            ],
        ], $lastModified);
    }

    private function respondWithFiles(Request $request, Collection $files): JsonResponse {
        $files = $files->values();
        $lastModified = Carbon::now('UTC')->startOfHour();
        return $this->respondJsonCached($request, [
            'files' => $files,
            'meta' => [
                'version' => 'v1',
                'generated_at' => $lastModified->copy()->toIso8601String(),
                'count' => $files->count(),
            ],
        ], $lastModified);
    }

    private function respondJsonCached(Request $request, array $payload, Carbon $lastModified): JsonResponse {
        $etag = '"' . hash('sha256', json_encode($payload)) . '"';
        $response = response()->json($payload);

        $response->setEtag($etag);
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
