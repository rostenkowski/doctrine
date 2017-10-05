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

$vendor1 = new Vendor();
$vendor2 = new Vendor();
$vendor3 = new Vendor();

$em->persist($vendor1);
$em->persist($vendor2);
$em->persist($vendor3);
$em->flush();

$repo = $em->getRepository(Vendor::class);

$results = $repo->search(new VendorQuery());
Assert::count(3, $results);

$q = new VendorQuery();
$p = new Nette\Utils\Paginator();
$p->setItemsPerPage(10);
$q->setPaginator($p);
Assert::type(Doctrine\ORM\Tools\Pagination\Paginator::class, $repo->search($q));
