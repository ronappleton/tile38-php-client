---
title: Version Compatibility
description: Minimum Tile38 version for every command and version-gated option, verified by the integration suite.
---

The integration test suite runs every command against Tile38 `1.13.0`, `1.17.0`,
`1.22.0`, `1.25.0`, `1.30.0`, `1.33.0`, and `1.38.0`. The versions below are the first
release each command or option is known to work with.

## Commands by minimum version

| Since   | Commands |
|---------|----------|
| 1.0.0   | `AUTH`, `PING`, `QUIT`, `DEL`, `DROP`, `EXPIRE`, `PERSIST`, `TTL`, `FSET`, `GET`, `KEYS`, `SET`, `STATS`, `SCAN`, `NEARBY`, `WITHIN`, `INTERSECTS`, `SERVER`, `GC`, `READONLY`, `FLUSHDB`, `FOLLOW`, `AOF`, `AOFMD5`, `AOFSHRINK`, `CONFIG`, `INFO`, `SETHOOK`, `DELHOOK`, `HOOKS`, `PDELHOOK` |
| 1.1.0   | `OUTPUT` |
| 1.3.0   | `BOUNDS` |
| 1.4.2   | `SEARCH` |
| 1.7.0   | `JSET`, `JGET`, `JDEL`, `PDEL` |
| 1.10.0  | `EVAL`, `EVALSHA`, `EVALNA`, `EVALNASHA`, `EVALRO`, `EVALROSHA`, `SCRIPT` |
| 1.13.0  | `CHANS`, `DELCHAN`, `PDELCHAN`, `SUBSCRIBE`, `PSUBSCRIBE`, `SETCHAN` |
| 1.14.5  | `RENAME`, `RENAMENX` |
| 1.16.0  | `TEST` |
| 1.17.0  | `TIMEOUT` |
| 1.24.1  | `HEALTHZ` |
| 1.32.0  | `ROLE` |
| 1.33.0  | `EXISTS`, `FEXISTS`, `FGET` |

`RAW` is a client-side passthrough, not a Tile38 command.

## Version-gated options and features

| Since   | Option / feature                                             |
|---------|--------------------------------------------------------------|
| 1.1.0   | `WITHFIELDS` on `GET`                                        |
| 1.3.0   | `EX` and `STRING` on `SET`                                   |
| 1.5.0   | `NX` / `XX` on `SET`                                         |
| 1.10.0  | `WHEREIN` clause                                             |
| 1.11.0  | `WHEREEVAL` clause                                           |
| 1.13.0  | `CLIP` search option, geofence pub/sub channels              |
| 1.25.0  | `CLIPBY` search option                                       |
| 1.26.0  | `SECTOR` area format                                         |
| 1.27.0  | `BUFFER` on `WITHIN` / `INTERSECTS`                          |
| 1.30.0  | `WHERE` filter expressions (`>`, `<`) and multiple `MATCH`   |
| 1.37.0  | `WHERE` regex (`=~`)                                         |
| 1.38.0  | `RX` on `SET` / `FSET`                                       |

## Server quirks to be aware of

- **CHANS JSON**: Tile38 versions before `1.17.3` emitted malformed JSON from `CHANS`
  (fixed in `1.17.3`). HOOKS and other commands were unaffected.
- **ROLE JSON**: the `ROLE` response JSON output was corrected in `1.33.2`.

## The integration stack

To re-run the full compatibility suite locally against every version:

```bash
docker compose up --build test
```

The versions under test are listed in `docker-compose.yml` (`TILE38_VERSIONS`). See
the project README for details.
