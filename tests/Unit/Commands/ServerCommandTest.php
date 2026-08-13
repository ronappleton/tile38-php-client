<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

class ServerCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'config' => ['get', 'requirepass'],
        'flushdb' => [],
        'gc' => [],
        'readonly' => [],
        'healthz' => [],
        'info' => [],
        'role' => [],
        'server' => [],
    ];
}
