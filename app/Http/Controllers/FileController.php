<?php
namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
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

}
