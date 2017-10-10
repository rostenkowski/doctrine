<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Rostenkowski\Doctrine\Debugger\TracyBar;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: tracy debugger bar
 */
$debugger = new TracyBar(__DIR__ . '/../');

$debugger->setWidth('961px');
$debugger->setHeight('721px');

Assert::same('961px', $debugger->getWidth());
Assert::same('721px', $debugger->getHeight());

$debugger->setHeight('721px');
$debugger->startQuery('"START TRANSACTION"');
$debugger->stopQuery();
$debugger->startQuery('SELECT * FROM character');
$debugger->stopQuery();
$debugger->startQuery('DELETE FROM character WHERE id = ?');
$debugger->stopQuery();
$debugger->startQuery('INSERT INTO character (name) VALUES (?)');
$debugger->stopQuery();
$debugger->startQuery('UPDATE character SET (name = ?)', [0 => 'John Zoidberg']);
$debugger->stopQuery();
$debugger->startQuery('"COMMIT"');
$debugger->stopQuery();

Assert::matchFile(__DIR__ . '/expected-tab.html', $debugger->getTab());
Assert::matchFile(__DIR__ . '/expected-panel.html', $debugger->getPanel());
