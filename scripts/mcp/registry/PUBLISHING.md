# Blank Files MCP Registry Publishing

This guide prepares `blankfiles-mcp` for listing in the MCP Registry.

## 1. Pick your namespace

Use one of:

- `io.github.filearchitect/blankfiles-mcp` (GitHub-backed namespace)
- `com.blankfiles/blankfiles-mcp` (domain-backed namespace, if you verify domain ownership)

## 2. Publish an npm package for the MCP server

The registry entry in `server.json.template` expects:

- package name: `@filearchitect/blankfiles-mcp`
- package version: `1.0.0`
- stdio entrypoint that starts the MCP server

The published package `package.json` should include:

```json
{
  "name": "@filearchitect/blankfiles-mcp",
  "version": "1.0.0",
  "type": "module",
  "bin": {
    "blankfiles-mcp": "./blankfiles-mcp.mjs"
  },
  "mcpName": "io.github.filearchitect/blankfiles-mcp"
}
```

## 3. Validate and publish with MCP Publisher

Install CLI:

```bash
npm install -g @modelcontextprotocol/mcp-publisher
```

Authenticate:

```bash
mcp-publisher login github
```

Copy and edit template:

```bash
cp scripts/mcp/registry/server.json.template server.json
```

Validate:

```bash
mcp-publisher verify
```

Publish:

```bash
mcp-publisher publish
```

## 4. Notes

- Keep `name` in `server.json` stable forever once published.
- Use immutable package versions and bump for each release.
- If npm package name changes, update `packages[].identifier` and republish a new version.
