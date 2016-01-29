<?php

# Workaround for PHP < 7
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    if (empty(ini_get('date.timezone'))) {
        ini_set('date.timezone', 'UTC');
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
