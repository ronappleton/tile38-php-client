<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use RuntimeException;
use Throwable;

use function sprintf;

class RequiredArgumentCount extends RuntimeException
{
    public function __construct(int $argumentCountRequired = 0, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            'You have not provided the minimum argument count required of [%d] arguments.',
            $argumentCountRequired,
        );
        
        parent::__construct($message, $code, $previous);
    }
}
