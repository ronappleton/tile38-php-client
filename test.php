<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Ronappleton\Tile38PhpClient\Clients\Tile38;

$client = new Tile38(host:'127.0.0.1', port: 9851);
$client->initialiseConnection();

$client->command->output('json');
$response = $client->command->server();
var_dump($response);
