---
title: Command Reference
description:
  Every command supported by the client, with its wire keyword, method, minimum
  Tile38 version, and class.
---

> This page is generated from `CommandRegistry` — run
> `npm run generate:commands` in `docs/` to refresh it after the client changes.

Every command is dispatched by name through the client. The minimum version is
the first Tile38 release the command is known to work with; see
[Version Compatibility](/reference/version-compatibility/) for the full picture.

| Command      | Method                  | Since  | Class          |
| ------------ | ----------------------- | ------ | -------------- |
| `AOF`        | `$client->aof()`        | 1.0.0  | `Aof`          |
| `AOFMD5`     | `$client->aofmd5()`     | 1.0.0  | `Aofmd5`       |
| `AOFSHRINK`  | `$client->aofshrink()`  | 1.0.0  | `Aofshrink`    |
| `AUTH`       | `$client->auth()`       | 1.0.0  | `Auth`         |
| `BOUNDS`     | `$client->bounds()`     | 1.3.0  | `Bounds`       |
| `CHANS`      | `$client->chans()`      | 1.13.0 | `Chans`        |
| `CONFIG`     | `$client->config()`     | 1.0.0  | `Config`       |
| `DEL`        | `$client->del()`        | 1.0.0  | `Del`          |
| `DELCHAN`    | `$client->delchan()`    | 1.13.0 | `Delchan`      |
| `DELHOOK`    | `$client->delhook()`    | 1.0.0  | `Delhook`      |
| `DROP`       | `$client->drop()`       | 1.0.0  | `Drop`         |
| `EVAL`       | `$client->eval()`       | 1.10.0 | `EvalScript`   |
| `EVALNA`     | `$client->evalna()`     | 1.10.0 | `Evalna`       |
| `EVALNASHA`  | `$client->evalnasha()`  | 1.10.0 | `Evalnasha`    |
| `EVALRO`     | `$client->evalro()`     | 1.10.0 | `Evalro`       |
| `EVALROSHA`  | `$client->evalrosha()`  | 1.10.0 | `Evalrosha`    |
| `EVALSHA`    | `$client->evalsha()`    | 1.10.0 | `Evalsha`      |
| `EXISTS`     | `$client->exists()`     | 1.33.0 | `Exists`       |
| `EXPIRE`     | `$client->expire()`     | 1.0.0  | `Expire`       |
| `FEXISTS`    | `$client->fexists()`    | 1.33.0 | `Fexists`      |
| `FGET`       | `$client->fget()`       | 1.33.0 | `Fget`         |
| `FLUSHDB`    | `$client->flushdb()`    | 1.0.0  | `Flushdb`      |
| `FOLLOW`     | `$client->follow()`     | 1.0.0  | `Follow`       |
| `FSET`       | `$client->fset()`       | 1.0.0  | `Fset`         |
| `GC`         | `$client->gc()`         | 1.0.0  | `Gc`           |
| `GET`        | `$client->get()`        | 1.0.0  | `Get`          |
| `HEALTHZ`    | `$client->healthz()`    | 1.24.1 | `Healthz`      |
| `HOOKS`      | `$client->hooks()`      | 1.0.0  | `Hooks`        |
| `INFO`       | `$client->info()`       | 1.0.0  | `Info`         |
| `INTERSECTS` | `$client->intersects()` | 1.0.0  | `Intersects`   |
| `JDEL`       | `$client->jdel()`       | 1.7.0  | `Jdel`         |
| `JGET`       | `$client->jget()`       | 1.7.0  | `Jget`         |
| `JSET`       | `$client->jset()`       | 1.7.0  | `Jset`         |
| `KEYS`       | `$client->keys()`       | 1.0.0  | `Keys`         |
| `NEARBY`     | `$client->nearby()`     | 1.0.0  | `Nearby`       |
| `OUTPUT`     | `$client->output()`     | 1.1.0  | `Output`       |
| `PDEL`       | `$client->pdel()`       | 1.7.0  | `Pdel`         |
| `PDELCHAN`   | `$client->pdelchan()`   | 1.13.0 | `Pdelchan`     |
| `PDELHOOK`   | `$client->pdelhook()`   | 1.0.0  | `Pdelhook`     |
| `PERSIST`    | `$client->persist()`    | 1.0.0  | `Persist`      |
| `PING`       | `$client->ping()`       | 1.0.0  | `Ping`         |
| `PSUBSCRIBE` | `$client->psubscribe()` | 1.13.0 | `Psubscribe`   |
| `QUIT`       | `$client->quit()`       | 1.0.0  | `Quit`         |
| `RAW`        | `$client->raw()`        | —      | `Raw`          |
| `READONLY`   | `$client->readonly()`   | 1.0.0  | `ReadonlyMode` |
| `RENAME`     | `$client->rename()`     | 1.14.5 | `Rename`       |
| `RENAMENX`   | `$client->renamenx()`   | 1.14.5 | `Renamenx`     |
| `ROLE`       | `$client->role()`       | 1.32.0 | `Role`         |
| `SCAN`       | `$client->scan()`       | 1.0.0  | `Scan`         |
| `SCRIPT`     | `$client->script()`     | 1.10.0 | `Script`       |
| `SEARCH`     | `$client->search()`     | 1.4.2  | `Search`       |
| `SERVER`     | `$client->server()`     | 1.0.0  | `Server`       |
| `SET`        | `$client->set()`        | 1.0.0  | `Set`          |
| `SETCHAN`    | `$client->setchan()`    | 1.13.0 | `Setchan`      |
| `SETHOOK`    | `$client->sethook()`    | 1.0.0  | `Sethook`      |
| `STATS`      | `$client->stats()`      | 1.0.0  | `Stats`        |
| `SUBSCRIBE`  | `$client->subscribe()`  | 1.13.0 | `Subscribe`    |
| `TEST`       | `$client->test()`       | 1.16.0 | `Test`         |
| `TTL`        | `$client->ttl()`        | 1.0.0  | `Ttl`          |
| `WITHIN`     | `$client->within()`     | 1.0.0  | `Within`       |
