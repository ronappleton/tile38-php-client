<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\BaseCommandDoesNotExecute;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command as AbstractCommand;

use function sprintf;
use function ucfirst;
use function class_exists;

/**
 * @method quit();
 * @method raw(string $command, mixed $arguments);
 * @method output(string $outputType);
 * @method auth(string $password);
 * @method ttl(string $key, string $id);
 */
class BaseCommand extends AbstractCommand
{
    private string $commandNamespace = __NAMESPACE__;

    public function execute(): Redis|array|string|bool
    {
        throw new BaseCommandDoesNotExecute();
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
        
        return (new $classFqdn($this->client, $arguments, $this->getTimeout()))->execute();
    }
}
