<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use Throwable;

class ObjectNotStringable extends \RuntimeException
{
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            'Class [%s] does not implement Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable',
            $class
        );
        
        parent::__construct($message, $code, $previous);
    }
}
