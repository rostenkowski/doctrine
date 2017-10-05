<?php

namespace Rostenkowski\Doctrine;


use Doctrine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * TEST: entity name trait
 */
$em = em();

$vendor = new Vendor();
$vendor->setName('ASUS');

$em->persist($vendor);
$em->flush();

$vendor = $em->find(Vendor::class, 1);

Assert::same('ASUS', $vendor->getName());
