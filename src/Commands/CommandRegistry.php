<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Channel\Chans;
use Ronappleton\Tile38PhpClient\Commands\Channel\Delchan;
use Ronappleton\Tile38PhpClient\Commands\Channel\Pdelchan;
use Ronappleton\Tile38PhpClient\Commands\Channel\Psubscribe;
use Ronappleton\Tile38PhpClient\Commands\Channel\Setchan;
use Ronappleton\Tile38PhpClient\Commands\Channel\Subscribe;
use Ronappleton\Tile38PhpClient\Commands\Connection\Auth;
use Ronappleton\Tile38PhpClient\Commands\Connection\Output;
use Ronappleton\Tile38PhpClient\Commands\Connection\Ping;
use Ronappleton\Tile38PhpClient\Commands\Connection\Quit;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command;
use Ronappleton\Tile38PhpClient\Commands\Key\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Key\Del;
use Ronappleton\Tile38PhpClient\Commands\Key\Drop;
use Ronappleton\Tile38PhpClient\Commands\Key\Exists;
use Ronappleton\Tile38PhpClient\Commands\Key\Expire;
use Ronappleton\Tile38PhpClient\Commands\Key\Fexists;
use Ronappleton\Tile38PhpClient\Commands\Key\Fget;
use Ronappleton\Tile38PhpClient\Commands\Key\Fset;
use Ronappleton\Tile38PhpClient\Commands\Key\Get;
use Ronappleton\Tile38PhpClient\Commands\Key\Jdel;
use Ronappleton\Tile38PhpClient\Commands\Key\Jget;
use Ronappleton\Tile38PhpClient\Commands\Key\Jset;
use Ronappleton\Tile38PhpClient\Commands\Key\Keys;
use Ronappleton\Tile38PhpClient\Commands\Key\Pdel;
use Ronappleton\Tile38PhpClient\Commands\Key\Persist;
use Ronappleton\Tile38PhpClient\Commands\Key\Rename;
use Ronappleton\Tile38PhpClient\Commands\Key\Renamenx;
use Ronappleton\Tile38PhpClient\Commands\Key\Set;
use Ronappleton\Tile38PhpClient\Commands\Key\Stats;
use Ronappleton\Tile38PhpClient\Commands\Key\Ttl;
use Ronappleton\Tile38PhpClient\Commands\Replication\Aof;
use Ronappleton\Tile38PhpClient\Commands\Replication\Aofmd5;
use Ronappleton\Tile38PhpClient\Commands\Replication\Aofshrink;
use Ronappleton\Tile38PhpClient\Commands\Replication\Follow;
use Ronappleton\Tile38PhpClient\Commands\Scripting\Evalna;
use Ronappleton\Tile38PhpClient\Commands\Scripting\Evalnasha;
use Ronappleton\Tile38PhpClient\Commands\Scripting\Evalro;
use Ronappleton\Tile38PhpClient\Commands\Scripting\Evalrosha;
use Ronappleton\Tile38PhpClient\Commands\Scripting\EvalScript;
use Ronappleton\Tile38PhpClient\Commands\Scripting\Evalsha;
use Ronappleton\Tile38PhpClient\Commands\Scripting\Script;
use Ronappleton\Tile38PhpClient\Commands\Search\Intersects;
use Ronappleton\Tile38PhpClient\Commands\Search\Nearby;
use Ronappleton\Tile38PhpClient\Commands\Search\Scan;
use Ronappleton\Tile38PhpClient\Commands\Search\Search;
use Ronappleton\Tile38PhpClient\Commands\Search\Within;
use Ronappleton\Tile38PhpClient\Commands\Server\Config;
use Ronappleton\Tile38PhpClient\Commands\Server\Flushdb;
use Ronappleton\Tile38PhpClient\Commands\Server\Gc;
use Ronappleton\Tile38PhpClient\Commands\Server\Healthz;
use Ronappleton\Tile38PhpClient\Commands\Server\Info;
use Ronappleton\Tile38PhpClient\Commands\Server\ReadonlyMode;
use Ronappleton\Tile38PhpClient\Commands\Server\Role;
use Ronappleton\Tile38PhpClient\Commands\Server\Server;
use Ronappleton\Tile38PhpClient\Commands\Utility\Raw;
use Ronappleton\Tile38PhpClient\Commands\Utility\Test;
use Ronappleton\Tile38PhpClient\Commands\Webhook\Delhook;
use Ronappleton\Tile38PhpClient\Commands\Webhook\Hooks;
use Ronappleton\Tile38PhpClient\Commands\Webhook\Pdelhook;
use Ronappleton\Tile38PhpClient\Commands\Webhook\Sethook;

/**
 * Maps each Tile38 wire command to its command class. This is the single
 * source of truth for the commands exposed by the client.
 */
final class CommandRegistry
{
    public const array COMMANDS = [

        'aof' => Aof::class,
        'aofmd5' => Aofmd5::class,
        'aofshrink' => Aofshrink::class,

        'auth' => Auth::class,

        'bounds' => Bounds::class,
        'chans' => Chans::class,

        'config' => Config::class,
        'del' => Del::class,
        'delchan' => Delchan::class,

        'delhook' => Delhook::class,
        'drop' => Drop::class,

        'eval' => EvalScript::class,
        'evalna' => Evalna::class,
        'evalnasha' => Evalnasha::class,
        'evalro' => Evalro::class,
        'evalrosha' => Evalrosha::class,
        'evalsha' => Evalsha::class,
        'exists' => Exists::class,
        'expire' => Expire::class,
        'fexists' => Fexists::class,
        'fget' => Fget::class,
        'flushdb' => Flushdb::class,
        'follow' => Follow::class,
        'fset' => Fset::class,
        'gc' => Gc::class,
        'get' => Get::class,
        'healthz' => Healthz::class,
        'hooks' => Hooks::class,
        'info' => Info::class,

        'intersects' => Intersects::class,
        'jdel' => Jdel::class,
        'jget' => Jget::class,
        'jset' => Jset::class,
        'keys' => Keys::class,
        'nearby' => Nearby::class,
        'output' => Output::class,
        'pdel' => Pdel::class,
        'pdelchan' => Pdelchan::class,
        'pdelhook' => Pdelhook::class,
        'persist' => Persist::class,
        'ping' => Ping::class,
        'psubscribe' => Psubscribe::class,
        'quit' => Quit::class,
        'raw' => Raw::class,
        'readonly' => ReadonlyMode::class,
        'rename' => Rename::class,
        'renamenx' => Renamenx::class,
        'role' => Role::class,
        'scan' => Scan::class,
        'script' => Script::class,
        'search' => Search::class,
        'server' => Server::class,
        'set' => Set::class,
        'setchan' => Setchan::class,
        'sethook' => Sethook::class,
        'stats' => Stats::class,
        'subscribe' => Subscribe::class,

        'test' => Test::class,
        'ttl' => Ttl::class,
        'within' => Within::class,
    ];
}
