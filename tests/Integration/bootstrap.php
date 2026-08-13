<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

if (!extension_loaded('redis')) {
    throw new RuntimeException(
        'Integration tests require the phpredis extension (ext-redis). Use the docker-compose test service.',
    );
}
