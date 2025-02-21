<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FilesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $files = Cache::remember('files.index', 60 * 60 * 24, function () {
            return Http::get(config('app.cdn_url') . '/files/index.json')->json();
        });

        return view('files.index', ['files' => $files]);
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
        $fileUrl = config('app.cdn_url') . "/files/{$category}/{$filename}";

        $contents = Cache::remember("files.{$category}.{$filename}", 60 * 60 * 24, function () use ($fileUrl) {
            return Http::get($fileUrl)->body();
        });

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
