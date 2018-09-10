<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\TablePanel\Controls;

use Nette\SmartObject;
use Voonne\Panels\InvalidArgumentException;


class Filter
{

	use SmartObject;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var array|null
	 */
	private $items = [];

	const TYPE_TEXT = 'TEXT';
	const TYPE_SELECT = 'SELECT';
	const TYPE_DATETIME = 'DATETIME';


	public function __construct($type, array $items = null)
	{
		if (!in_array($type, [self::TYPE_TEXT, self::TYPE_SELECT, self::TYPE_DATETIME])) {
			throw new InvalidArgumentException("Type must be '" . self::TYPE_TEXT . "', '" . self::TYPE_SELECT . "' or '" . self::TYPE_DATETIME . "', '" . $type . "' given.");
		}

		$this->type = $type;
		$this->items = $items;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @return array|null
	 */
	public function getItems()
	{
		return $this->items;
	}

}
