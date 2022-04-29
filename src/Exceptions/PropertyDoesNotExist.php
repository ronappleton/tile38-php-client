<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use RuntimeException;
use Throwable;

use function sprintf;

class PropertyDoesNotExist extends RuntimeException
{
    public function __construct(string $name, string $class, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Property [%s] does not exist on this class [%s]', $name, $class);
        
        parent::__construct($message, $code, $previous);
    }
}
