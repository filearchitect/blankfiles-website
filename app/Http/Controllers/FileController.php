<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;

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

}
