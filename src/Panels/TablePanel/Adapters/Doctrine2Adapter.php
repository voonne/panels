<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\TablePanel\Adapters;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nette\SmartObject;
use Voonne\Panels\InvalidArgumentException;
use Voonne\Panels\InvalidStateException;


class Doctrine2Adapter implements IAdapter
{

	use SmartObject;

	/**
	 * @var QueryBuilder
	 */
	private $queryBuilder;

	/**
	 * @var integer
	 */
	private $limit;

	/**
	 * @var integer
	 */
	private $offset;


	public function __construct(QueryBuilder $queryBuilder)
	{
		$this->queryBuilder = $queryBuilder;
	}


	public function setLimit($limit)
	{
		$this->limit = $limit;
	}


	public function setOffset($offset)
	{
		$this->offset = $offset;
	}


	public function getResults($filters, $sort, $order)
	{
		if (!isset($this->queryBuilder->getDQLPart('from')[0])) {
			throw new InvalidArgumentException('From statement must be declared in QueryBuilder.');
		}

		if(!isset($this->limit)) {
			throw new InvalidStateException('Property limit must be set.');
		}

		if(!isset($this->offset)) {
			throw new InvalidStateException('Property offset must be set.');
		}

		$queryBuilder = clone $this->queryBuilder;

		foreach ($filters as $name => $value) {
			$queryBuilder->where($queryBuilder->getDQLPart('from')[0]->getAlias() . '.' . $name . ' LIKE :' . $name)
				->setParameter($name, '%' . $value . '%');
		}

		$queryBuilder->orderBy($queryBuilder->getDQLPart('from')[0]->getAlias() . '.' . $sort, $order);

		$queryBuilder->setMaxResults($this->limit);
		$queryBuilder->setFirstResult($this->offset);

		return $queryBuilder->getQuery()->getResult();
	}


	public function getCount($filters)
	{
		$queryBuilder = clone $this->queryBuilder;

		foreach ($filters as $name => $value) {
			$queryBuilder->where($queryBuilder->getDQLPart('from')[0]->getAlias() . '.' . $name . ' = :' . $name)
				->setParameter($name, $value);
		}

		return (new Paginator($queryBuilder))->count();
	}

}
