<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine;


use Nette\Configurator;
use Nette\DI\Statement;
use Symfony\Component\Console\Application;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: that existing custom console application service is used
 */
$config = [
	'services' => [
		'customConsole' => new Statement(Application::class, ['app console'])
	]
];
$container = container(false, $config);
/** @var Application $app */
$app = $container->getByType(Application::class);
Assert::same('app console', $app->getName());
Assert::true($app->has('doctrine:info'));
