<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Enums\SearchType;

use function array_column;

class GeofenceTest extends IntegrationTestCase
{
    public function testSetchanAndChans(): void
    {
        $this->requireVersion('1.13.0');

        $name = $this->uniqueKey();

        $command = $this->client()->setchan($name, SearchType::Nearby, 'fleet', Point::make(33.5123, - 112.2693, 500));
        $command->fence();

        $response = $this->jsonResponse($command->execute());

        TestCase::assertTrue($response['ok'] ?? false);

        $chans = $this->client()->chans('*')->execute();

        TestCase::assertContains($name, $this->namesFromResponse($chans, 'chans', '/"name":"([^"]+)"/'));

        $this->client()->delchan($name)->execute();
    }

    public function testPdelchan(): void
    {
        $this->requireVersion('1.13.0');

        $name = $this->uniqueKey();

        $command = $this->client()->setchan($name, SearchType::Within, 'fleet', Point::make(33.5123, - 112.2693, 500));
        $command->fence();
        $command->execute();

        $this->client()->pdelchan($name)->execute();

        $chans = $this->client()->chans('*')->execute();

        TestCase::assertNotContains($name, $this->namesFromResponse($chans, 'chans', '/"name":"([^"]+)"/'));
    }

    public function testSethookAndHooks(): void
    {
        $this->requireVersion('1.13.0');

        $name = $this->uniqueKey();

        $command = $this->client()->sethook(
            $name,
            'http://example.com/hook',
            SearchType::Nearby,
            'fleet',
            Point::make(33.5123, - 112.2693, 500),
        );
        $command->fence();

        $response = $this->jsonResponse($command->execute());

        TestCase::assertTrue($response['ok'] ?? false);

        $hooks = $this->jsonResponse($this->client()->hooks('*')->execute());

        TestCase::assertContains($name, array_column($hooks['hooks'] ?? [], 'name'));

        $this->client()->delhook($name)->execute();
    }

    public function testPdelhook(): void
    {
        $this->requireVersion('1.13.0');

        $name = $this->uniqueKey();

        $command = $this->client()->sethook(
            $name,
            'http://example.com/hook',
            SearchType::Within,
            'fleet',
            Point::make(33.5123, - 112.2693, 500),
        );
        $command->fence();
        $command->execute();

        $this->client()->pdelhook($name)->execute();

        $hooks = $this->jsonResponse($this->client()->hooks('*')->execute());

        TestCase::assertNotContains($name, array_column($hooks['hooks'] ?? [], 'name'));
    }
}
