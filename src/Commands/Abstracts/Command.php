<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Abstracts;

use Redis;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command as CommandInterface;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable;
use Ronappleton\Tile38PhpClient\Exceptions\ObjectNotStringable;
use Ronappleton\Tile38PhpClient\Exceptions\InvalidType;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;

use function gettype;
use function sprintf;
use function count;

abstract class Command implements CommandInterface
{
    protected string $command = '';
    
    protected int $argumentCountRequired = 0;

    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(
        protected readonly Redis $client,
        protected array $arguments = [],
        protected string $outputType = 'json',
    ) {
        if (count($this->arguments) < $this->argumentCountRequired) {
            throw new RequiredArgumentCount($this->argumentCountRequired);
        }
    }
    
    public function execute(): mixed
    {
        $this->sendCommand('OUTPUT', $this->outputType);

        if ($this->hasTimeout()) {
            $this->command = sprintf('TIMEOUT %f %s', $this->getTimeout(), $this->command);
        }
        
        return $this->sendCommand($this->command, $this->arguments);
    }
    
    public function getTimeout(): float
    {
        return $this->timeout ?? 0.0;
    }

    public function setTimeout(float $timeout): Command
    {
        $this->timeout = $timeout;
        
        return $this;
    }
    
    public function hasTimeout(): bool
    {
        return (bool) $this->getTimeout();
    }

    /**
     * @param array<int, mixed> $arguments
     * 
     * @return array<int, mixed>
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    public function formatArguments(array $arguments): array
    {
        $args = [];
        
        foreach ($arguments as $argument) {
            $args[] = match (gettype($argument)) {
                'boolean', 'integer', 'double', 'string' => (string) $argument,
                'array' => $argument,
                'object' => $this->getArgumentString($argument),
                default => throw new InvalidType(gettype($argument)),
            };
        }
        
        return $args;
    }
    
    public function getArgumentString(object $argument): string
    {
        if (! $argument instanceof Stringable) {
            throw new ObjectNotStringable($argument::class);
        }
        
        return sprintf(' %s', $argument->toString());
    }
    
    protected function sendCommand(string $command, mixed $arguments): Redis|array|string|bool
    {
        dump($command, ... $this->formatArguments((array) $arguments));
        return $this->client->rawCommand($command, ... $this->formatArguments((array) $arguments));
    }
}
