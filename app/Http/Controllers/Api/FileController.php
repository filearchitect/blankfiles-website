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

    private function respondWithFiles(Request $request, Collection $files): JsonResponse {
        $files = $files->values();
        $lastModified = Carbon::now('UTC')->startOfHour();
        $etag = '"' . hash('sha256', json_encode($files->all())) . '"';

        $response = response()->json([
            'files' => $files,
            'meta' => [
                'version' => 'v1',
                'generated_at' => $lastModified->copy()->toIso8601String(),
                'count' => $files->count(),
            ],
        ]);

        $response->setEtag($etag);
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
