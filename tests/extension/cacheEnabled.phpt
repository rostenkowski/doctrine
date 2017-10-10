<?php

namespace Rostenkowski\Doctrine;


use Doctrine\Common\Cache\ApcuCache;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: doctrine extension cache setup
 */
$container = container(true, [
	'doctrine' => [
		'cache' => [
			'enabled' => true,
		]
	]
]);
Assert::type(ApcuCache::class, $container->getService('doctrine.cache'));
