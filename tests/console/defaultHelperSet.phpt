<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine;


use Nette\Configurator;
use Symfony\Component\Console\Application;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: that created helper set contains default helpers
 */
$container = container(false);

/** @var Application $app */
$app = $container->getByType(Application::class);
$helpers = $app->getHelperSet();

Assert::true($helpers->has('formatter'));
Assert::true($helpers->has('process'));
Assert::true($helpers->has('formatter'));
Assert::true($helpers->has('question'));
