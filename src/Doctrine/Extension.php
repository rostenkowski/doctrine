<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine;


use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\DBAL\Logging\LoggerChain;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use Rostenkowski\Doctrine\Debugger\TracyBar;
use Rostenkowski\Doctrine\Logger\FileLogger;
use Rostenkowski\Doctrine\Repository\Repository;

class Extension extends CompilerExtension
{

	/**
	 * @var bool
	 */
	private $debugMode = false;

	/**
	 * @var array
	 */
	private $defaults = [
		'default' => [
			'connection' => [
				'driver'   => NULL,
				'path'     => NULL,
				'host'     => NULL,
				'dbname'   => NULL,
				'user'     => NULL,
				'password' => NULL,
			],
			'entity'     => [
				'%appDir%/entities'
			],
			'repository' => Repository::class,
			'debugger'   => [
				'enabled' => true,
				'factory' => TracyBar::class,
			],
			'logger'     => [
				'enabled' => true,
				'factory' => FileLogger::class,
				'args'    => ['%logDir%/query.log']
			],
			'cache'      => [
				'factory' => PhpFileCache::class,
				'args'    => ['%tempDir%/doctrine/cache']
			],
			'proxy'      => [
				'dir' => '%tempDir%/doctrine/proxies',
			],
			'function'   => [],
			'type'       => [],
		]
	];


	public function __construct(bool $debugMode)
	{
		$this->debugMode = $debugMode;
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$configs = Helpers::expand($this->validateConfig($this->defaults), $builder->parameters);
		foreach ($configs as $name => $config) {
			$configuration = $builder->addDefinition($this->prefix("{$name}.config"))
				->setFactory('Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration', [
					$config['entity'],
					$this->debugMode,
					$config['proxy']['dir'],
					$this->prefix("@{$name}.cache"),
				]);
			$log = $builder->addDefinition($this->prefix("{$name}.log"))
				->setFactory(LoggerChain::class);
			$configuration->addSetup('setDefaultRepositoryClassName', [$config['repository']]);
			$configuration->addSetup('setSQLLogger', [$this->prefix("@{$name}.log")]);
			if ($this->debugMode && $config['debugger']['enabled']) {
				$builder->addDefinition($this->prefix("{$name}.debugger"))
					->setFactory(TracyBar::class)
					->addSetup('Tracy\Debugger::getBar()->addPanel(?);', ['@self']);
				$log->addSetup('addLogger', [$this->prefix("@{$name}.debugger")]);
			}
			if ($config['logger']['enabled']) {
				$builder->addDefinition($this->prefix("{$name}.logger"))
					->setFactory($config['logger']['factory'], $config['logger']['args']);
				$log->addSetup('addLogger', [$this->prefix("@{$name}.logger")]);
			}
			$builder->addDefinition($this->prefix("{$name}.em"))
				->setFactory('Doctrine\ORM\EntityManager::create', [$config['connection'], $this->prefix("@{$name}.config")])
				->setAutowired($name === 'default');
		}
	}

}
