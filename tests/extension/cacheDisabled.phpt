<?php

namespace Rostenkowski\Doctrine;


use Doctrine\Common\Cache\ArrayCache;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: doctrine extension cache setup
 */
$container = container(true, [
	'doctrine' => [
		'cache' => [
			'enabled' => false,
		]
	]
]);
Assert::type(ArrayCache::class, $container->getService('doctrine.cache'));
