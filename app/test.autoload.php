<?php

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    passthru(sprintf(
        '%s "%s/bin/console" cache:clear --env=%s',
        PHP_BINARY,
        dirname(__DIR__),
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    ));
}

require __DIR__.'/autoload.php';
