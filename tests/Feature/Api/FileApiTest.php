<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('cache.cache_enabled', false);

    Http::fake([
        '*' => Http::response([
            'files' => [
                [
                    'category' => 'document-spreadsheet',
                    'type' => 'xlsx',
                    'url' => 'blank.xlsx',
                    'package' => false,
                ],
                [
                    'category' => 'image',
                    'type' => 'png',
                    'url' => 'blank.png',
                    'package' => false,
                ],
            ],
        ], 200),
    ]);
});

test('api files response includes meta and cache validators', function () {
    $response = $this->getJson('/api/v1/files');

    $response->assertOk();
    $response->assertJsonStructure([
        'files' => [['category', 'type', 'url', 'package']],
        'meta' => ['version', 'generated_at', 'count'],
    ]);

    expect($response->headers->get('ETag'))->not->toBeNull();
    expect($response->headers->get('Last-Modified'))->not->toBeNull();
});

test('api files supports conditional get with etag', function () {
    $first = $this->getJson('/api/v1/files');
    $etag = $first->headers->get('ETag');

    $second = $this->withHeaders(['If-None-Match' => $etag])->get('/api/v1/files');
    $second->assertStatus(304);
});

test('api supports deterministic lookup by category and type', function () {
    $response = $this->getJson('/api/v1/files/document-spreadsheet/xlsx');

    $response->assertOk();
    $response->assertJsonPath('meta.count', 1);
    $response->assertJsonPath('files.0.category', 'document-spreadsheet');
    $response->assertJsonPath('files.0.type', 'xlsx');
});

test('deterministic lookup returns 404 with meta when not found', function () {
    $response = $this->getJson('/api/v1/files/missing/txt');

    $response->assertNotFound();
    $response->assertJsonPath('meta.count', 0);
});

test('sitemap supports conditional get with etag', function () {
    $first = $this->get('/sitemap.xml');
    $first->assertOk();
    $etag = $first->headers->get('ETag');

    $second = $this->withHeaders(['If-None-Match' => $etag])->get('/sitemap.xml');
    $second->assertStatus(304);
});

test('api status returns catalog metadata with cache validators', function () {
    $response = $this->getJson('/api/v1/status');

    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'service',
        'version',
        'generated_at',
        'catalog' => [
            'source_repository',
            'catalog_url',
            'cdn_url',
            'file_count',
            'type_count',
            'category_count',
        ],
    ]);
    $response->assertJsonPath('status', 'ok');
    $response->assertJsonPath('catalog.source_repository', 'https://github.com/filearchitect/blank-files');

    expect($response->headers->get('ETag'))->not->toBeNull();
    expect($response->headers->get('Last-Modified'))->not->toBeNull();
});
