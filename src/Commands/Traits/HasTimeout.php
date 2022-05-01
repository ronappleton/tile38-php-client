<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Traits;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

trait HasTimeout
{
    protected float $timeout = 0.0;
    
    public function timeout(float $timeout): Command
    {
        $this->timeout = $timeout;
        
        return $this;
    }
    
    protected function getCommand(): string
    {
        if ($this->timeout) {
            return sprintf('TIMEOUT %f %s', $this->timeout, $this->command);
        }
        
        return $this->command;
    }
}
