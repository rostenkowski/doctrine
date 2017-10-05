<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Rostenkowski\Doctrine\Query\QueryFactory;

class Repository extends EntityRepository implements RepositoryInterface
{


	public function search(QueryFactory $qf, bool $fetchJoinCollection = true, int $mode = Query::HYDRATE_OBJECT)
	{
		$query = $qf->create($this->_em);

		if ($p = $qf->getPaginator()) {

			$query->setFirstResult($p->getOffset());
			$query->setMaxResults($p->getItemsPerPage());

			return new Paginator($query, $fetchJoinCollection);
		}

		return $query->getResult($mode);
	}

}
