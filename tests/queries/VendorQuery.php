<?php

namespace Rostenkowski\Doctrine;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Rostenkowski\Doctrine\Query\QueryFactory;

final class VendorQuery extends QueryFactory
{

	protected function configure(EntityManager $em): Query
	{
		$qb = $em->createQueryBuilder();

		$qb->select('v')->from(Vendor::class, 'v');

		return $qb->getQuery();
	}

}
