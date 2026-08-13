---
title: The Fluent API
description: How client, command, and execute work together.
---

Every Tile38 command is a small class. The client is a factory that turns a method
call into a command object, and commands are sent only when you call `execute()`.

## The shape of a call

```php
$client->set('fleet', 'truck1', Point::make(51.5, -0.12))->execute();
//       └─cmd─┘  └─key─┘  └─id──┘  └───────object───────┘  └─send─┘
```

1. **`$client->set(...)`**: the client looks up the command in its registry and
   returns a `Set` command, passing your arguments to its constructor.
2. **`->field('speed', 90)`**: optional builder methods add keywords to the command.
3. **`->execute()`**: sends the assembled command over the wire and returns the
   response.

## The command registry

The client exposes every supported command through `__call`. The mapping lives in a
single `CommandRegistry`, grouped by category:

```php
use Ronappleton\Tile38PhpClient\Commands\CommandRegistry;

$class = CommandRegistry::COMMANDS['nearby']; // Search\Nearby
```

Because the registry is the source of truth, unknown commands throw:

```php
$client->doesNotExist(); // Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist
```

## Commands are cheap objects

Command instances are constructed per call and hold no shared state, so you can build
them, chain options, and discard them freely:

```php
$search = $client->nearby('fleet', Point::make(51.5, -0.12, 1000));
$search->where('speed', 50, '+inf')->limit(25);

$count = $search->count()->execute(); // sends NEARBY ... COUNT
```

## Arguments

Arguments are typed and formatted for you:

- scalars are cast to strings,
- arrays are flattened into separate tokens,
- objects (`Point`, `Bounds`, `GeoHash`, `GeoJson`, …) expand into their keyword
  tokens (`POINT`, `BOUNDS`, `HASH`, `OBJECT`, …),
- a `BackedEnum` (like `SearchType`) expands to its value.

```php
use Ronappleton\Tile38PhpClient\Enums\SearchType;

$client->setchan('near-me', SearchType::Nearby, 'fleet', Point::make(51.5, -0.12, 500));
```

## Output

Every command also exposes `output()` as a convenience:

```php
$client->chans('*')->output('json')->execute();
```
