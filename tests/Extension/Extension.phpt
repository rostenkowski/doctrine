<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: doctrine extension in production mode
 */
$container = container(false);

Assert::type(Doctrine\Common\Cache\PhpFileCache::class, $container->getService('doctrine.default.cache'));

Assert::false($container->hasService('doctrine.default.debugger'));
