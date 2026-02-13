<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\JsonResponse;
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

    public function llms(): Response
    {
        $lines = [
            '# Blank Files',
            '',
            '> Minimal valid blank files for testing, automation, and development workflows.',
            '',
            'Key resources:',
            '- API docs: ' . route('api-docs'),
            '- OpenAPI schema: ' . route('openapi'),
            '- API files endpoint: ' . url('/api/v1/files'),
            '- Full agent guide: ' . route('llms-full'),
            '',
            'Primary machine endpoints:',
            '- GET /api/v1/files',
            '- GET /api/v1/files/{type}',
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
            '- OpenAPI schema: ' . route('openapi'),
            '- Source catalog repo: https://github.com/filearchitect/blank-files',
            '',
            'Endpoints',
            '- GET /api/v1/files',
            '  Returns `{ "files": [{ "category", "type", "url", "package" }] }`.',
            '- GET /api/v1/files/{type}',
            '  Returns the same schema filtered by file extension (example: `pdf`, `xlsx`).',
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
            '',
            'Operational notes',
            '- API routes are throttled at 30 requests/min/client.',
            '- Download route is throttled at 60 requests/min/client.',
            '- Prefer API URLs for automation and stable parsing.',
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
            ],
            'components' => [
                'schemas' => [
                    'FileListResponse' => [
                        'type' => 'object',
                        'required' => ['files'],
                        'properties' => [
                            'files' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/File',
                                ],
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
                ],
            ],
        ];

        return response()->json($schema);
    }

    public function sitemap(FileService $fileService): Response
    {
        $today = Carbon::now()->toDateString();
        $files = $fileService->getFormattedFiles();

        $urls = [
            ['loc' => route('home'), 'lastmod' => $today, 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => $today, 'priority' => '0.7'],
            ['loc' => route('api-docs'), 'lastmod' => $today, 'priority' => '0.8'],
            ['loc' => route('llms'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => route('llms-full'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => route('openapi'), 'lastmod' => $today, 'priority' => '0.9'],
            ['loc' => url('/api/v1/files'), 'lastmod' => $today, 'priority' => '0.9'],
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

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
