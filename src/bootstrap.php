<?php

namespace App;

use Exception;
use Symfony\Component\Dotenv\Dotenv;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

(new Dotenv())->bootEnv($root . '/.env');

$options = [
    'sslFolder' => realpath($root . '/ssl'),
    'docroot' => realpath($root . '/public'),
    'email' => $_ENV['ADMIN_EMAIL'],
    'domains' => explode(';', $_ENV['DOMAIN']),
];

foreach ($options as $key => $value) {
    if (!$value) {
        throw new Exception('Variable not specified: "' . $key . '"');
    }
}

extract($options);
