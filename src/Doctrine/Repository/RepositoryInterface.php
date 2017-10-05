<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Repository;


use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Query;
use Rostenkowski\Doctrine\Query\QueryFactory;

interface RepositoryInterface extends ObjectRepository
{

	public function search(QueryFactory $qf, bool $fetchJoinCollection = true, int $mode = Query::HYDRATE_OBJECT);

}
