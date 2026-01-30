<?php
namespace App\Http\Controllers;

use App\Services\FileService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;

class FileController extends Controller {
    protected FileService $fileService;

    public function __construct(FileService $fileService) {
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $files = $this->fileService->getFormattedFiles();

        if (request()->wantsJson()) {
            return response()->json(['files' => $files]);
        }

        return view('files.index', ['files' => $files]);
    }

    /**
     * Display a single file details page.
     */
    public function show(string $category, string $type) {
        $files = $this->fileService->getFormattedFiles();

        $file = $files->first(function ($item) use ($category, $type) {
            return ($item['category'] === $category) && ($item['type'] === $type);
        });

        abort_unless($file, 404);

        return view('files.show', [
            'file' => $file,
            'title' => Str::upper("{$type} blank files for {$category}"),
        ]);
    }

    /**
     * Proxy download from CDN so the browser gets a same-origin response with
     * Content-Disposition: attachment (redirect to CDN can fail in some browsers).
     */
    public function download(string $category, string $type) {
        $files = $this->fileService->getFormattedFiles();
        $file = $files->first(fn ($item) => $item['category'] === $category && $item['type'] === $type);
        abort_unless($file, 404);

        $url = $file['url'];
        $isPackage = $file['package'];
        $filename = 'blank.' . $type . ($isPackage ? '.zip' : '');

        try {
            $client = new Client(['timeout' => 120]);
            $response = $client->request('GET', $url, ['stream' => true]);
        } catch (RequestException $e) {
            abort(502, 'File unavailable');
        }

        if ($response->getStatusCode() !== 200) {
            abort(502, 'File unavailable');
        }

        $body = $response->getBody();
        return response()->streamDownload(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(65536);
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
            }
        }, $filename, [
            'Content-Type' => $isPackage ? 'application/zip' : 'application/octet-stream',
        ]);
    }
}
