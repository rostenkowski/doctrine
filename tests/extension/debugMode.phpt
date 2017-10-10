<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: doctrine extension in debug mode
 */
$container = container(true);
Assert::true($container->hasService('doctrine.debugger'));
