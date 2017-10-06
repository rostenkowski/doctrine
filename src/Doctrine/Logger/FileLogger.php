<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Logger;


use Doctrine\DBAL\Logging\SQLLogger;

class FileLogger implements SQLLogger
{

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var float
	 */
	private $start;

	/**
	 * @var int
	 */
	private $current;

	/**
	 * @var array
	 */
	private $queries = [];


	public function __construct(string $file)
	{
		$this->file = $file;

		register_shutdown_function(function () {
			file_put_contents($this->file, str_repeat('-', 100) . PHP_EOL . PHP_EOL, FILE_APPEND);
		});
	}


	public function startQuery($sql, array $params = NULL, array $types = NULL)
	{
		$this->start = (float) microtime(true);
		$this->queries[++$this->current] = ['sql' => $sql, 'params' => $params, 'types' => $types, 'dur' => 0];
	}


	public function stopQuery()
	{
		$q = $this->queries[$this->current];
		$q['dur'] = (float) microtime(true) - $this->start;
		$log = sprintf("%s \n\n%s \n\n%s ms \n\n%s \n\n",
			"#$this->current:",
			$q['sql'],
			round($q['dur'] * 1000, 3),
			var_export($q['params'], true)
		);
		file_put_contents($this->file, $log, FILE_APPEND);
	}

}
