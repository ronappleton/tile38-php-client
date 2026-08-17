---
title: Tile38 PHP Client
description:
  A PHP client for the Tile38 ultra-fast geospatial database and realtime
  geofencing server.
template: splash
hero:
  title: Geospatial queries, at the <span class="accent">speed of a map</span>.
  tagline:
    'A fluent PHP client for Tile38: store points, search nearby, fence in
    geofences, and build realtime location features in minutes.'
  actions:
    - text: Quick Start
      link: /getting-started/quick-start/
      icon: rocket
      variant: primary
    - text: View on GitHub
      link: https://github.com/ronappleton/tile38-php-client
      icon: external
---

This client wraps every Tile38 command behind a small, fluent API. You set a
point with one line, search within a radius with another, and publish geofence
events to channels and webhooks: all from PHP.

## Start here

- [Installation](/getting-started/installation/): requirements and
  `composer require`.
- [Quick Start](/getting-started/quick-start/): connect, set, get, and search in
  under a minute.
- [Configuration](/getting-started/configuration/): hosts, auth, timeouts, and
  output.

## What you can build

- [Track a fleet](/tutorials/fleet-tracking/) and run `nearby` queries against
  live positions.
- [Geofence alerts](/tutorials/geofence-alerts/): get notified when things enter
  or leave an area.
- [Postcode & geocoding lookup](/tutorials/postcode-lookup/): resolve
  coordinates from place keys, and back.
- [Delivery radius](/tutorials/delivery-radius/): find points within a radius,
  filtered by fields.
- [Realtime dispatch](/tutorials/driver-dispatch/): push job offers to drivers
  over webhooks.

## Under the hood

- [The Fluent API](/concepts/fluent-api/): how `client → command → execute()`
  works.
- [Object Types](/concepts/object-types/): points, bounds, geohashes, GeoJSON,
  and strings.
- [Search](/concepts/search/): `SCAN`, `SEARCH`, `NEARBY`, `WITHIN`,
  `INTERSECTS`.
- [Command Reference](/reference/commands/): every supported command, with
  signatures.
- [Version Compatibility](/reference/version-compatibility/): what works on
  which Tile38 release.
