<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: doctrine extension in production mode
 */
$container = container(false);
Assert::false($container->hasService('doctrine.debugger'));
