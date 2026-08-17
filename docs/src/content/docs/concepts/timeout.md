---
title: Timeouts
description: How TIMEOUT works, and when it applies.
---

Tile38 can abort long-running commands. The `TIMEOUT` keyword is a **wrapper**:
you give it a number of seconds and a command, and Tile38 runs the command with
a deadline:

```text
TIMEOUT 0.5 SCAN fleet COUNT
```

## Usage

The client exposes this through `->timeout(float)` on scan, search, and
scripting commands:

```php
$client->scan('fleet')->count()->timeout(0.5)->execute();
// TIMEOUT 0.5 SCAN fleet COUNT

$client->nearby('fleet', Point::make(51.5, -0.12, 5000))
    ->ids()
    ->timeout(1.0)
    ->execute();
// TIMEOUT 1 NEARBY fleet IDS POINT ...
```

## Which commands support it

Per Tile38, only **scan/search** commands (`SCAN`, `SEARCH`, `INTERSECTS`,
`WITHIN`, `NEARBY`) and **scripting** commands (`EVAL`, `EVALRO`, `EVALNA`,
`EVALSHA`, `EVALROSHA`, `EVALNASHA`) respect a timeout. All other commands
ignore it, and attempting it on a write command is an error.

## Notes

- The timeout applies to the whole wrapped command.
- A command that exceeds the deadline returns a timeout error.
- Timeouts on the connection itself are configured in the constructor: see
  [Configuration](/getting-started/configuration/).
