<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Query;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Nette\Utils\Paginator;

abstract class QueryFactory implements QueryFactoryInterface
{

	/**
	 * @var Paginator
	 */
	private $paginator;


	public function create(EntityManager $em): Query
	{
		return $this->configure($em);
	}


	public function getPaginator(): ?Paginator
	{
		return $this->paginator;
	}


	abstract protected function configure(EntityManager $em): Query;


	public function setPaginator(Paginator $paginator)
	{
		$this->paginator = $paginator;

		return $this;
	}


}
