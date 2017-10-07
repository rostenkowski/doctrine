<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine;


use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Tools\Console\Command\ImportCommand;
use Doctrine\DBAL\Tools\Console\Command\RunSqlCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand;
use Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand;
use Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand;
use Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand;
use Doctrine\ORM\Tools\Console\Command\InfoCommand;
use Doctrine\ORM\Tools\Console\Command\RunDqlCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use Nette\DI\Statement;
use Rostenkowski\Doctrine\Debugger\TracyBar;
use Rostenkowski\Doctrine\Logger\FileLogger;
use Rostenkowski\Doctrine\Repository\Repository;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;

class Extension extends CompilerExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
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
			'enabled' => '%debugMode%',
			'factory' => TracyBar::class,
			'width'   => '960px',
			'height'  => '720px',
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
		'console'    => [
			'commands' => [
				'doctrine:info'           => InfoCommand::class,
				'doctrine:production'     => EnsureProductionSettingsCommand::class,
				'doctrine:repositories'   => GenerateRepositoriesCommand::class,
				'doctrine:proxies'        => GenerateProxiesCommand::class,
				'doctrine:schema-check'   => ValidateSchemaCommand::class,
				'doctrine:schema-update'  => UpdateCommand::class,
				'doctrine:schema-create'  => CreateCommand::class,
				'doctrine:schema-drop'    => DropCommand::class,
				'doctrine:cache-metadata' => MetadataCommand::class,
				'doctrine:cache-query'    => QueryCommand::class,
				'doctrine:cache-result'   => ResultCommand::class,
				'doctrine:query'          => RunDqlCommand::class,
				'doctrine:exec'           => RunSqlCommand::class,
				'doctrine:import'         => ImportCommand::class,
			],
		],
	];


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$this->config = $config = Helpers::expand($this->validateConfig($this->defaults), $builder->parameters);

		// create configuration
		$configuration = $builder->addDefinition($this->prefix('config'))
			->setFactory('Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration', [
				$config['entity'],
				$config['debugger']['enabled'],
				$config['proxy']['dir'],
				$this->prefix('@cache'),
			]);

		// create logger
		$log = $builder->addDefinition($this->prefix('log'))
			->setFactory(LoggerChain::class);
		if ($config['logger']['enabled']) {
			$builder->addDefinition($this->prefix('logger'))
				->setFactory($config['logger']['factory'], $config['logger']['args']);
			$log->addSetup('addLogger', [$this->prefix('@logger')]);
		}

		// create debugger
		if ($config['debugger']['enabled']) {
			$builder->addDefinition($this->prefix('debugger'))
				->setFactory(TracyBar::class)
				->addSetup('Tracy\Debugger::getBar()->addPanel(?);', ['@self'])
				->addSetup('setWidth', [$config['debugger']['width']])
				->addSetup('setHeight', [$config['debugger']['height']]);
			$log->addSetup('addLogger', [$this->prefix('@debugger')]);
		}

		// create cache
		$cache = $builder->addDefinition($this->prefix('cache'));
		if ($config['debugger']['enabled']) {
			$cache->setFactory(ArrayCache::class);
		} else {
			$cache->setFactory($config['cache']['factory'], $config['cache']['args']);
		}

		// set repository class
		$configuration->addSetup('setDefaultRepositoryClassName', [$config['repository']]);

		// set logger chain as logger
		$configuration->addSetup('setSQLLogger', [$this->prefix('@log')]);

		// create entity manager
		$builder->addDefinition($this->prefix('entityManager'))
			->setFactory('Doctrine\ORM\EntityManager::create', [$config['connection'], $this->prefix('@config')])
			->setAutowired(true);
	}


	public function beforeCompile()
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		// add console
		if ($builder->findByType(Application::class)) {
			$console = $builder->getDefinition($builder->getByType(Application::class));
		} else {
			$console = $builder->addDefinition($this->prefix('console'));
			$console->setFactory(Application::class, ['doctrine console']);
		}

		// create entity manager helper
		$em = $builder->addDefinition($this->prefix('em'));
		$em->setFactory(EntityManagerHelper::class);

		// create helper set
		if ($builder->findByType(HelperSet::class)) {
			$helpers = $builder->getDefinition($builder->getByType(HelperSet::class));
		} else {
			// default helper set
			$helpers = $builder->addDefinition($this->prefix('helpers'));
			$helpers->setFactory(HelperSet::class, [[
				new Statement(FormatterHelper::class),
				new Statement(DebugFormatterHelper::class),
				new Statement(ProcessHelper::class),
				new Statement(QuestionHelper::class),
			]]);
		}

		// add entity manager helper to application helper set
		$helpers->addSetup('set', [$this->prefix('@em'), 'em']);

		// set helper set as application helper set
		$console->addSetup('setHelperSet');

		// add doctrine commands
		foreach ($config['console']['commands'] as $commandName => $class) {
			$commandServiceName = 'command_' . preg_replace('/[^a-z]/i', '_', $commandName);
			$command = $builder->addDefinition($this->prefix($commandServiceName));
			$command->setFactory($class);
			$command->addSetup('setName', [$commandName]);
			$console->addSetup('add', [$this->prefix("@$commandServiceName")]);
		}
	}
}
