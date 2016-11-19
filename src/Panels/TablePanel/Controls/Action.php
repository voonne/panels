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
use Nette\Utils\Callback;


class Action
{

	use SmartObject;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var callable
	 */
	private $linkCallback;


	public function __construct($name, $label, callable $linkCallback)
	{
		$this->name = $name;
		$this->label = $label;
		$this->linkCallback = $linkCallback;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}


	/**
	 * @param object $row
	 *
	 * @return string
	 */
	public function getLink($row)
	{
		return Callback::invokeArgs($this->linkCallback, [$row]);
	}

}
