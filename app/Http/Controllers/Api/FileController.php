<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FileController extends Controller {
    /**
     * Get list of all files
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $files = Cache::remember('files.index', 60 * 60 * 24, function () {
            return Http::get(config('app.cdn_url') . '/files/index.json')->json();
        });

        return response()->json($files);
    }

    /**
     * Get a specific file's information
     *
     * @param string $category
     * @param string $filename
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($category, $filename) {
        $fileUrl = config('app.cdn_url') . "/files/{$category}/{$filename}";

        try {
            $fileExists = Http::head($fileUrl)->successful();

            if (!$fileExists) {
                return response()->json(['error' => 'File not found'], 404);
            }

            return response()->json([
                'url'      => $fileUrl,
                'category' => $category,
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch file information'], 500);
        }
    }

    /**
     * Download a file
     *
     * @param string $category
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function download($category, $filename) {
        $fileUrl = config('app.cdn_url') . "/files/{$category}/{$filename}";

        try {
            $contents = Cache::remember("files.{$category}.{$filename}", 60 * 60 * 24 * 30, function () use ($fileUrl) {
                $response = Http::get($fileUrl);

                if (!$response->successful()) {
                    throw new \Exception('File not found');
                }

                return $response->body();
            });

            $headers = [
                'Content-Type'        => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response($contents, 200, $headers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}