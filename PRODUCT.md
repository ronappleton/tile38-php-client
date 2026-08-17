# PRODUCT — tile38-php-client documentation site

## What this is

The documentation website for `ronappleton/tile38-php-client`, a PHP client for
the Tile38 ultra-fast geospatial database and realtime geofencing server. The
site is a Starlight (Astro) static docs site, served from its own Docker image,
co-located in `docs/`.

## Audience and scene

PHP and Laravel developers building location-aware features (fleet tracking,
delivery radius, geofence alerts, postcode/geocoding lookup, IoT, logistics,
real-estate search). They read docs at a desk, often in a dark IDE, splitting
between bright office light and dim evening coding sessions — so the site ships
both light and dark themes, dark default.

## Mode (impeccable)

Read. Visitors come to understand: how to install, the fluent command API,
object types, search/geofencing/webhook/scripting concepts, and real-world
usage. Structure for comprehension first, then make the reading experience worth
staying in.

## Visual identity (brief-pinned)

Map/geo-inspired developer tool. Deep teal/green as the carrying color, warm
amber accent for highlights, topographic-contour + grid/tile motifs as geometry
(never illustration), a distinctive grotesk display face (Familjen Grotesk),
workhorse body (IBM Plex Sans), JetBrains Mono for code. Polished
syntax-highlighted code blocks, memorable hero, readable 65–75ch measure.

## Content promise

Comprehensive: installation, quick start, configuration, concepts (fluent API,
object types, search, output formats, timeout), full command reference
auto-generated from the client's `CommandRegistry`, version-compatibility
matrix, guides (geofencing, webhooks, Lua scripting), and real-world tutorials —
simple (fleet tracking, geofence alerts, store locator, asset check-in, postcode
lookup) and complex (delivery radius, realtime dispatch, IoT roaming,
real-estate search).

## Constraints

- Static, self-contained (fonts and assets bundled; no runtime network).
- Served via a Docker image (multi-stage node build → nginx) as a `docs` compose
  service.
- Docs content and code examples must match the actual client API exactly.
