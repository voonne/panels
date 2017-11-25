<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\TablePanel\Adapters;

use Nette\SmartObject;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\TablePanel\TablePanel;


class ArrayAdapter implements IAdapter
{

	use SmartObject;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var integer
	 */
	private $limit;

	/**
	 * @var integer
	 */
	private $offset;


	public function __construct(array $data)
	{
		$this->data = $data;
	}


	public function setLimit($limit)
	{
		$this->limit = $limit;
	}


	public function setOffset($offset)
	{
		$this->offset = $offset;
	}


	public function getResults($sort, $order)
	{
		if(!isset($this->limit)) {
			throw new InvalidStateException('Property limit must be set.');
		}

		if(!isset($this->offset)) {
			throw new InvalidStateException('Property offset must be set.');
		}

		usort($this->data, function ($a, $b) use ($sort, $order) {
			if ($a->{$sort} == $b->{$sort}) {
				return 0;
			} else if (($order == TablePanel::ORDER_ASC && $a->{$sort} < $b->{$sort}) || ($order == TablePanel::ORDER_DESC && $a->{$sort} > $b->{$sort})) {
				return -1;
			} else {
				return 1;
			}
		});

		return array_slice($this->data, $this->offset, $this->limit);
	}


	public function getCount()
	{
		return count($this->data);
	}

}
