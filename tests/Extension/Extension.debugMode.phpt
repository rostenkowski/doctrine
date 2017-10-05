<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: doctrine extension in debug mode
 */
$container = container(true);

Assert::type(Doctrine\Common\Cache\ArrayCache::class, $container->getService('doctrine.default.cache'));

Assert::true($container->hasService('doctrine.default.debugger'));
