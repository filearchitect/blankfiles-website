<?php
namespace App\Http\Controllers\Api;

use App\Services\FileService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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
    public function index() {
        return response()->json([
            'files' => $this->fileService->getFormattedFiles(),
        ]);
    }

    /**
     * Get files by type
     *
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByType(string $type) {
        return response()->json([
            'files' => $this->fileService->getFilesByType($type),
        ]);
    }

}