<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

class BaseCommandDoesNotExecute extends \RuntimeException
{
    public function __construct(
        string $message = 'The base command class does not execute',
        int $code = 0,
        ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
