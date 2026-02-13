# Blank Files Website

Laravel application that powers [blankfiles.com](https://blankfiles.com). Browse and download minimal valid blank files by type and category. File data and assets are served from the [filearchitect/blank-files](https://github.com/filearchitect/blank-files) repository via a configurable CDN.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ (for Vite frontend build)

## Installation

```bash
git clone https://github.com/filearchitect/blankfiles-website.git
cd blankfiles-website
composer install
cp .env.example .env
php artisan key:generate
```

Set `CDN_URL` in `.env` (see [Configuration](#configuration)). Then build the frontend and run the app:

```bash
npm install && npm run build
php artisan serve
```

Or use [Laravel Herd](https://herd.laravel.com) with a `.test` domain.

## Configuration

| Variable        | Description                                                                                                                                                                                                                                                                                                                                                                                                                      |
| --------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `CDN_URL`       | **Required.** Base URL where the file catalog and assets are served. The app expects `{CDN_URL}/files/files.json` for the catalog and `{CDN_URL}/files/{filename}` for each file. Examples: `https://cdn.statically.io/gh/filearchitect/blank-files/main`, `https://raw.githubusercontent.com/filearchitect/blank-files/main` (note: raw GitHub has rate limits). Default in [config/app.php](config/app.php) is Statically CDN. |
| `CACHE_ENABLED` | Optional. When `true`, the file list from the CDN is cached for 1 hour. See [config/cache.php](config/cache.php) and [app/Services/FileService.php](app/Services/FileService.php).                                                                                                                                                                                                                                               |

## Project structure

| Path                                          | Purpose                                                      |
| --------------------------------------------- | ------------------------------------------------------------ |
| `app/Http/Controllers/FileController.php`     | Web: homepage, file detail page, download proxy.             |
| `app/Http/Controllers/Api/FileController.php` | API: list all files, list files by type.                     |
| `app/Services/FileService.php`                | Fetches and formats file list from CDN (`files/files.json`). |
| `routes/web.php`                              | Web routes (home, files show, download).                     |
| `routes/api.php`                              | API v1 routes.                                               |
| `resources/views/files/`                      | Blade views for file listing and file detail.                |

## Deployment

On push to `main`, GitHub Actions:

1. Builds the frontend (Vite) with `npm ci` and `npm run build`.
2. SCPs `public/build/` to the Forge server.
3. Triggers a Laravel Forge deployment.
4. Runs `php artisan optimize:clear` on the server.

Required repository secrets:

- `FORGE_SSH_HOST` — SSH host for the server.
- `FORGE_SSH_USER` — SSH user (e.g. `forge`).
- `SSH_PRIVATE_KEY` — Private key for SCP/SSH.
- `FORGE_SERVER_ID` — Forge server ID.
- `FORGE_SITE_ID` — Forge site ID.
- `FORGE_API_KEY` — Forge deploy token.

See [.github/workflows/deploy.yml](.github/workflows/deploy.yml).

---

## For developers and bots

### Base URL

Production: `https://blankfiles.com`. HTML and JSON are available; use `Accept: application/json` where applicable.

### Web routes

| Method | Path                                | Description                                                                                                                                |
| ------ | ----------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `GET`  | `/`                                 | Homepage: file list by category. Responds with JSON when `Accept: application/json`. Throttle: 30/min.                                     |
| `GET`  | `/files/{category}/{type}`          | SEO-friendly file detail page (e.g. `/files/document-spreadsheet/xlsx`). Constraints: `category`, `type` = `[A-Za-z0-9\-]+`.               |
| `GET`  | `/files/download/{category}/{type}` | Download proxy: streams the file with `Content-Disposition: attachment` (filename `blank.{type}` or `blank.{type}.zip`). Throttle: 60/min. |

### API routes (prefix `api/v1`, throttle 30/min)

| Method | Path                   | Response                                                                                                                                                        |
| ------ | ---------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `GET`  | `/api/v1/files`        | `{ "files": [ ... ], "meta": { "version", "generated_at", "count" } }`.                                                                                         |
| `GET`  | `/api/v1/files/{type}` | Same schema, filtered by extension.                                                                                                                              |
| `GET`  | `/api/v1/files/{category}/{type}` | Same schema with exactly one matching entry when found; `404` when missing.                                                                                   |

### Machine-friendly notes

- The canonical file catalog schema is defined in the [blank-files](https://github.com/filearchitect/blank-files) repo: `files/files.json` (key `files`, array of `{ type, url, category, package? }`).
- Download URLs: use the API `url` field for direct CDN access, or `GET /files/download/{category}/{type}` for a same-origin download with a predictable filename.
- Conditional requests are supported on API responses and sitemap (`ETag`, `Last-Modified`).

---

## Related

- [filearchitect/blank-files](https://github.com/filearchitect/blank-files) — Source of truth for the file list and blank file assets.

## MCP server (for agent marketplaces/registries)

This repository now includes a minimal MCP server that exposes Blank Files as tool calls.

- Script: `scripts/mcp/blankfiles-mcp.mjs`
- Run: `npm run mcp:server`
- Optional env: `BLANKFILES_BASE_URL` (default: `https://blankfiles.com`)

Available MCP tools:

- `list_blank_files` — list files, optional filters (`category`, `type`, `limit`)
- `files_by_type` — list entries by extension
- `file_by_category_type` — deterministic single lookup by category + extension

Example local MCP client config entry:

```json
{
  "mcpServers": {
    "blankfiles": {
      "command": "node",
      "args": ["/absolute/path/to/blankfiles-website/scripts/mcp/blankfiles-mcp.mjs"],
      "env": {
        "BLANKFILES_BASE_URL": "https://blankfiles.com"
      }
    }
  }
}
```

Registry submission helpers:

- Template metadata: `scripts/mcp/registry/server.json.template`
- Publish checklist: `scripts/mcp/registry/PUBLISHING.md`

## License

This project is licensed under the MIT License.
