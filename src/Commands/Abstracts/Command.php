<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Abstracts;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command as CommandInterface;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable;
use Ronappleton\Tile38PhpClient\Exceptions\ObjectNotStringable;
use Ronappleton\Tile38PhpClient\Exceptions\TypeException;

abstract class Command implements CommandInterface
{
    protected string $command = '';
    
    public function __construct(
        protected readonly Tile38 $client,
        protected array $arguments = [],
        protected float $timeout = 0.0)
    {
    }
    
    public function execute(): Redis|array|string|bool
    {
        return $this->sendCommand($this->command, $this->arguments);
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return round($this->timeout ?? 0.0, 4);
    }

    public function setTimeout(float $timeout): Command
    {
        $this->timeout = $timeout;
        
        return $this;
    }
    
    protected function hasTimeout(): bool
    {
        return (bool) $this->getTimeout();
    }
    
    public function formatArguments(array $arguments): array
    {
        $args = [];
        
        foreach ($arguments as $argument) {
            $args[] = match (gettype($argument)) {
                'boolean', 'integer', 'double', 'string' => (string)$argument,
                'array' => $argument,
                'object' => $this->getArgumentString($argument),
                default => throw new TypeException(gettype($argument)),
            };
        }
        
        return $args;
    }
    
    public function getArgumentString(object $argument): string
    {
        if (! $argument instanceof Stringable) {
            throw new ObjectNotStringable(get_class($argument));
        }
        
        return sprintf(' %s', $argument->toString());
    }
    
    protected function sendCommand(string $command, mixed $arguments): Redis|array|string|bool
    {
        if ($this->hasTimeout()) {
            $this->client->rawCommand('TIMEOUT', $this->getTimeout());
        }

        return $this->client->rawCommand($command, ...$this->formatArguments((array) $arguments));
    }
}
