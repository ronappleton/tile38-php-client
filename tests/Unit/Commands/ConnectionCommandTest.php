<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

class ConnectionCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'auth' => ['secret'],
        'output' => ['json'],
        'ping' => [],
        'quit' => [],
    ];
}
