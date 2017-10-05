<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Rostenkowski\Doctrine\Debugger\TracyBar;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: tracy debugger bar
 */
$debugger = new TracyBar();
$debugger->startQuery('"START TRANSACTION"');
$debugger->stopQuery();
$debugger->startQuery('SELECT * FROM vendor');
$debugger->stopQuery();
$debugger->startQuery('DELETE FROM vendor WHERE id = ?');
$debugger->stopQuery();
$debugger->startQuery('INSERT INTO vendor (name) VALUES (?)');
$debugger->stopQuery();
$debugger->startQuery('UPDATE vendor SET (name = ?)', [0 => 'KINGSTON']);
$debugger->stopQuery();
$debugger->startQuery('"COMMIT"');
$debugger->stopQuery();

Assert::matchFile(__DIR__ . '/tab.html', $debugger->getTab());
Assert::matchFile(__DIR__ . '/panel.html', $debugger->getPanel());
