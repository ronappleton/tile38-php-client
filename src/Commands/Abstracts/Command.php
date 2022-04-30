<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Abstracts;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command as CommandInterface;

abstract class Command implements CommandInterface
{
    private float $timeout;

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout ?? 0.0;
    }

    public function setTimeout(float $timeout): CommandInterface
    {
        $this->timeout = $timeout;
        
        return $this;
    }
    
    protected function hasTimeout(): bool
    {
        return (bool) $this->getTimeout();
    }
    
    public function formatCommand(string $argument): string
    {
        return $this->hasTimeout()
            ? sprintf('TIMEOUT %f %s', $this->getTimeout(), $argument)
            : $argument;
    }
}
