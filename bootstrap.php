<?php

declare (strict_types=1);

use Pimple\Container;
use UMA\DCA\Bitstamp;
use UMA\DCA\SharedProvider;

define('APP_NAME', 'dca');
define('APP_VERSION', '0.0.0');

require_once __DIR__ . '/vendor/autoload.php';

$cnt = new Container([
    'settings' => parse_ini_file(__DIR__ . '/settings.ini', true, INI_SCANNER_TYPED)
]);

$cnt->register(new Bitstamp\Provider)
    ->register(new SharedProvider);

return $cnt;
