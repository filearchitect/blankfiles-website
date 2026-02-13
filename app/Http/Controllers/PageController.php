<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function apiDocs()
    {
        return view('pages.api-docs');
    }

    public function apiPolicy()
    {
        return view('pages.api-policy');
    }

    public function llms(): Response
    {
        $lines = [
            '# Blank Files',
            '',
            '> Minimal valid blank files for testing, automation, and development workflows.',
            '',
            'Key resources:',
            '- API docs: ' . route('api-docs'),
            '- API compatibility policy: ' . route('api-policy'),
            '- OpenAPI schema: ' . route('openapi'),
            '- API files endpoint: ' . url('/api/v1/files'),
            '- API status endpoint: ' . url('/api/v1/status'),
            '- Full agent guide: ' . route('llms-full'),
            '',
            'Primary machine endpoints:',
            '- GET /api/v1/files',
            '- GET /api/v1/files/{type}',
            '- GET /api/v1/files/{category}/{type}',
            '- GET /files/{category}/{type}',
            '- GET /files/download/{category}/{type}',
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function llmsFull(): Response
    {
        $lines = [
            '# Blank Files: Agent Integration Guide',
            '',
            'Blank Files provides minimal valid blank files by category and extension.',
            '',
            'Base URL',
            '- ' . url('/'),
            '',
            'Documentation',
            '- API docs: ' . route('api-docs'),
            '- API compatibility policy: ' . route('api-policy'),
            '- OpenAPI schema: ' . route('openapi'),
            '- Source catalog repo: https://github.com/filearchitect/blank-files',
            '',
            'Endpoints',
            '- GET /api/v1/files',
            '  Returns `{ "files": [{ "category", "type", "url", "package" }] }`.',
            '- GET /api/v1/files/{type}',
            '  Returns the same schema filtered by file extension (example: `pdf`, `xlsx`).',
            '- GET /api/v1/files/{category}/{type}',
            '  Returns a deterministic single-file match.',
            '- GET /api/v1/status',
            '  Returns service health and catalog stats.',
            '- GET /files/{category}/{type}',
            '  Human page for a specific file type.',
            '- GET /files/download/{category}/{type}',
            '  Same-origin file download endpoint with attachment headers.',
            '',
            'Catalog Schema',
            '- `category` (string): normalized category slug.',
            '- `type` (string): extension without leading dot.',
            '- `url` (string): direct CDN URL to blank file.',
            '- `package` (boolean): true when distributed as zip archive.',
            '- `meta.version` (string): API version tag.',
            '- `meta.generated_at` (ISO8601 string): response generation timestamp (UTC, hour-granular).',
            '- `meta.count` (integer): number of entries returned.',
            '',
            'Operational notes',
            '- API routes are throttled at 30 requests/min/client.',
            '- Valid API keys can receive a higher limit (default 300/min via `X-API-Key`).',
            '- Download route is throttled at 60 requests/min/client.',
            '- Prefer API URLs for automation and stable parsing.',
            '- API supports conditional requests (`ETag`, `Last-Modified`).',
            '',
            'Discovery',
            '- robots.txt: ' . url('/robots.txt'),
            '- sitemap.xml: ' . route('sitemap'),
            '- llms.txt: ' . route('llms'),
            '',
            'Example',
            '1. Fetch all files: GET ' . url('/api/v1/files'),
            '2. Filter client-side by category/type.',
            '3. Download via `url` (direct CDN) or `/files/download/{category}/{type}` (same origin).',
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function openApi(): JsonResponse
    {
        $schema = [
            'openapi' => '3.1.0',
            'info' => [
                'title' => 'Blank Files API',
                'description' => 'Programmatic access to minimal valid blank files by type and category.',
                'version' => '1.0.0',
            ],
            'servers' => [
                ['url' => url('/')],
            ],
            'paths' => [
                '/api/v1/files' => [
                    'get' => [
                        'summary' => 'List all blank files',
                        'description' => 'Optional API key in `X-API-Key` may receive higher rate limits.',
                        'responses' => [
                            '200' => [
                                'description' => 'List of files',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/FileListResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/v1/files/{type}' => [
                    'get' => [
                        'summary' => 'List files by extension',
                        'description' => 'Optional API key in `X-API-Key` may receive higher rate limits.',
                        'parameters' => [
                            [
                                'name' => 'type',
                                'in' => 'path',
                                'required' => true,
                                'schema' => ['type' => 'string'],
                                'description' => 'File extension, for example `pdf` or `xlsx`.',
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Filtered list of files',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/FileListResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/v1/files/{category}/{type}' => [
                    'get' => [
                        'summary' => 'Get a single file by category and extension',
                        'description' => 'Optional API key in `X-API-Key` may receive higher rate limits.',
                        'parameters' => [
                            [
                                'name' => 'category',
                                'in' => 'path',
                                'required' => true,
                                'schema' => ['type' => 'string'],
                                'description' => 'Category slug, for example `document-spreadsheet`.',
                            ],
                            [
                                'name' => 'type',
                                'in' => 'path',
                                'required' => true,
                                'schema' => ['type' => 'string'],
                                'description' => 'File extension, for example `xlsx`.',
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Single-entry list of files',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/FileListResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '404' => [
                                'description' => 'File not found',
                            ],
                        ],
                    ],
                ],
                '/api/v1/status' => [
                    'get' => [
                        'summary' => 'Get API status and catalog metrics',
                        'description' => 'Returns operational status and aggregate counts from the blank files catalog.',
                        'responses' => [
                            '200' => [
                                'description' => 'API status',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/StatusResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'schemas' => [
                    'FileListResponse' => [
                        'type' => 'object',
                        'required' => ['files', 'meta'],
                        'properties' => [
                            'files' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/File',
                                ],
                            ],
                            'meta' => [
                                '$ref' => '#/components/schemas/Meta',
                            ],
                        ],
                    ],
                    'File' => [
                        'type' => 'object',
                        'required' => ['category', 'type', 'url', 'package'],
                        'properties' => [
                            'category' => ['type' => 'string', 'example' => 'document-spreadsheet'],
                            'type' => ['type' => 'string', 'example' => 'xlsx'],
                            'url' => ['type' => 'string', 'format' => 'uri', 'example' => url('/files/blank.xlsx')],
                            'package' => ['type' => 'boolean', 'example' => false],
                        ],
                    ],
                    'Meta' => [
                        'type' => 'object',
                        'required' => ['version', 'generated_at', 'count'],
                        'properties' => [
                            'version' => ['type' => 'string', 'example' => 'v1'],
                            'generated_at' => ['type' => 'string', 'format' => 'date-time'],
                            'count' => ['type' => 'integer', 'example' => 1],
                        ],
                    ],
                    'StatusResponse' => [
                        'type' => 'object',
                        'required' => ['status', 'service', 'version', 'generated_at', 'catalog'],
                        'properties' => [
                            'status' => ['type' => 'string', 'example' => 'ok'],
                            'service' => ['type' => 'string', 'example' => 'blankfiles-api'],
                            'version' => ['type' => 'string', 'example' => 'v1'],
                            'generated_at' => ['type' => 'string', 'format' => 'date-time'],
                            'catalog' => [
                                'type' => 'object',
                                'required' => ['source_repository', 'cdn_url', 'file_count', 'type_count', 'category_count'],
                                'properties' => [
                                    'source_repository' => ['type' => 'string', 'format' => 'uri'],
                                    'cdn_url' => ['type' => 'string', 'format' => 'uri'],
                                    'file_count' => ['type' => 'integer', 'example' => 300],
                                    'type_count' => ['type' => 'integer', 'example' => 120],
                                    'category_count' => ['type' => 'integer', 'example' => 15],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($schema);
    }

    public function sitemap(Request $request, FileService $fileService): Response
    {
        $today = Carbon::now()->toDateString();
        $files = $fileService->getFormattedFiles();

        $urls = [
            ['loc' => route('home'), 'lastmod' => $today, 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => $today, 'priority' => '0.7'],
            ['loc' => route('api-docs'), 'lastmod' => $today, 'priority' => '0.8'],
            ['loc' => route('api-policy'), 'lastmod' => $today, 'priority' => '0.8'],
            ['loc' => route('llms'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => route('llms-full'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => route('openapi'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => url('/api/v1/files'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => url('/api/v1/status'), 'lastmod' => $today, 'priority' => '0.9'],
        ];

        foreach ($files->pluck('type')->unique() as $type) {
            $urls[] = [
                'loc' => url("/api/v1/files/{$type}"),
                'lastmod' => $today,
                'priority' => '0.7',
            ];
        }

        foreach ($files as $file) {
            $urls[] = [
                'loc' => URL::route('files.show', ['category' => $file['category'], 'type' => $file['type']]),
                'lastmod' => $today,
                'priority' => '0.8',
            ];
        }

        $xml = view('pages.sitemap', ['urls' => $urls])->render();
        $lastModified = Carbon::now('UTC')->startOfHour();
        $etag = '"' . hash('sha256', $xml) . '"';

        $response = response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
        $response->setEtag($etag);
        $response->setLastModified($lastModified);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
