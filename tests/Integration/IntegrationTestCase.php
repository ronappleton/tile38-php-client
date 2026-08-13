<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use function array_column;
use function array_filter;
use function array_values;
use function preg_match_all;
use function is_array;
use function is_string;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Clients\Tile38;

use function bin2hex;
use function getenv;
use function json_decode;
use function random_bytes;
use function sprintf;
use function version_compare;

use const JSON_THROW_ON_ERROR;

abstract class IntegrationTestCase extends TestCase
{
    protected static ?Tile38 $client = null;

    protected static string $version = '';

    public static function setUpBeforeClass(): void
    {
        $host = getenv('TILE38_HOST') ?: '127.0.0.1';
        $port = (int) (getenv('TILE38_PORT') ?: '9851');
        self::$version = getenv('TILE38_VERSION') ?: '';

        self::$client = new Tile38($host, $port);
        self::$client->output('json');
    }

    protected function client(): Tile38
    {
        return self::$client;
    }

    protected function uniqueKey(): string
    {
        return sprintf('t38:%s', bin2hex(random_bytes(4)));
    }

    /**
     * @return array<string, mixed>
     */
    protected function jsonResponse(mixed $response): array
    {
        if (!is_string($response)) {
            return (array) $response;
        }

        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return is_array($decoded)
            ? $decoded
            : (array) $decoded;
    }

    /**
     * Extracts the value of a top-level key from a JSON response. Older Tile38
     * versions (e.g. CHANS before 1.17.3) emitted malformed JSON, so names are
     * also recovered from the raw payload via regex when decoding fails.
     *
     * @return array<int, string>
     */
    protected function namesFromResponse(mixed $response, string $key, string $namePattern): array
    {
        if (!is_string($response)) {
            return [];
        }

        $decoded = json_decode($response, true);

        if (is_array($decoded) && isset($decoded[$key]) && is_array($decoded[$key])) {
            $names = array_column($decoded[$key], 'name');

            return array_values(array_filter($names, 'is_string'));
        }

        preg_match_all($namePattern, $response, $matches);

        return $matches[1] ?? [];
    }

    protected function versionAtLeast(string $version): bool
    {
        if (self::$version === '') {
            return true;
        }

        return version_compare(self::$version, $version, '>=');
    }

    protected function requireVersion(string $version): void
    {
        if ($this->versionAtLeast($version)) {
            return;
        }

        self::markTestSkipped(
            sprintf('Requires Tile38 >= %s (running %s).', $version, self::$version ?: 'unknown'),
        );
    }
}
