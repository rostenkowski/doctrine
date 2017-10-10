<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Debugger;


class SimpleQueryColorizer
{

	private static $keywords = [
		'DROP TABLE',
		'CREATE TABLE',
		'PRAGMA',
		'SELECT',
		'FROM',
		'WHERE',
		'ORDER BY',
		'GROUP BY',
		'LEFT JOIN',
		'INNER JOIN',
		'UNION ALL',
		'AND',
		'OR',
		'UPDATE',
		'INSERT',
		'DELETE',
		'START TRANSACTION',
		'COMMIT',
		'INTO',
		'SET',
		'VALUES',
		'DEFAULT',
		'PRIMARY KEY',
		'VARCHAR',
		'INTEGER',
		'TEXT',
		'IN',
		'IS',
		'NULL',
		'NOT NULL',
	];

	private static $newline = [
		'SELECT',
		'FROM',
		'WHERE',
		'ORDER BY',
		'GROUP BY',
		'LEFT JOIN',
		'INNER JOIN',
		'UNION ALL',
		'AND',
		'OR',
	];

	private static $keywordRegex;

	private static $newlineRegex;


	public function __construct()
	{
		self::$keywordRegex = '/\b(' . implode('|', self::$keywords) . ')\b/';
		self::$newlineRegex = '/\b(' . implode('|', self::$newline) . ')\b/';
	}


	public function colorize(string $sql, bool $format = false): string
	{
		if ($format) {
			$sql = $this->format($sql);
		}

		// each query type has custom colors
		$type = strtolower(substr($sql, 0, 6));
		if (!in_array($type, ['select', 'update', 'delete', 'insert'])) {
			$type = '';
		}
		$template = '<span class="reserved">$1</span>';
		$sql = preg_replace(self::$keywordRegex, $template, $sql);

		return "<div class='query $type'>$sql</div>";
	}


	public function format(string $sql): string
	{
		$template = '<br>$1';
		$sql = preg_replace(self::$newlineRegex, $template, $sql);

		if (substr($sql, 0, 4) === '<br>') {
			$sql = substr($sql, 4);
		}

		return $sql;
	}
}
