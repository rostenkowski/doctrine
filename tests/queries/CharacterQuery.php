<?php

namespace Rostenkowski\Doctrine;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Rostenkowski\Doctrine\Query\QueryFactory;

final class CharacterQuery extends QueryFactory
{

	protected function configure(EntityManager $em): Query
	{
		$qb = $em->createQueryBuilder();

		$qb->select('c')->from(Character::class, 'c');

		return $qb->getQuery();
	}

}
