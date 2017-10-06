<?php

namespace Rostenkowski\Doctrine;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Environment;

$dir = dirname(__DIR__);

require "$dir/vendor/autoload.php";

Environment::setup();

define('TEMP_DIR', __DIR__ . '/temp/' . (string) lcg_value());
@mkdir(TEMP_DIR, 0755, true);

function container(bool $debugMode = false): Container
{
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler) use ($debugMode) {

		$compiler->addExtension('doctrine', new Extension());
		$compiler->addConfig([
			'parameters' => [
				'debugMode' => $debugMode,
				'appDir'    => __DIR__,
				'logDir'    => TEMP_DIR,
				'tempDir'   => TEMP_DIR,
			],
			'doctrine'   => [
				'default' => [
					'connection' => [
						'driver' => 'pdo_sqlite',
						'path'   => TEMP_DIR . '/db.sqlite',
					]
				]
			]
		]);
	});

	return new $class;
}

function em(bool $debugMode = false): EntityManager
{
	$c = container($debugMode);
	$em = $c->getByType(EntityManager::class);
	$s = new SchemaTool($em);
	$s->dropSchema([
		$em->getClassMetadata(Vendor::class),
	]);
	$s->createSchema([
		$em->getClassMetadata(Vendor::class),
	]);

	return $em;
}
