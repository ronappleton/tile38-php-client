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
    public function __construct(protected readonly Redis $client, protected array $arguments = []) {
        if (count($this->arguments) < $this->argumentCountRequired) {
            throw new RequiredArgumentCount($this->argumentCountRequired);
        }
    }
    
    public function execute(): mixed
    {
        return $this->client->rawCommand($this->getCommand(), ... $this->formatArguments((array) $this->arguments));
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
    
    public function output(string $output): Command
    {
        $this->client->rawCommand('OUTPUT', $output);
        
        return $this;
    }
    
    protected function getCommand(): string
    {
        return $this->command;
    }
}
