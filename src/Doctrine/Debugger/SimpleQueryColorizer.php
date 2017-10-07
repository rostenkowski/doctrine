<?php

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

	private static $keywordRegex;


	public function __construct()
	{
		self::$keywordRegex = '/\b(' . implode('|', self::$keywords) . ')\b/';
	}


	public function colorize(string $sql): string
	{
		// determine specific query type
		$type = strtolower(substr($sql, 0, 6));
		if (!in_array($type, ['select', 'update', 'delete', 'insert'])) {
			$type = '';
		}
		$template = '<span class="reserved">$1</span>';
		$sql = preg_replace(self::$keywordRegex, $template, $sql);

		return "<div class='query $type'>$sql</div>";
	}
}
