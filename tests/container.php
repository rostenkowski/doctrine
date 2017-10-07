<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nette\DI\Compiler;
use Nette\DI\Config\Helpers;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;

function container(bool $debugMode = false, array $config = []): Container
{
	$loader = new ContainerLoader(TEMP_DIR, true);

	$class = $loader->load(function (Compiler $compiler) use ($debugMode, $config) {

		$compiler->addExtension('doctrine', new Extension());

		$defaults = [
			'parameters' => [
				'debugMode' => $debugMode,
				'appDir'    => __DIR__,
				'logDir'    => TEMP_DIR,
				'tempDir'   => TEMP_DIR,
			],
			'doctrine'   => [
				'connection' => [
					'driver' => 'pdo_sqlite',
					'path'   => TEMP_DIR . '/db.sqlite',
				],
			]
		];;

		$compiler->addConfig(Helpers::merge($defaults, $config));
	});

	return new $class;
}

function em(bool $debugMode = false): EntityManager
{
	$c = container($debugMode);
	$em = $c->getByType(EntityManager::class);
	$s = new SchemaTool($em);
	$s->dropSchema([
		$em->getClassMetadata(Character::class),
	]);
	$s->createSchema([
		$em->getClassMetadata(Character::class),
	]);

	return $em;
}
