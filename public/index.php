<?php

$minPhpVersion = '8.1';

if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run Zero. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'index.php';