<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: entity name trait
 */
$em = em();

$character = new Character();
$character->setName('John Zoidberg');

$em->persist($character);
$em->flush();

$character = $em->find(Character::class, 1);

Assert::same('John Zoidberg', $character->getName());
