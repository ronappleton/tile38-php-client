# DESIGN — tile38-php-client documentation

Read-mode documentation site. Built with Starlight (Astro), self-hosted, served from
its own Docker image. This document records the world as shipped; it is written from
the built surface, not from intention.

## Direction contract

See the `DESIGN CONTRACT` comment at the top of every rendered page (first child of
`<body>` via the `PageFrame` override) and the opening comment of
`docs/src/styles/custom.css`.

- **Mode:** Read. Structure for comprehension first; the reading experience is the polish.
- **Direction:** map/geo developer tool. Pinned by the user; no concept roll.

## Color

Restrained-to-committed teal-green system with an amber survey accent.

### Dark (default for a developer scene)

| Token | Value | Use |
|---|---|---|
| `--sl-color-black` / `--sl-color-bg` | `#0d1615` | page ground |
| `--sl-color-bg-nav` | `#111c1a` | header / footer |
| `--sl-color-bg-inline-code` | `#182422` | inline code chip |
| `--sl-color-text` | `#d6e0dc` | body |
| `--sl-color-gray-3` | `#a8b6b1` | secondary text |
| `--sl-color-accent-low` | `#0f3d38` | selected / active tint |
| `--sl-color-accent` | `#2dd4bf` | buttons, focus |
| `--sl-color-accent-high` / text-accent | `#99f6e4` | links, headings accent |
| `--map-accent-amber` | `#fbbf24` | blockquote rule, survey marks |

### Light

| Token | Value | Use |
|---|---|---|
| `--sl-color-black` / `--sl-color-bg` | `#f5f7f2` | warm map-paper ground |
| `--sl-color-bg-nav` | `#ffffff` | header / footer |
| `--sl-color-bg-inline-code` | `#e9ede7` | inline code chip |
| `--sl-color-text` | `#1c2724` | body |
| `--sl-color-accent-low` | `#ccfbf1` | selected / active tint |
| `--sl-color-accent` | `#0f766e` | buttons, links (white-on-accent ≥ 5.4:1) |
| `--sl-color-accent-high` / text-accent | `#115e59` / `#0f766e` | links |
| `--map-accent-amber` | `#b45309` | blockquote rule, survey marks |

Contrast is verified for every token pair (≥ 4.5:1 body, ≥ 3:1 large).

## Typography

| Role | Face | Weight | Tracking |
|---|---|---|---|
| Display / headings | Familjen Grotesk Variable | 560–590 | −0.02em |
| Body / UI | IBM Plex Sans Variable | 400 / 600 | 0.01em |
| Code | JetBrains Mono Variable | 400 | — |

Faces are self-hosted via `@fontsource-variable` (woff2 bundled, no runtime network).

- Headings: tight line-height (1.12), more space above than below, an h2 hairline rule.
- Body measure: content width `54rem` (~65–75ch).
- Code: dark editor panel in the hero and syntax-highlighted blocks; `github-dark`
  Shiki theme.

## Components

- **Hero** (`Hero.astro`): two-column grid; display headline with an accent span, a
  one-line tagline, pill actions, and a terminal-flavored code window (`nearby.php`)
  proving the mechanism. Topographic contour SVG (generated Catmull-Rom geometry) as
  a faint backdrop — pure geometry, no illustration.
- **Logo**: rounded square tile with contour arcs + amber survey dots.
- **Blockquote**: amber rule on a tinted panel (used for "gotcha"/tip callouts).
- **Nav / sidebar**: teal accent for active states; Familjen Grotesk brand and sidebar
  section labels.

## Content structure

21 pages: getting started (3), concepts (5), reference (2, one generated from the
client's `CommandRegistry`), guides (3), tutorials (9). Simple + complex real-world
examples include fleet tracking, geofence alerts, store locator, asset check-in,
postcode/geocoding lookup, delivery radius, realtime dispatch, IoT roaming, and real
estate.

## Verified

- Impeccable detector: 0 findings across all built pages.
- Em-dash saturation removed from all body copy.
- All font/theme tokens bundled and self-contained in the Docker image.
