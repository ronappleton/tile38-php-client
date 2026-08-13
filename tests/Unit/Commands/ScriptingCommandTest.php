<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

class ScriptingCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'eval' => ['return KEYS[1]', 1, 'mykey'],
        'evalsha' => ['d8bc1591', 0],
        'evalna' => ['return 1', 0],
        'evalnasha' => ['d8bc1591', 0],
        'evalro' => ['return 1', 0],
        'evalrosha' => ['d8bc1591', 0],
        'script' => ['flush'],
    ];
}
