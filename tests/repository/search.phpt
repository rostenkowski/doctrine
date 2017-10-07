<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Nette;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: repository
 */
$em = em();

$character1 = new Character();
$character2 = new Character();
$character3 = new Character();

$em->persist($character1);
$em->persist($character2);
$em->persist($character3);
$em->flush();

$repo = $em->getRepository(Character::class);

$results = $repo->search(new CharacterQuery());
Assert::count(3, $results);

$q = new CharacterQuery();
$p = new Nette\Utils\Paginator();
$p->setItemsPerPage(10);
$q->setPaginator($p);
Assert::type(Doctrine\ORM\Tools\Pagination\Paginator::class, $repo->search($q));
