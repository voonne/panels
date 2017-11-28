<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\TablePanel\Adapters;


interface IAdapter
{

	public function setLimit($limit);


	public function setOffset($offset);


	public function getResults($filters, $sort, $order);


	public function getCount($filters);

}
