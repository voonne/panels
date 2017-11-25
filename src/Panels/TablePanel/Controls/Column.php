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
use Voonne\Panels\InvalidStateException;


class Column
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
	 * @var boolean
	 */
	private $sortable = true;

	/**
	 * @var string|null
	 */
	private $template;


	public function __construct($name, $label)
	{
		$this->name = $name;
		$this->label = $label;
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
	 * @return boolean
	 */
	public function isSortable()
	{
		return $this->sortable;
	}


	/**
	 * @return string|null
	 */
	public function getTemplate()
	{
		return $this->template;
	}


	/**
	 * @param object $row
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 */
	public function getContent($row)
	{
		if (method_exists($row, 'get' . ucfirst($this->name))) {
			return $row->{'get' . ucfirst($this->name)}();
		} else if (isset($row->{$this->name})) {
			return $row->{$this->name};
		} else {
			throw new InvalidStateException('Method $object->get' . ucfirst($this->name) . '() or field $object->' . $this->name . ' was not found on row object.');
		}
	}


	/**
	 * @return $this
	 */
	public function enableSorting()
	{
		$this->sortable = true;

		return $this;
	}


	/**
	 * @return $this
	 */
	public function disableSorting()
	{
		$this->sortable = false;

		return $this;
	}


	/**
	 * @param string $template
	 *
	 * @return $this
	 */
	public function setTemplate($template)
	{
		$this->template = $template;

		return $this;
	}

}
