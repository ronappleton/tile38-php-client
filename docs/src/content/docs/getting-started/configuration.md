---
title: Configuration
description: Connection options, authentication, output format, and timeouts.
---

## Constructor

```php
new Tile38(
    string $host,
    int $port = 9851,
    ?string $password = null,
    float $timeout = 0.0,
    mixed $reserved = null,
    int $retryInterval = 0,
    float $readTimeout = 0.0,
    ?Redis $client = null,
)
```

| Parameter        | Default | Purpose                                          |
|------------------|---------|--------------------------------------------------|
| `$host`          |:       | Tile38 server host                               |
| `$port`          | `9851`  | Tile38 server port                               |
| `$password`      | `null`  | Runs `AUTH` on connect when set                  |
| `$timeout`       | `0.0`   | Connection timeout (seconds)                     |
| `$retryInterval` | `0`     | Retry interval for connecting                    |
| `$readTimeout`   | `0.0`   | Read timeout (seconds)                           |
| `$client`        | `null`  | Inject a preconfigured `Redis` client            |

## Examples

```php
// Default local server
$client = new Tile38('127.0.0.1');

// With auth and a 5 second connection timeout
$client = new Tile38('localhost', 9851, 'secret', 5.0);
```

## Output format

By default the client speaks RESP. You can switch the connection to JSON output:

```php
$client->output('json');
```

This changes the **server connection** output mode, so `execute()` responses come back
as JSON strings. It applies to every command on that connection.

## Timeouts

Two different things are called "timeout":

1. **Connection timeouts**: set via the constructor (`$timeout`, `$readTimeout`).
2. **Command timeouts**: Tile38 wraps scan/search/scripting commands with `TIMEOUT
   seconds`, which aborts a command that runs too long:

```php
$result = $client->scan('fleet')->count()->timeout(0.5)->execute();
```

See [Timeouts](/concepts/timeout/) for details.

## Injecting a Redis client

For tests or advanced setups you can supply your own phpredis client:

```php
use Redis;

$redis = new Redis();
$redis->connect('127.0.0.1', 9851);

$client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, $redis);
```
