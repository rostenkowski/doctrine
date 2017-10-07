<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Query;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Nette\Utils\Paginator;

interface QueryFactoryInterface
{

	public function create(EntityManager $em): Query;


	public function getPaginator(): ?Paginator;


	public function setPaginator(Paginator $paginator);
}
