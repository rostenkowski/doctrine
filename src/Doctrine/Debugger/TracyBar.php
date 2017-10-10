<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Debugger;


use Doctrine\DBAL\Logging\SQLLogger;
use Nette\Utils\Html;
use Tracy\Dumper;
use Tracy\Helpers;
use Tracy\IBarPanel;
use const DEBUG_BACKTRACE_IGNORE_ARGS;
use function count;
use function debug_backtrace;
use function dirname;
use function strlen;
use function substr;

class TracyBar implements SQLLogger, IBarPanel
{

	/**
	 * @var string
	 */
	private $appDir;

	/**
	 * @var string
	 */
	private $tempDir;

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


	public function __construct(string $appDir, string $tempDir)
	{
		$this->appDir = $appDir;
		$this->tempDir = $tempDir;
	}


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

			$link = Helpers::editorUri($query['file'], $query['line']);
			$linkText = '…/' . substr($query['file'], strlen(dirname($this->appDir)) + 1) . ':' . $query['line'];
			$a = Html::el('a')->setAttribute('href', $link)->setText($linkText);
			$buffer .= sprintf($row,
				$this->formatTime($query['dur']),
				$colorizer->colorize($query['sql'], true),
				$this->dump($query['params']),
				(string) $a
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

		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

		foreach ($trace as $i => $item) {
			if (isset($trace[$i]['file'])) {
				if (
					substr($trace[$i]['file'], 0, strlen($this->tempDir)) === $this->tempDir
					||
					substr($trace[$i]['file'], 0, strlen($this->appDir)) === $this->appDir
				) {
					break;
				}
			}
		}

		$this->queries[++$this->current] = [
			'sql'    => trim($sql, " \t\n\r\0\x0B\""),
			'params' => $params,
			'types'  => $types,
			'dur'    => 0,
			'file'   => $trace[$i]['file'],
			'line'   => $trace[$i]['line'],
		];
	}


	public function stopQuery()
	{
		$this->queries[$this->current]['dur'] = (float) microtime(true) - $this->start;
	}


}
