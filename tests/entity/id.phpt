<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: entity id trait
 */
$em = em();

$character = new Character();

$em->persist($character);
$em->flush();

$character = $em->find(Character::class, 1);

Assert::same(1, $character->getId());
