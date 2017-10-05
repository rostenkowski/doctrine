<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: entity id trait
 */
$em = em();

$vendor = new Vendor();

$em->persist($vendor);
$em->flush();

$vendor = $em->find(Vendor::class, 1);

Assert::same(1, $vendor->getId());
