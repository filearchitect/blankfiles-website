# Binary File Growth Roadmap

This roadmap is based on the current catalog in `filearchitect/blank-files` and excludes text-first formats (for example plain-text SVG use cases).

## Priority 1: Highest traffic potential

- `mkv` (video containers are frequent in upload validation)
- `3gp` (mobile camera upload edge case)
- `mpeg`, `mpg` (legacy media ingestion)
- `m4a` (common audio upload from mobile workflows)
- `opus` (modern streaming/voice workflows)
- `mid`, `midi` (audio pipeline edge case)
- `epub`, `mobi` (document/reader upload testing)
- `ttf`, `otf`, `woff`, `woff2` (font upload workflows)

## Priority 2: Data and developer workflows

- `parquet`
- `feather`
- `arrow`
- `h5`, `hdf5`
- `sqlite`, `sqlite3`, `db`
- `dbf`
- `npy`, `npz`
- `safetensors`
- `pcap`, `pcapng`

## Priority 3: Creative/professional binary formats

- `dng`, `cr2`, `nef`, `arw`, `raf`, `orf`, `rw2` (camera RAW files)
- `dwg`, `stl`, `step`, `stp` (CAD/3D engineering uploads)
- `blend1` or additional Blender package variants (tool-specific import tests)
- `icns` (macOS icon pipeline tests)

## Priority 4: Platform/package binaries

- `apk`, `aab` (Android package uploads)
- `ipa` (mobile package validation)
- `dmg`, `iso` (disk image workflows)
- `7z`, `rar` (archive ingestion beyond zip)

## Rollout recommendation

1. Add 10-15 formats from Priority 1 first.
2. Publish each batch as a changelog section on `blankfiles.com` and link new formats from `/upload-testing`.
3. Watch traffic by landing extension page and expand into the winning segment (media, data, or design).
