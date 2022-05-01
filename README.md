# tile38-php-client
A php client for the Tile38 Ultra Fast Geospatial Database

[![License](http://poser.pugx.org/ronappleton/tile38-php-client/license)](https://packagist.org/packages/ronappleton/tile38-php-client)
[![PHP Version Require](http://poser.pugx.org/ronappleton/tile38-php-client/require/php)](https://packagist.org/packages/ronappleton/tile38-php-client)

## Commands Covered - Updated as Implemented

| Group       | Command Name    | Command   |
|-------------|-----------------|-----------|
| Channels    | CHANS           | ❌         |
| Channels    | DELCHAN         | ❌         |
| Channels    | PDELCHAN        | ❌         |
| Channels    | PSUBSCRIBE      | ❌         |
| Channels    | SETCHAN         | ❌         |
| Channels    | SUBSCRIBE       | ❌         |
| ---------   | ----------      | -------   |
| Connection  | AUTH            | ✅         |
| Connection  | OUTPUT          | ✅         |
| Connection  | PING            | ✅         |
| Connection  | QUIT            | ✅         |
| Connection  | TIMEOUT         | ✅         |
| ---------   | ----------      | -------   |
| Keys        | BOUNDS          | ❌         |
| Keys        | DEL             | ❌         |
| Keys        | DROP            | ❌         |
| Keys        | EXPIRE          | ❌         |
| Keys        | FSET            | ❌         |
| Keys        | GET             | ❌         |
| Keys        | JDEL            | ❌         |
| Keys        | JGET            | ❌         |
| Keys        | JSET            | ❌         |
| Keys        | KEYS            | ❌         |
| Keys        | PDEL            | ❌         |
| Keys        | PERSIST         | ❌         |
| Keys        | RENAME          | ❌         |
| Keys        | RENAMENX        | ❌         |
| Keys        | SET             | ❌         |
| Keys        | STATS           | ✅         |
| Keys        | TTL             | ✅         |
| ---------   | ----------      | -------   |
| Scripting   | EVAL            | ❌         |
| Scripting   | EVALNA          | ❌         |
| Scripting   | EVALNASHA       | ❌         |
| Scripting   | EVALRO          | ❌         |
| Scripting   | EVALROSHA       | ❌         |
| Scripting   | EVALSHA         | ❌         |
| Scripting   | SCRIPT EXISTS   | ❌         |
| Scripting   | SCRIPT FLUSH    | ❌         |
| Scripting   | SCRIPT LOAD     | ❌         |
| ---------   | ----------      | -------   |
| Search      | INTERSECTS      | ❌         |
| Search      | NEARBY          | ❌         |
| Search      | SCAN            | ❌         |
| Search      | SEARCH          | ❌         |
| Search      | WITHIN          | ❌         |
| Server      | CONFIG GET      | ❌         |
| Server      | CONFIG REWRITE  | ❌         |
| Server      | CONFIG SET      | ❌         |
| Server      | FLUSHDB         | ❌         |
| Server      | GC              | ❌         |
| Server      | READONLY        | ❌         |
| Server      | SERVER          | ✅         |
| ---------   | ----------      | -------   |
| Webhooks    | DELHOOK         | ❌         |
| Webhooks    | HOOKS           | ❌         |
| Webhooks    | PDELHOOK        | ❌         |
| Webhooks    | SETHOOK         | ❌         |
| ---------   | ----------      | -------   |
| Utility     | TEST            | ❌         |
| Utility     | RAW             | ✅         |
