<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine;


use Tester\Environment;

umask(0002);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/container.php';

Environment::setup();

define('TEMP_DIR', __DIR__ . '/temp/' . (string) lcg_value());
@mkdir(TEMP_DIR, 0775, true);
