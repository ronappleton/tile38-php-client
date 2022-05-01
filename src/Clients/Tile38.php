<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Clients;

use Redis;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;

use function class_exists;

/**
 * Channel Group
 * @method chan(string $channelSearchPattern);
 * Connection Group
 * @method auth(string $password);
 * @method output(string $outputType);
 * @method ping();
 * @method quit();
 *
 * @method raw(string $command, mixed $arguments);
 *
 *
 * @method ttl(string $key, string $id);
 */
class Tile38
{
    private readonly Redis $client;

    private string $commandNamespace = 'Ronappleton\Tile38PhpClient\Commands';
    
    public function __construct(
        string $host, 
        int $port = 9_851, 
        string $password = null,
        float $timeout = 0.0,
        mixed $reserved = null, 
        int $retryInterval = 0, 
        float $readTimeout = 0.0,
    ) {
        $this->client = new Redis;
        
        $this->client->connect(
            $host,
            $port,
            $timeout,
            $reserved,
            $retryInterval,
            $readTimeout,
        );

        if (!$password) {
            return;
        }

        $this->client->auth($password);
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function __call(string $command, array $arguments = []): mixed
    {
        $classFqdn = sprintf('%s\\%s', $this->commandNamespace, ucfirst($command));

        if (!class_exists($classFqdn)) {
            throw new CommandDoesNotExist($classFqdn);
        }

        return (new $classFqdn($this->client, $arguments))->execute();
    }
}
