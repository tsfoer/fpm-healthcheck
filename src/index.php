<?php declare(strict_types=1);
require 'vendor/autoload.php';

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\GetRequest;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;

$options = getopt('', ['host:', 'port:']);
$host = $options['host'] ?? '127.0.0.1';
$port = $options['port'] ?? 9000;

$client     = new Client();
$connection = new NetworkSocket($host, (int)$port);
$request    = new GetRequest('/ping', "");
$request->setCustomVar("SCRIPT_NAME", '/ping');

try {
    $response = $client->sendRequest($connection, $request);
    exit($response->getBody() === 'pong' ? 0 : 99);
} catch (Throwable $e) {
    echo $e->getMessage();
    exit(99);
}