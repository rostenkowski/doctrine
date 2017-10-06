<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Debugger;


use Doctrine\DBAL\Logging\SQLLogger;
use Nette\Utils\Strings;
use Tracy\Dumper;
use Tracy\IBarPanel;

class TracyBar implements SQLLogger, IBarPanel
{

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

	private $totalTime;


	private function getTotalTime()
	{
		if ($this->totalTime === NULL) {
			foreach ($this->queries as $query) {
				$this->totalTime += $query['dur'];
			}
		}

		return $this->totalTime;
	}


	public function getPanel()
	{
		$count = count($this->queries);
		$color = $count ? 'green' : '#555555';
		$totalTime = $this->getTotalTime();
		$t = number_format($totalTime * 1000, 0, '.', '&nbsp;') . '&nbsp;ms';
		$template = $this->getTemplate('panel');
		$row = $this->getTemplate('query');
		$buffer = '';
		foreach ($this->queries as $i => $query) {
			$buffer .= sprintf($row,
				number_format(round($query['dur'] * 1000, 5), 1, '.', '&nbsp;'),
				$this->colorize($query['sql']),
				$this->dump($query['params'])
			);
		}

		return sprintf($template, $color, $t, $count, $buffer);
	}


	public function getTab()
	{
		$count = count($this->queries);
		$color = $count ? 'green' : '#555555';
		$totalTime = $this->getTotalTime();
		$time = number_format($totalTime * 1000, 0, '.', '&nbsp;') . ' ms';
		$template = $this->getTemplate('tab');

		return sprintf($template, $color, $time, $count);
	}


	private function getTemplate($name)
	{
		return file_get_contents(__DIR__ . "/templates/$name.html");
	}


	private function colorize($sql): string
	{
		$class = strtolower(substr($sql, 0, 6));
		if (!in_array($class, ['select', 'update', 'delete', 'insert'])) {
			$class = '';
		}
		$keywords = implode('|', [
			'DROP TABLE',
			'CREATE TABLE',
			'PRAGMA',
			'SELECT ',
			'FROM ',
			'WHERE ',
			'ORDER BY ',
			'GROUP BY ',
			'LEFT JOIN ',
			'INNER JOIN ',
			'UNION ALL',
			' AND ',
			' OR ',
			'UPDATE ',
			'INSERT ',
			'DELETE ',
			'START TRANSACTION',
			'COMMIT',
			'INTO ',
			'SET ',
			'VALUES ',
			'DEFAULT ',
			'PRIMARY KEY',
			'VARCHAR',
			'INTEGER',
			'TEXT',
			'NULL',
			'NOT NULL',
		]);
		$template = "<b>$1</b>";
		$sql = preg_replace("/($keywords)/i", $template, $sql);

		return "<div class='query $class'>$sql</div>";
	}


	private function dump($params)
	{
		if ($params) {

			return Dumper::toHtml($params, [Dumper::COLLAPSE => 1]);
		}

		return '';
	}


	public function startQuery($sql, array $params = NULL, array $types = NULL)
	{
		$this->start = (float) microtime(true);
		$this->queries[++$this->current] = ['sql' => Strings::trim($sql), 'params' => $params, 'types' => $types, 'dur' => 0];
	}


	public function stopQuery()
	{
		$this->queries[$this->current]['dur'] = (float) microtime(true) - $this->start;
	}
}
