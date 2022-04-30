<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;

use function sprintf;
use function ucfirst;
use function class_exists;

/**
 * @method quit();
 * @method raw(string $command);
 * @method output(string $outputType);
 * @method auth(string $password);
 */
class Command
{
    private string $commandNamespace = __NAMESPACE__;
    
    public function __construct(private readonly Tile38 $client)
    {
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
