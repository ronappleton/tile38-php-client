<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ReplicationTest extends IntegrationTestCase
{
    public function testAofshrink(): void
    {
        $response = $this->jsonResponse($this->client()->aofshrink()->execute());

        TestCase::assertTrue($response['ok'] ?? false);
    }

    public function testAofmd5(): void
    {
        $response = $this->client()->aofmd5(0, 64)->execute();

        TestCase::assertNotFalse($response);
    }
}
