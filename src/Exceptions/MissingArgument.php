<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use Illuminate\Support\Str;
use RuntimeException;

class MissingArgument extends RuntimeException
{
    public function __construct(string|array $message, int $code = 0, ?Throwable $previous = null)
    {
        $count = 1;
        $argumentString = $message;

        if (is_array($message)) {
            $count = count($message);
            $argumentString = implode(', ', $message);
        }

        parent::__construct(
            sprintf('%s [%s] required!', Str::plural('argument', $count), $argumentString),
            $code,
            $previous
        );
    }
}
