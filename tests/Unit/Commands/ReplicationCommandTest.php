<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

class ReplicationCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'aof' => [],
        'aofmd5' => [100, 64],
        'aofshrink' => [],
        'follow' => ['leader', 9851],
    ];
}
