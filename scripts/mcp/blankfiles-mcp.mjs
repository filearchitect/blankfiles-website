#!/usr/bin/env node

import { McpServer } from "@modelcontextprotocol/sdk/server/mcp.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import * as z from "zod/v4";

const BASE_URL = (process.env.BLANKFILES_BASE_URL || "https://blankfiles.com").replace(/\/+$/, "");

async function fetchJson(pathname) {
    const url = `${BASE_URL}${pathname}`;
    const response = await fetch(url, {
        headers: {
            "accept": "application/json",
            "user-agent": "blankfiles-mcp/1.0.0",
        },
    });

    if (!response.ok) {
        const body = await response.text();
        throw new Error(`Request failed (${response.status}) for ${url}: ${body.slice(0, 500)}`);
    }

    return response.json();
}

function asText(data) {
    return JSON.stringify(data, null, 2);
}

const server = new McpServer({
    name: "blankfiles-mcp",
    version: "1.0.0",
});

server.registerTool(
    "list_blank_files",
    {
        description: "List blank files from blankfiles.com. Optional filters: category and type.",
        inputSchema: {
            category: z.string().optional().describe("Category slug, for example: document-spreadsheet"),
            type: z.string().optional().describe("File extension, for example: xlsx"),
            limit: z.number().int().min(1).max(500).optional().describe("Max results to return (default 200)"),
        },
    },
    async ({ category, type, limit }) => {
        const payload = await fetchJson("/api/v1/files");
        let files = payload.files || [];

        if (category) {
            files = files.filter((file) => file.category === category);
        }

        if (type) {
            files = files.filter((file) => file.type === type);
        }

        const capped = files.slice(0, limit || 200);
        const result = {
            files: capped,
            meta: {
                source: `${BASE_URL}/api/v1/files`,
                total_filtered: files.length,
                returned: capped.length,
            },
        };

        return {
            content: [
                {
                    type: "text",
                    text: asText(result),
                },
            ],
        };
    }
);

server.registerTool(
    "files_by_type",
    {
        description: "Get blank files by extension/type from blankfiles.com.",
        inputSchema: {
            type: z.string().describe("File extension, for example: pdf, xlsx, png"),
        },
    },
    async ({ type }) => {
        const result = await fetchJson(`/api/v1/files/${encodeURIComponent(type)}`);
        return {
            content: [
                {
                    type: "text",
                    text: asText(result),
                },
            ],
        };
    }
);

server.registerTool(
    "file_by_category_type",
    {
        description: "Get one deterministic blank file entry by category and extension.",
        inputSchema: {
            category: z.string().describe("Category slug, for example: document-spreadsheet"),
            type: z.string().describe("File extension, for example: xlsx"),
        },
    },
    async ({ category, type }) => {
        const result = await fetchJson(`/api/v1/files/${encodeURIComponent(category)}/${encodeURIComponent(type)}`);
        return {
            content: [
                {
                    type: "text",
                    text: asText(result),
                },
            ],
        };
    }
);

const transport = new StdioServerTransport();

try {
    await server.connect(transport);
} catch (error) {
    process.stderr.write(`Failed to start blankfiles MCP server: ${error?.message || error}\n`);
    process.exit(1);
}
