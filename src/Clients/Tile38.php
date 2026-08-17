<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Clients;

use Redis;
use Ronappleton\Tile38PhpClient\Commands\CommandRegistry;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;
use Throwable;

use function count;
use function is_array;
use function strtolower;

/**
 * Fluent client for the Tile38 geospatial database. Commands are resolved
 * dynamically through __call and returned as fluent command objects.
 *
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Set set(string $key, string $id, \Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject $object)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Fset fset(string $key, string $id, string $field, mixed $value)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Jset jset(string $key, string $id, string $path, mixed $value)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Jget jget(string $key, string $id, string $path)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Get get(string $key, string $id)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Del del(string $key, string $id)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Drop drop(string $key)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Stats stats(string $key)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Keys keys(string $pattern)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Bounds bounds(string $key)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Expire expire(string $key, string $id, int $seconds)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Persist persist(string $key, string $id)
 * @method \Ronappleton\Tile38PhpClient\Commands\Key\Ttl ttl(string $key, string $id)
 * @method \Ronappleton\Tile38PhpClient\Commands\Search\Scan scan(string $key)
 * @method \Ronappleton\Tile38PhpClient\Commands\Search\Search search(string $key)
 * @method \Ronappleton\Tile38PhpClient\Commands\Search\Nearby nearby(string $key, \Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject $area)
 * @method \Ronappleton\Tile38PhpClient\Commands\Search\Within within(string $key, \Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject $area)
 * @method \Ronappleton\Tile38PhpClient\Commands\Search\Intersects intersects(string $key, \Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject $area)
 * @method \Ronappleton\Tile38PhpClient\Commands\Channel\Setchan setchan(string $name, \Ronappleton\Tile38PhpClient\Enums\SearchType|string $searchType, string $key, \Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject $area)
 * @method \Ronappleton\Tile38PhpClient\Commands\Channel\Delchan delchan(string $name)
 * @method \Ronappleton\Tile38PhpClient\Commands\Channel\Chans chans(string $pattern)
 * @method \Ronappleton\Tile38PhpClient\Commands\Channel\Pdelchan pdelchan(string $pattern)
 * @method \Ronappleton\Tile38PhpClient\Commands\Webhook\Sethook sethook(string $name, string $endpoint, \Ronappleton\Tile38PhpClient\Enums\SearchType|string $searchType, string $key, \Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject $area)
 * @method \Ronappleton\Tile38PhpClient\Commands\Webhook\Delhook delhook(string $name)
 * @method \Ronappleton\Tile38PhpClient\Commands\Webhook\Pdelhook pdelhook(string $pattern)
 * @method \Ronappleton\Tile38PhpClient\Commands\Webhook\Hooks hooks(string $pattern)
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\Info info()
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\Healthz healthz()
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\Server server()
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\Role role()
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\Config config(string $action, ...mixed $arguments)
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\Flushdb flushdb()
 * @method \Ronappleton\Tile38PhpClient\Commands\Server\ReadonlyMode readonly()
 * @method \Ronappleton\Tile38PhpClient\Commands\Replication\Follow follow(string $host, int $port)
 * @method \Ronappleton\Tile38PhpClient\Commands\Replication\Aof aof(string $action, string $key = '')
 * @method \Ronappleton\Tile38PhpClient\Commands\Replication\Aofmd5 aofmd5(string $key)
 * @method \Ronappleton\Tile38PhpClient\Commands\Scripting\Eval eval(string $script, array $keys, int $numKeys, array $arguments = [])
 * @method \Ronappleton\Tile38PhpClient\Commands\Scripting\Evalsha evalsha(string $sha, array $keys, int $numKeys, array $arguments = [])
 * @method \Ronappleton\Tile38PhpClient\Commands\Utility\Test test()
 * @method \Ronappleton\Tile38PhpClient\Commands\Utility\Raw raw(string $command, ...mixed $arguments)
 * @method static $this output(string $output)
 */
class Tile38
{
    private readonly Redis $client;

    public function __construct(
        string $host,
        int $port = 9851,
        ?string $password = null,
        float $timeout = 0.0,
        mixed $reserved = null,
        int $retryInterval = 0,
        float $readTimeout = 0.0,
        ?Redis $client = null,
    ) {
        $this->client = $client ?? new Redis();

        if ($client !== null) {
            return;
        }

        $this->client->connect($host, $port, $timeout, $reserved, $retryInterval, $readTimeout);

        if (!$password) {
            return;
        }

        $this->client->auth($password);
    }

    /**
     * Queue fluent client commands in a phpredis pipeline and flush them once.
     *
     * @param callable(self): void $callback
     *
     * @return array<int, mixed>
     */
    public function pipeline(callable $callback): array
    {
        $this->client->pipeline();

        try {
            $callback($this);
            $results = $this->client->exec();
        } catch (Throwable $exception) {
            $this->client->discard();

            throw $exception;
        }

        if (! is_array($results)) {
            return [];
        }

        return $results;
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private function setOutput(array $arguments): self
    {
        if (!count($arguments)) {
            throw new RequiredArgumentCount(1);
        }

        $this->client->rawCommand('OUTPUT', $arguments[0]);

        return $this;
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function __call(string $command, array $arguments = []): mixed
    {
        $command = strtolower($command);

        if ($command === 'output') {
            return $this->setOutput($arguments);
        }

        $class = CommandRegistry::COMMANDS[$command] ?? throw new CommandDoesNotExist($command);

        return new $class($this->client, $arguments);
    }
}
