<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ConnectionTest extends IntegrationTestCase
{
    public function testPing(): void
    {
        $response = $this->client()->ping()->execute();

        TestCase::assertNotFalse($response);
    }

    public function testServer(): void
    {
        $response = $this->jsonResponse($this->client()->server()->execute());

        TestCase::assertTrue($response['ok'] ?? false);
        TestCase::assertArrayHasKey('stats', $response);
    }

    public function testInfo(): void
    {
        $response = $this->client()->info()->execute();

        TestCase::assertNotFalse($response);
    }

    public function testHealthz(): void
    {
        $this->requireVersion('1.24.1');

        $response = $this->jsonResponse($this->client()->healthz()->execute());

        TestCase::assertTrue($response['ok'] ?? false);
    }

    public function testRole(): void
    {
        $this->requireVersion('1.32.0');

        $response = $this->client()->role()->execute();

        TestCase::assertNotFalse($response);

        if (!$this->versionAtLeast('1.33.2')) {
            return;
        }

        $decoded = $this->jsonResponse($response);

        TestCase::assertArrayHasKey('role', $decoded);
    }

    public function testConfigGet(): void
    {
        $response = $this->jsonResponse($this->client()->config('get', 'maxmemory')->execute());

        TestCase::assertTrue($response['ok'] ?? false);
    }

    public function testGc(): void
    {
        $response = $this->jsonResponse($this->client()->gc()->execute());

        TestCase::assertTrue($response['ok'] ?? false);
    }
}
