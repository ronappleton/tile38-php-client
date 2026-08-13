<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Clients;

use Redis;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;

use function class_exists;
use function count;
use function sprintf;
use function strtolower;
use function ucfirst;

/**
 * Channel Group
 *
 * @method chan(string $channelSearchPattern);
 * @method delchan(string $pattern);
 * @method pdelchan(string $pattern);
 * @method psubscribe(string $pattern);
 * @method setchan(string $name, mixed $searchType, string $key, mixed $area);
 * @method subscribe(string $pattern);
 * Connection Group
 * @method auth(string $password);
 * @method output(string $output);
 * @method ping();
 * @method quit();
 * Key Group
 * @method bounds(string $key, string $id);
 * @method del(string $key, string $id);
 * @method drop(string $key);
 * @method exists(string $key, string $id);
 * @method expire(string $key, string $id, int $seconds);
 * @method fexists(string $key, string $id, string $field);
 * @method fget(string $key, string $id, string $field);
 * @method fset(string $key, string $id, string $field, mixed $value);
 * @method get(string $key, string $id);
 * @method jdel(string $key, string $id, string $path);
 * @method jget(string $key, string $id, string $path);
 * @method jset(string $key, string $id, string $path, mixed $value);
 * @method keys(string $pattern);
 * @method pdel(string $key, string $pattern);
 * @method persist(string $key, string $id);
 * @method rename(string $key, string $newKey);
 * @method renamenx(string $key, string $newKey);
 * @method set(string $key, string $id, mixed $object);
 * @method stats(string $key);
 * @method ttl(string $key, string $id);
 * Scripting Group
 * @method eval(string $script, int $numKeys);
 * @method evalna(string $script, int $numKeys);
 * @method evalnasha(string $sha, int $numKeys);
 * @method evalro(string $script, int $numKeys);
 * @method evalrosha(string $sha, int $numKeys);
 * @method evalsha(string $sha, int $numKeys);
 * @method script(string $subCommand);
 * Search Group
 * @method intersects(string $key, mixed $area);
 * @method nearby(string $key, mixed $area);
 * @method scan(string $key);
 * @method search(string $key);
 * @method within(string $key, mixed $area);
 * Server Group
 * @method config(string $subCommand);
 * @method flushdb();
 * @method follow(string $host, int $port);
 * @method gc();
 * @method healthz();
 * @method info();
 * @method readonly();
 * @method role();
 * @method server();
 * Replication Group
 * @method aof(int $pos);
 * @method aofmd5(int $pos, int $size);
 * @method aofshrink();
 * Utility Group
 * @method test();
 * Webhook Group
 * @method delhook(string $name);
 * @method hooks(string $pattern);
 * @method pdelhook(string $pattern);
 * @method sethook(string $name, string $endpoint, mixed $searchType, string $key, mixed $area);
 *
 * @method raw(string $command, mixed $arguments);
 */
class Tile38
{
    private const array COMMAND_ALIASES = [
        'eval' => 'EvalScript',
        'readonly' => 'ReadonlyMode',
    ];

    private readonly Redis $client;

    private string $commandNamespace = 'Ronappleton\Tile38PhpClient\Commands';
    
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

        $className = self::COMMAND_ALIASES[$command] ?? ucfirst($command);
        $classFqdn = sprintf('%s\\%s', $this->commandNamespace, $className);

        if (!class_exists($classFqdn)) {
            throw new CommandDoesNotExist($classFqdn);
        }

        return new $classFqdn($this->client, $arguments);
    }
}
