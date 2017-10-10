<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Debugger;


use Doctrine\DBAL\Logging\SQLLogger;
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

	/**
	 * debugger panel max width
	 *
	 * @var string
	 */
	private $width = '960px';

	/**
	 * debugger panel max height
	 *
	 * @var string
	 */
	private $height = '720px';


	public function getHeight(): string
	{
		return $this->height;
	}


	public function setHeight(string $height)
	{
		$this->height = $height;
	}


	public function getPanel()
	{
		$totalQueries = count($this->queries);
		$color = $totalQueries ? 'green' : '#555555';
		$timeCaption = $this->formatTime($this->getTotalTime());
		$panel = $this->getTemplate('panel');
		$row = $this->getTemplate('query');
		$buffer = '';
		$colorizer = new SimpleQueryColorizer();
		foreach ($this->queries as $query) {
			$buffer .= sprintf($row,
				$this->formatTime($query['dur']),
				$colorizer->colorize($query['sql'], true),
				$this->dump($query['params'])
			);
		}

		return sprintf($panel, $color, $timeCaption, $totalQueries, $this->width, $this->height, $buffer);
	}


	public function getTab()
	{
		$count = count($this->queries);
		$color = $count ? 'green' : '#555555';
		$time = $this->formatTime($this->getTotalTime());
		$template = $this->getTemplate('tab');

		return sprintf($template, $color, $time, $count);
	}


	private function formatTime($microseconds)
	{
		return number_format($microseconds * 1000, 1, '.', ' ') . ' ms';
	}


	private function getTotalTime()
	{
		if ($this->totalTime === NULL) {
			foreach ($this->queries as $query) {
				$this->totalTime += $query['dur'];
			}
		}

		return $this->totalTime;
	}


	private function getTemplate($name)
	{
		return file_get_contents(__DIR__ . "/templates/$name.html");
	}


	private function dump($params)
	{
		if ($params) {

			return Dumper::toHtml($params, [Dumper::COLLAPSE => 1]);
		}

		return '';
	}


	public function getWidth(): string
	{
		return $this->width;
	}


	public function setWidth(string $width)
	{
		$this->width = $width;
	}


	public function startQuery($sql, array $params = NULL, array $types = NULL)
	{
		$this->start = (float) microtime(true);
		$this->queries[++$this->current] = ['sql' => trim($sql, " \t\n\r\0\x0B\""), 'params' => $params, 'types' => $types, 'dur' => 0];
	}


	public function stopQuery()
	{
		$this->queries[$this->current]['dur'] = (float) microtime(true) - $this->start;
	}

}
