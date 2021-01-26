<?php

namespace App;

use Exception;
use Symfony\Component\Dotenv\Dotenv;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

(new Dotenv())->bootEnv($root . '/.env');

$options = [
    'sslFolder' => realpath('/ssl'),
    'docroot' => realpath($root . '/public'),
    'accountKeyFileName' => 'account.key',
    'serverKeyFileName' => 'server.key',
    'serverCertFileName' => 'server.crt',
    'email' => $_ENV['ADMIN_EMAIL'],
    'domain' => $_ENV['DOMAIN'],
];

foreach ($options as $key => $value) {
    if (!$value) {
        throw new Exception('Variable not specified: "' . $key . '"');
    }
}

extract($options);
