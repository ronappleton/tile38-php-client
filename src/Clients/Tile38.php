<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Clients;

use Redis;
use Ronappleton\Tile38PhpClient\Commands\Builder;
use Ronappleton\Tile38PhpClient\Commands\BaseCommand;
use Ronappleton\Tile38PhpClient\Exceptions\PropertyDoesNotExist;

use function property_exists;

/**
 * @property $command
 */
class Tile38 extends Redis
{
    private readonly BaseCommand $command;
    
    /**
     * @phpcs:disable SlevomatCodingStandard.Numbers.RequireNumericLiteralSeparator.RequiredNumericLiteralSeparator
     */
    public function __construct(
        private readonly string $host, 
        private readonly int $port = 9851, 
        private readonly float $timeout = 0.0,
        private readonly mixed $reserved = null, 
        private readonly int $retryInterval = 0, 
        private readonly float $readTimeout = 0.0,
    ) {
        parent::__construct();
        
        $this->command = new BaseCommand($this);
    }
    
    public function initialiseConnection(null|string|array $credentials = null): void
    {
        $this->connect(
            $this->host,
            $this->port,
            $this->timeout,
            $this->reserved,
            $this->retryInterval,
            $this->readTimeout,
        );
        
        if (!$credentials) {
            return;
        }
        
        $this->auth($credentials);
    }

    public function tileCommand(): BaseCommand
    {
        return $this->command;
    }
}
