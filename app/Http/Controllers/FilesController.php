<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FilesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $response = Http::get('https://cdn.jsdelivr.net/gh/filearchitect/blank-files@main/files/index.json?_refresh');

        $data = [
            'files' => $response->json(),
        ];

        return view('files.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    public function download($category, $filename) {
        $fileUrl = "https://cdn.jsdelivr.net/gh/filearchitect/blank-files@main/files/{$category}/{$filename}";

        // Get the file contents
        $contents = Http::get($fileUrl)->body();

        // Get file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Set the response headers to force download
        $headers = [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
        ];

        return response($contents, 200, $headers);
    }
}
