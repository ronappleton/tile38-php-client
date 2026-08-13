<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ScriptingTest extends IntegrationTestCase
{
    public function testEval(): void
    {
        $this->requireVersion('1.10.0');

        $response = $this->jsonResponse($this->client()->eval('return 2 * 3', 0)->execute());

        TestCase::assertSame(6, $response['result'] ?? null);
    }

    public function testEvalWithKeysAndArgs(): void
    {
        $this->requireVersion('1.10.0');

        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', ['POINT', '33.5', '-112.3'])->execute();

        $response = $this->client()->eval(
            'return tile38.call("GET", KEYS[1], ARGV[1], "POINT")',
            1,
            $key,
            'truck1',
        )->execute();

        TestCase::assertNotFalse($response);
    }

    public function testScriptLoadExistsFlush(): void
    {
        $this->requireVersion('1.10.0');

        $load = $this->jsonResponse($this->client()->script('load', 'return 2 * 3')->execute());

        TestCase::assertArrayHasKey('result', $load);

        $sha = $load['result'];

        $exists = $this->jsonResponse($this->client()->script('exists', $sha)->execute());

        TestCase::assertSame([1], $exists['result'] ?? null);

        $this->client()->script('flush')->execute();

        $exists = $this->jsonResponse($this->client()->script('exists', $sha)->execute());

        TestCase::assertSame([0], $exists['result'] ?? null);
    }
}
