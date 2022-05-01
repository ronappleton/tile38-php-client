<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Ronappleton\Tile38PhpClient\Clients\Tile38;

$client = new Tile38('127.0.0.1', 9851);

$response = $client->chans('*')->output('json')->execute();

var_dump($response);

dd(\Ronappleton\Tile38PhpClient\Enums\GeoJsonType::values());
